<?php

namespace App\Http\Controllers\Admin\Video;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\VideoModel;
use App\Models\LanguageModel;
use App\Models\VideoLanguage;
use App\Exports\VideoExport;
use App\Services\TagService;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Types\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function create() {

        $tags = VideoModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];

        return view('pages.admin.video.create')->with('languages', LanguageModel::all())->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function store(VideoCreateRequest $req) {

        $data = VideoModel::create([
            ...$req->except(['status', 'restricted']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        $data->Languages()->sync($req->language);

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('video_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function edit($id) {
        $data = VideoModel::findOrFail($id);
        $tags = VideoModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];
        return view('pages.admin.video.edit')->with('country',$data)->with('languages', LanguageModel::all())->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function update(Request $req, $id) {
        $data = VideoModel::findOrFail($id);

        $data->update([
            ...$req->except(['status', 'restricted']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        $data->Languages()->sync($req->language);

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('video_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function restoreTrash($id){
        $data = VideoModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $data->restore();
        return redirect()->intended(route('video_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function restoreAllTrash(){
        VideoModel::withTrashed()->whereNotNull('deleted_at')->restore();
        return redirect()->intended(route('video_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function delete($id){
        $data = VideoModel::findOrFail($id);
        $data->delete();
        return redirect()->intended(route('video_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function deleteTrash($id){
        $data = VideoModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(route('video_view_trash'))->with('success_status', 'Data Deleted successfully.');
    }

    public function view() {
        $query = VideoModel::with(['User', 'Languages'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.video.list')->with('country', $data)->with('languages', LanguageModel::all());
    }

    public function viewTrash() {
        $query = VideoModel::withTrashed()->whereNotNull('deleted_at')->with(['User', 'Languages'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.video.list_trash')->with('country', $data)->with('languages', LanguageModel::all());
    }

    private function pagination_query(Builder $query): Builder
    {
        if (request()->has('search')) {
            $search = request()->input('search');
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('year', 'like', '%' . $search . '%')
                ->orWhere('deity', 'like', '%' . $search . '%')
                ->orWhere('version', 'like', '%' . $search . '%')
                ->orWhere('tags', 'like', '%' . $search . '%')
                ->orWhere('uuid', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function display($id) {
        $data = VideoModel::findOrFail($id);
        $url = "";
        return view('pages.admin.video.display')->with('country',$data)->with('languages', LanguageModel::all())->with('url',$url);
    }

    public function displayTrash($id) {
        $data = VideoModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $url = "";
        return view('pages.admin.video.display_trash')->with('country',$data)->with('languages', LanguageModel::all())->with('url',$url);
    }

    public function excel(){
        return Excel::download(new VideoExport, 'video.xlsx');
    }

    public function bulk_upload(){
        return view('pages.admin.video.bulk_upload');
    }

    public function bulk_upload_store(Request $req) {
        $rules = array(
            'excel' => ['required','mimes:xls,xlsx'],
        );
        $messages = array(
            'excel.required' => 'Please select an excel !',
            'excel.mimes' => 'Please enter a valid excel !',
        );

        $validator = Validator::make($req->all(), $rules, $messages);
        if($validator->fails()){
            return response()->json(["form_error"=>$validator->errors()], 400);
        }

        $path = $req->file('excel')->getRealPath();
        $data = (new FastExcel)->import($path);

        if($data->count() == 0)
        {
            return response()->json(["form_error"=>"Please enter atleast one row of data in the excel."], 400);
        }elseif($data->count() > 30)
        {
            return response()->json(["form_error"=>"Maximum 30 rows of data in the excel are allowed."], 400);
        }else{
            foreach ($data as $key => $value) {

                $exceldata = new VideoModel;
                $exceldata->title = $value['title'];
                $exceldata->description = $value['description'];
                $exceldata->description_unformatted = $value['description'];
                $exceldata->year = $value['year'];
                $exceldata->deity = $value['deity'];
                $exceldata->tags = $value['tags'];
                if(!empty($value['topics'])){
                    $exceldata->topics = $value['topics'];
                }
                $exceldata->version = $value['version'];
                $exceldata->video = $value['video'];
                $exceldata->status = 1;
                $exceldata->user_id = Auth::user()->id;

                switch ($value['restricted']) {
                    case 'true':
                    case 'True':
                    case 'TRUE':
                    case '1':
                    case 'restricted':
                        # code...
                        $exceldata->restricted = 1;
                        break;

                    default:
                        # code...
                        $exceldata->restricted = 0;
                        break;
                }

                $result = $exceldata->save();

                $arr = array_map('strval', explode(',', $value['language']));
                for($i=0; $i < count($arr); $i++) {
                    $languageCheck = LanguageModel::where('name','like',$arr[$i])->first();
                    if($languageCheck){
                        $language = new VideoLanguage;
                        $language->video_id = $exceldata->id;
                        $language->language_id = $languageCheck->id;
                        $language->save();
                    }
                }


            }
            return response()->json(["url"=>empty($req->refreshUrl)?route('video_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }

    }



}


class VideoCreateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'deity' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'version' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'year' => ['nullable','regex:/^[0-9]*$/'],
            'language' => ['required','array','min:1'],
            'language.*' => ['required','regex:/^[0-9]*$/'],
            'video' => ['required'],
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
            'title.required' => 'Please enter the title !',
            'title.regex' => 'Please enter the valid title !',
            'deity.regex' => 'Please enter the valid deity !',
            'version.regex' => 'Please enter the valid version !',
            'year.regex' => 'Please enter the valid year !',
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

class VideoUpdateRequest extends VideoCreateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'deity' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'version' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'year' => ['nullable','regex:/^[0-9]*$/'],
            'language' => ['required','array','min:1'],
            'language.*' => ['required','regex:/^[0-9]*$/'],
            'video' => ['required'],
        ];
    }
}
