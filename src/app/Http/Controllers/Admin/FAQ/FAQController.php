<?php

namespace App\Http\Controllers\Admin\FAQ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\FAQModel;
use App\Support\Types\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class FAQController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function store(FaqCreateRequest $req) {

        $data = FAQModel::create([
            ...$req->validated(),
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('faq_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function update(FaqUpdateRequest $req) {

        $data = FAQModel::findOrFail($req->id);

        $data->update([
            ...$req->except(['id']),
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('faq_view'):$req->refreshUrl, "message" => "Data Stored successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }

    public function delete($id){
        $data = FAQModel::findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(route('faq_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function view() {
        $data = FAQModel::orderBy('id', 'DESC')->get();
        return view('pages.admin.faq.list')->with('faq', $data);
    }



}

class FaqCreateRequest extends FormRequest
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
            'question' => ['required'],
            'answer' => ['required'],
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
            'question.required' => 'Please enter the question !',
            'answer.required' => 'Please enter the answer !',
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


class FaqUpdateRequest extends FaqCreateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required'],
            'question' => ['required'],
            'answer' => ['required'],
        ];
    }
}
