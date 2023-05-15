<?php

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use App\Models\PageModel;
use App\Models\PageContentModel;
use App\Services\FileService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PageController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function home_page(){
        return view('pages.admin.page_content.home')->with('page_detail', PageModel::find(1))->with('page_content_detail', PageContentModel::where('page_id',1)->get())->with('page_name', 'Home');
    }

    public function about_page(){
        return view('pages.admin.page_content.home')->with('page_detail', PageModel::find(2))->with('page_content_detail', PageContentModel::where('page_id',2)->get())->with('page_name', 'About');
    }

    public function edit_dynamic_page($id){
        $page_detail = PageModel::findOrFail($id);
        $page_content_detail = PageContentModel::where('page_id',$id)->get();
        return view('pages.admin.page_content.edit')->with('page_detail', $page_detail)->with('page_content_detail', $page_content_detail)->with('page_name', $page_detail->page_name);
    }

    public function getPageContent(Request $req){
        $rules = array(
            'id' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
        );
        $messages = array(
            'id.required' => 'Please enter the id !',
        );

        $validator = Validator::make($req->all(), $rules, $messages);
        if($validator->fails()){
            return response()->json(["form_error"=>$validator->errors()], 400);
        }
        return response()->json(['data'=>PageContentModel::findOrFail($req->id)], 200);
    }

    public function dynamic_page_list(Request $request){
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $data = PageModel::where('id', '!=',1)->where('id', '!=',2)->where('title', 'like', '%' . $search . '%')
            ->orWhere('page_name', 'like', '%' . $search . '%')
            ->orWhere('url', 'like', '%' . $search . '%')
            ->orderBy('id', 'DESC');
            $data = $data->paginate(10);
        }else{
            $data = PageModel::where('id', '!=',1)->where('id', '!=',2)->orderBy('id', 'DESC')->paginate(10);
        }
        return view('pages.admin.page_content.list')->with('country', $data);
    }

    public function storePage(PageCreateRequest $req){

        $data = PageModel::create([
            ...$req->validated(),
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('edit_dynamic_page', $data->id):$req->refreshUrl, "message" => "Data updated successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function updatePage(PageUpdateRequest $req, $id){
        $data = PageModel::findOrFail($id);

        $data->update([
            ...$req->validated(),
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?URL::previous():$req->refreshUrl, "message" => "Data updated successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function deletePage($id){
        $data = PageModel::where('id', '!=',1)->where('id', '!=',2)->findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(URL::previous())->with('success_status', 'Data Deleted permanently.');
    }

    public function storePageContent(PageContentCreateRequest $req) {

        $data = PageContentModel::create([
            ...$req->except(['image_position', 'image']),
        ]);

        if($req->hasFile('image')){
            $data->image = (new FileService)->save_image('image', 'public/upload/pages');
            $data->image_position = $req->image_position;
        }

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?URL::previous():$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function updatePageContent(PageContentUpdateRequest $req) {

        $data = PageContentModel::findOrFail($req->id);

        $data->update([
            ...$req->except(['id', 'image']),
        ]);

        if($req->hasFile('image')){
            (new FileService)->remove_file($data->image, 'app/public/upload/pages/');
            (new FileService)->remove_file('compressed-'.$data->image, 'app/public/upload/pages/');
            $data->image = (new FileService)->save_image('image', 'public/upload/pages');
        }

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?URL::previous():$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function deletePageContent($id){
        $data = PageContentModel::findOrFail($id);
        (new FileService)->remove_file($data->image, 'app/public/upload/pages/');
        (new FileService)->remove_file('compressed-'.$data->image, 'app/public/upload/pages/');
        $data->forceDelete();
        return redirect()->intended(URL::previous())->with('success_status', 'Data Deleted permanently.');
    }
}

class PageCreateRequest extends FormRequest
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
            'page_name' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i','unique:pages,page_name'],
            'url' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i','unique:pages,url'],
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
            'url.required' => 'Please enter the url !',
            'url.regex' => 'Please enter the valid url !',
            'url.unique' => 'This url is already taken!',
            'page_name.unique' => 'This page name is already taken!',
            'page_name.required' => 'Please enter the page name !',
            'page_name.regex' => 'Please enter the valid page name !',
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

class PageUpdateRequest extends PageCreateRequest
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
            'page_name' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i','unique:pages,page_name,'.$this->route('id')],
            'url' => ['nullable','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i','unique:pages,url,'.$this->route('id')],
        ];
    }
}

class PageContentCreateRequest extends FormRequest
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
            'heading' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\?\'\r\n+=,]+$/i'],
            'description_unformatted' => ['required'],
            'page_id' => ['required'],
            'image_position' => ['required'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp'],
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
            'heading.required' => 'Please enter the heading !',
            'heading.regex' => 'Please enter the valid heading !',
            'description_unformatted.required' => 'Please enter the description !',
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

class PageContentUpdateRequest extends PageContentCreateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'heading' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\?\'\r\n+=,]+$/i'],
            'description_unformatted' => ['required'],
            'id' => ['required'],
            'page_id' => ['required'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp'],
        ];
    }
}
