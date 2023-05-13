<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use App\Models\BannerQuoteModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Stevebauman\Purify\Facades\Purify;

class BannerQuoteController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function banner_quote(){
        return view('pages.admin.banner.banner_quote')->with('quotes', BannerQuoteModel::orderBy('id', 'DESC')->get());
    }

    public function storeBannerQuote(BannerQuoteCreateRequest $req){

        $data = BannerQuoteModel::create([
            ...$req->validated(),
            'user_id' => Auth::user()->id,
        ]);

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('banner_quote_view'):$req->refreshUrl, "message" => "Data updated successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }


    public function deleteBannerQuote($id){
        $data = BannerQuoteModel::findOrFail($id);
        $data->forceDelete();
        return redirect()->intended(URL::previous())->with('success_status', 'Data Deleted permanently.');
    }


}


class BannerQuoteCreateRequest extends FormRequest
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
            'quote' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
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
            'quote.required' => 'Please enter the quote !',
            'quote.regex' => 'Please enter the valid quote !',
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
