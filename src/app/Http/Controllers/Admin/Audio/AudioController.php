<?php

namespace App\Http\Controllers\Admin\Audio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\AudioModel;
use App\Models\AudioLanguage;
use App\Models\LanguageModel;
use App\Exports\AudioExport;
use App\Services\FileService;
use App\Services\TagService;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Types\UserType;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Support\Mp3\MP3File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Builder;

class AudioController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function create() {
        $tags = AudioModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];

        return view('pages.admin.audio.create')->with('languages', LanguageModel::all())->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function store(AudioCreateRequest $req) {

        $data = AudioModel::create([
            ...$req->except(['status', 'restricted', 'audio']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        if($req->hasFile('audio')){
            $data->audio = (new FileService)->save_file('audio', 'public/upload/audios');
            $data->duration = (new FileService)->mp3_file_duration($data->audio);
        }

        $result = $data->save();

        $data->Languages()->sync($req->language);


        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('audio_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function edit($id) {
        $data = AudioModel::findOrFail($id);
        $tags = AudioModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];

        return view('pages.admin.audio.edit')->with('country',$data)->with('languages', LanguageModel::all())->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function update(AudioUpdateRequest $req, $id) {
        $data = AudioModel::findOrFail($id);

        $data->update([
            ...$req->except(['status', 'restricted', 'audio']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        if($req->hasFile('audio')){
            (new FileService)->remove_file($data->audio, 'app/public/upload/audios/');
            $data->audio = (new FileService)->save_file('audio', 'public/upload/audios');
            $data->duration = (new FileService)->mp3_file_duration($data->audio);
        }

        $result = $data->save();

        $data->Languages()->sync($req->language);

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('audio_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function restoreTrash($id){
        $data = AudioModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $data->restore();
        return redirect()->intended(route('audio_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function restoreAllTrash(){
        AudioModel::withTrashed()->whereNotNull('deleted_at')->restore();
        return redirect()->intended(route('audio_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function delete($id){
        $data = AudioModel::findOrFail($id);
        $data->delete();
        return redirect()->intended(route('audio_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function deleteTrash($id){
        $data = AudioModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        (new FileService)->remove_file($data->audio, 'app/public/upload/audios/');
        $data->forceDelete();
        return redirect()->intended(route('audio_view_trash'))->with('success_status', 'Data Deleted permanently.');
    }

    public function view() {
        $query = AudioModel::with(['Languages','User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.audio.list')->with('country', $data)->with('languages', LanguageModel::all());
    }

    public function viewTrash() {
        $query = AudioModel::withTrashed()->whereNotNull('deleted_at')->with(['Languages','User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.audio.list_trash')->with('country', $data)->with('languages', LanguageModel::all());
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
        $data = AudioModel::with(['Languages','User'])->findOrFail($id);
        return view('pages.admin.audio.display')->with('country',$data)->with('languages', LanguageModel::all());
    }

    public function displayTrash($id) {
        $data = AudioModel::withTrashed()->whereNotNull('deleted_at')->with(['Languages','User'])->findOrFail($id);
        return view('pages.admin.audio.display_trash')->with('country',$data)->with('languages', LanguageModel::all());
    }

    public function excel(){
        return Excel::download(new AudioExport, 'audio.xlsx');
    }

    public function bulk_upload(){
        return view('pages.admin.audio.bulk_upload');
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
                    if(file_exists(storage_path('app/public/zip/audios').'/'.$value['audio'])){

                        $exceldata = new AudioModel;
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


                        $uuid = Uuid::generate(4)->string;
                        Storage::move('public/zip/audios'.'/'.$value['audio'], 'public/upload/audios'.'/'.$uuid.'-'.$value['audio']);
                        $exceldata->audio = $uuid.'-'.$value['audio'];

                        try {
                            //code...
                            $mp3file = new MP3File(storage_path('app/public/upload/audios/'.$uuid.'-'.$value['audio']));//http://www.npr.org/rss/podcast.php?id=510282
                            $duration2 = $mp3file->getDuration();//(slower) for VBR (or CBR)
                            $exceldata->duration = MP3File::formatTime($duration2);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }

                        $result = $exceldata->save();
                        $arr = array_map('strval', explode(',', $value['language']));
                        for($i=0; $i < count($arr); $i++) {
                            $languageCheck = LanguageModel::where('name','like',$arr[$i])->first();
                            if($languageCheck){
                                $language = new AudioLanguage;
                                $language->audio_id = $exceldata->id;
                                $language->language_id = $languageCheck->id;
                                $language->save();
                            }
                        }
                    }


            }
            return response()->json(["url"=>empty($req->refreshUrl)?route('audio_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }

    }



}


class AudioCreateRequest extends FormRequest
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
            'audio' => ['required','mimes:wav,mp3,aac'],
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
            'language.required' => 'Please enter the language !',
            'language.regex' => 'Please enter the valid language !',
            'audio.mimes' => 'Please enter a valid audio !',
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

class AudioUpdateRequest extends AudioCreateRequest
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
            'audio' => ['nullable','mimes:wav,mp3,aac'],
        ];
    }
}
