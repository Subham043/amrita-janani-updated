<?php

namespace App\Http\Controllers\Main\Contracts;

use App\Http\Controllers\Controller;
use App\Jobs\SendAdminAccessRequestEmailJob;
use App\Jobs\SendAdminReportEmailJob;
use App\Models\LanguageModel;
use App\Models\SearchHistory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class CommonContentController extends Controller
{
    protected $model;
    protected $with_favourite_model;
    protected $with_access_model;
    protected $with_report_model;

    public function __construct(string $model, string $with_favourite_model, string $with_access_model, string $with_report_model)
    {
        $this->initializeSubject($model, $with_favourite_model, $with_access_model, $with_report_model);
    }

    protected function initializeSubject($model, $with_favourite_model, $with_access_model, $with_report_model): static
    {

        $this->model = $model;
        $this->with_favourite_model = $with_favourite_model;
        $this->with_access_model = $with_access_model;
        $this->with_report_model = $with_report_model;

        return $this;
    }

    protected function query(){
        return $this->model::where('status', 1);
    }

    protected function sort_query(){
        if(request()->has('sort')){
            if(request()->input('sort')=="oldest"){
                return $this->query()->orderBy('id', 'ASC');
            }elseif(request()->input('sort')=="a-z"){
                return $this->query()->orderBy('title', 'ASC');
            }elseif(request()->input('sort')=="z-a"){
                return $this->query()->orderBy('title', 'DESC');
            }
        }
        return $this->query()->orderBy('id', 'DESC');
    }

    protected function index_base(string $view, string $breadcrumb, string $key, string $with_fav){

        $data = $this->sort_query();

        if(request()->has('search')){
            $search  = request()->input('search');
            $data->where(function($q) use($search){
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('year', 'like', '%' . $search . '%')
                ->orWhere('deity', 'like', '%' . $search . '%')
                ->orWhere('version', 'like', '%' . $search . '%')
                ->orWhere('tags', 'like', '%' . $search . '%')
                ->orWhere('description_unformatted', 'like', '%' . $search . '%')
                ->orWhere('uuid', 'like', '%' . $search . '%');
            });

            $searchHistory = new SearchHistory();
            $searchHistory->search = $search;
            $searchHistory->user_id = Auth::user()->id;
            $searchHistory->screen = 2;
            $searchHistory->save();
        }

        if(request()->has('language')){
            $arr = array_map('intval', explode(',', request()->input('language')));
            $data->with(['Languages']);
            $data->whereHas('Languages', function($q) use($arr) {
                $q->whereIn('language_id', $arr);
            });
        }

        if(request()->has('filter')){
            $data->with([$with_fav]);
            $data->whereHas($with_fav, function($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }

        $data = $data->where('status', 1)->paginate(6)->withQueryString();

        return view($view)->with('breadcrumb',$breadcrumb)
        ->with($key.'s',$data)
        ->with('languages',LanguageModel::all());
    }

    protected function view_base(string $view, string $key, $uuid){
        $data = $this->query()->where('uuid', $uuid)->firstOrFail();
        $data->views = $data->views +1;
        $data->save();

        try {
            $dataAccess = $this->with_access_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->first();
        } catch (\Throwable $th) {
            //throw $th;
            $dataAccess = null;
        }

        return view($view)->with('breadcrumb', $key.' - '.$data->title)
        ->with($key.'Access',$dataAccess)
        ->with($key,$data);
    }

    protected function make_favourite_base(string $redirect, string $key, $uuid){
        $data = $this->query()->where('uuid', $uuid)->firstOrFail();
        $dataFav = $this->with_favourite_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->get();

        if(count($dataFav)>0){
            $dataFav = $this->with_favourite_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->first();
            if($dataFav->status==1){
                $dataFav->status=0;
                $dataFav->save();
                $data->favourites = $data->favourites -1;
                $data->save();
                return redirect()->intended(route($redirect, $uuid));
            }else{
                $dataFav->status=1;
                $data->favourites = $data->favourites +1;
                $data->save();
                $dataFav->save();
                return redirect()->intended(route($redirect, $uuid));
            }
        }else{
            $dataFav = new $this->with_favourite_model;
            $dataFav[$key.'_id'] = $data->id;
            $dataFav->user_id = Auth::user()->id;
            $dataFav->status = 1;
            $dataFav->save();
            $dataFav->status=1;
            $data->favourites = $data->favourites +1;
            $data->save();
            return redirect()->intended(route($redirect, $uuid))->with('success_status', 'Made favourite successfully.');
        }

    }

    protected function request_access_base(ContentRequest $req, string $key, $uuid){
        $data = $this->query()->where('uuid', $uuid)->firstOrFail();

        $dataFav = $this->with_access_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->get();

        if(count($dataFav)>0){
            $dataFav = $this->with_access_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->first();
            $dataFav->status=0;
            $dataFav->message=$req->message;
            $dataFav->save();


        }else{
            $dataFav = new $this->with_access_model;
            $dataFav[$key.'_id'] = $data->id;
            $dataFav->user_id = Auth::user()->id;
            $dataFav->status = 0;
            $dataFav->message=$req->message;
            $dataFav->save();

            $details['name'] = Auth::user()->name;
            $details['email'] = Auth::user()->email;
            $details['filename'] = $data->title;
            $details['fileid'] = $data->uuid;
            $details['filetype'] = $key;
            $details['message'] = $dataFav->message;
            dispatch(new SendAdminAccessRequestEmailJob($details));
        }

        return response()->json(["message" => "Access requested successfully."], 201);
    }

    protected function report_base(ContentRequest $req, string $key, $uuid){
        $data = $this->query()->where('uuid', $uuid)->firstOrFail();

        $dataFav = $this->with_report_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->get();

        if(count($dataFav)>0){
            $dataFav = $this->with_report_model::where($key.'_id', $data->id)->where('user_id', Auth::user()->id)->first();
            $dataFav->status = 0;
            $dataFav->message=$req->message;
            $dataFav->save();

        }else{
            $dataFav = new $this->with_report_model;
            $dataFav[$key.'_id'] = $data->id;
            $dataFav->user_id = Auth::user()->id;
            $dataFav->status = 0;
            $dataFav->message=$req->message;
            $dataFav->save();

            $details['name'] = Auth::user()->name;
            $details['email'] = Auth::user()->email;
            $details['filename'] = $data->title;
            $details['fileid'] = $data->uuid;
            $details['filetype'] = $key;
            $details['message'] = $dataFav->message;
            dispatch(new SendAdminReportEmailJob($details));
        }

        return response()->json(["message" => "Reported successfully."], 201);
    }

    protected function search_query_base($key){

        $search  = request()->phrase;
        $data = [];
        $datas = $this->query()->where(function($q) use($search){
            $q->where('title', 'like', '%' . $search . '%')
            ->orWhere('year', 'like', '%' . $search . '%')
            ->orWhere('deity', 'like', '%' . $search . '%')
            ->orWhere('version', 'like', '%' . $search . '%')
            ->orWhere('tags', 'like', '%' . $search . '%')
            ->orWhere('description_unformatted', 'like', '%' . $search . '%')
            ->orWhere('uuid', 'like', '%' . $search . '%');
        })
        ->get();

        foreach ($datas as $value) {
            if(!in_array(array("name"=>$value->title, "group"=>$key), $data)){
                array_push($data,array("name"=>$value->title, "group"=>$key));
            }
        }

        $tags = $this->model::select('tags')->whereNotNull('tags')->where('tags', 'like', '%' . $search . '%')->get();
        foreach ($tags as $tag) {
            $arr = explode(",",$tag->tags);
            foreach ($arr as $i) {
                if (!(in_array(array("name"=>$i, "group"=>"Tags"), $data))){
                    array_push($data,array("name"=>$i, "group"=>"Tags"));
                }
            }
        }

        $searchHistory = SearchHistory::where('screen', 2)->where('search', 'like', '%' . $search . '%')->get();

        foreach ($searchHistory as $value) {
            if(!in_array(array("name"=>$value->search, "group"=>$key), $data) && !in_array(array("name"=>$value->search, "group"=>"Tags"), $data)){
                array_push($data,array("name"=>$value->search, "group"=>"Previous Searches"));
            }
        }

        return response()->json(["data"=>$data], 200);
    }

}


class ContentRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'captcha' => ['required','captcha']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Please enter the reason !',
            'message.regex' => 'Please enter the valid reason !',
            'captcha.captcha' => 'Please enter the valid captcha !',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->replace(
            Purify::clean(
                $this->all()
            )
        );
    }

}
