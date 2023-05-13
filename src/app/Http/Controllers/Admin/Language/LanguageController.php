<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\LanguageModel;
use App\Exports\LanguageExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Types\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Builder;

class LanguageController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function create() {

        return view('pages.admin.language.create');
    }

    public function store(LanguageRequest $req) {

        $data = LanguageModel::create([
            ...$req->except(['status']),
            'status' => $req->status == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('language_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function edit($id) {
        $data = LanguageModel::findOrFail($id);

        return view('pages.admin.language.edit')->with('country',$data);
    }

    public function update(LanguageRequest $req, $id) {
        $data = LanguageModel::findOrFail($id);

        $data->update([
            ...$req->except(['status']),
            'status' => $req->status == "on" ? 1 : 0,
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('language_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function delete($id){
        $data = LanguageModel::findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(route('language_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function view() {
        $query = LanguageModel::orderBy('id', 'DESC');
        $data = $this->pagination_query($query)->paginate(10);
        return view('pages.admin.language.list')->with('country', $data);
    }

    private function pagination_query(Builder $query): Builder
    {
        if (request()->has('search')) {
            $search = request()->input('search');
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function display($id) {
        $data = LanguageModel::findOrFail($id);
        $url = "";
        return view('pages.admin.language.display')->with('country',$data)->with('url',$url);
    }

    public function excel(){
        return Excel::download(new LanguageExport, 'language.xlsx');
    }



}

class LanguageRequest extends FormRequest
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
            'name' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
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
            'name.required' => 'Please enter the name !',
            'name.regex' => 'Please enter the valid name !',
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
