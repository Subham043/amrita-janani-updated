<?php

namespace App\Http\Controllers\Admin\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\ImageModel;
use App\Exports\ImageExport;
use App\Services\FileService;
use App\Services\TagService;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use App\Support\Types\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class ImageController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function create() {
        $tags = ImageModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];

        return view('pages.admin.image.create')->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function store(ImageCreateRequest $req) {

        $data = ImageModel::create([
            ...$req->except(['status', 'restricted', 'image']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        if($req->hasFile('image')){
            $data->image = (new FileService)->save_image('image', 'public/upload/images');
        }

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('image_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function edit($id) {
        $data = ImageModel::findOrFail($id);
        $tags = ImageModel::select('tags', 'topics')->whereNotNull('tags')->orWhereNotNull('topics')->get();
        $tags_data = (new TagService)->get_tags($tags);
        $tags_exist = $tags_data['tags_exist'];
        $topics_exist = $tags_data['topics_exist'];
        return view('pages.admin.image.edit')->with('country',$data)->with("tags_exist",$tags_exist)->with("topics_exist",$topics_exist);
    }

    public function update(ImageUpdateRequest $req, $id) {
        $data = ImageModel::findOrFail($id);

        $data->update([
            ...$req->except(['status', 'restricted', 'image']),
            'status' => $req->status == "on" ? 1 : 0,
            'restricted' => $req->restricted == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        if($req->hasFile('image')){
            (new FileService)->remove_file($data->image, 'app/public/upload/images/');
            (new FileService)->remove_file('compressed-'.$data->image, 'app/public/upload/images/');
            $data->image = (new FileService)->save_image('image', 'public/upload/images');
        }

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('image_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function restoreTrash($id){
        $data = ImageModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $data->restore();
        return redirect()->intended(route('image_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function restoreAllTrash(){
        ImageModel::withTrashed()->whereNotNull('deleted_at')->restore();
        return redirect()->intended(route('image_view_trash'))->with('success_status', 'Data Restored successfully.');
    }

    public function delete($id){
        $data = ImageModel::findOrFail($id);
        $data->delete();
        return redirect()->intended(route('image_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function deleteTrash($id){
        $data = ImageModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        (new FileService)->remove_file($data->image, 'app/public/upload/images/');
        (new FileService)->remove_file('compressed-'.$data->image, 'app/public/upload/images/');
        $data->forceDelete();
        return redirect()->intended(route('image_view_trash'))->with('success_status', 'Data Deleted permanently.');
    }

    public function view() {
        $query = ImageModel::with(['User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.image.list')->with('country', $data);
    }

    public function viewTrash() {
        $query = ImageModel::withTrashed()->whereNotNull('deleted_at')->with(['User'])->orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.image.list_trash')->with('country', $data);
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
        $data = ImageModel::findOrFail($id);
        $url = "";
        return view('pages.admin.image.display')->with('country',$data)->with('url',$url);
    }

    public function displayTrash($id) {
        $data = ImageModel::withTrashed()->whereNotNull('deleted_at')->findOrFail($id);
        $url = "";
        return view('pages.admin.image.display_trash')->with('country',$data)->with('url',$url);
    }

    public function excel(){
        return Excel::download(new ImageExport, 'image.xlsx');
    }

    public function bulk_upload(){
        return view('pages.admin.image.bulk_upload');
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
                if(file_exists(storage_path('app/public/zip/images').'/'.$value['image'])){

                    $exceldata = new ImageModel;
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
                    Storage::move('public/zip/images'.'/'.$value['image'], 'public/upload/images'.'/'.$uuid.'-'.$value['image']);
                    $exceldata->image = $uuid.'-'.$value['image'];


                    $img = Image::make(storage_path('app/public/upload/images').'/'.$uuid.'-'.$value['image']);
                    $img->resize(300, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(storage_path('app/public/upload/images').'/'.'compressed-'.$uuid.'-'.$value['image']);

                    $result = $exceldata->save();
                }



            }
            return response()->json(["url"=>empty($req->refreshUrl)?route('image_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }

    }



}


class ImageCreateRequest extends FormRequest
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
            'image' => ['nullable','image','mimes:jpeg,png,jpg,webp'],
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
            'image.image' => 'Please enter a valid image !',
            'image.mimes' => 'Please enter a valid image !',
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

class ImageUpdateRequest extends ImageCreateRequest
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
            'image' => ['nullable','image','mimes:jpeg,png,jpg,webp'],
        ];
    }
}
