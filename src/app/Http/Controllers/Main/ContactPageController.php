<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Main\Contracts\CommonController;
use App\Models\Enquiry;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;

class ContactPageController extends CommonController
{
    public function index(){
        return parent::index_base('pages.main.contact', 'Contact');
    }

    public function contact_ajax(EnquiryRequest $req){

        $result = Enquiry::create($req->validated());
        if($result){
            return response()->json(["message" => "Message sent successfully."], 201);
        }else{
            return response()->json(["error_popup"=>"something went wrong. Please try again"], 400);
        }
    }
}

class EnquiryRequest extends FormRequest
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
            'name' => ['required','string','regex:/^[a-zA-Z\s]*$/'],
            'email' => ['required','email'],
            'phone' => ['nullable','regex:/^[0-9]*$/'],
            'subject' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
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
            'name.required' => 'Please enter the name !',
            'name.string' => 'Please enter the valid name !',
            'name.regex' => 'Please enter the valid name !',
            'email.required' => 'Please enter the email !',
            'email.email' => 'Please enter the valid email !',
            'phone.required' => 'Please enter the phone !',
            'phone.regex' => 'Please enter the valid phone !',
            'subject.required' => 'Please enter the subject !',
            'subject.regex' => 'Please enter the valid subject !',
            'message.required' => 'Please enter the message !',
            'message.regex' => 'Please enter the valid message !',
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
