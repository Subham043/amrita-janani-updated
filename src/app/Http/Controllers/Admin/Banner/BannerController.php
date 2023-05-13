<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use App\Models\BannerModel;
use App\Services\FileService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Stevebauman\Purify\Facades\Purify;
use Webpatser\Uuid\Uuid;

class BannerController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    public function banner(){
        return view('pages.admin.banner.banner')->with('images', BannerModel::all());
    }

    public function storeBanner(BannerCreateRequest $req){

        $data = BannerModel::create([
            ...$req->except(['image']),
            'user_id' => Auth::user()->id,
        ]);

        if($req->hasFile('image')){
            $data->image = (new FileService)->save_image('image', 'public/upload/banners');
        }

        $result = $data->save();

        if($result){
            return response()->json(["url"=>empty($req->refreshUrl)?route('banner_view'):$req->refreshUrl, "message" => "Data updated successfully.", "data" => $data], 201);
        }else{
            return response()->json(["error"=>"something went wrong. Please try again"], 400);
        }
    }


    public function deleteBanner($id){
        $data = BannerModel::findOrFail($id);
        (new FileService)->remove_file($data->image, 'app/public/upload/banners/');
        (new FileService)->remove_file('compressed-'.$data->image, 'app/public/upload/banners/');
        $data->forceDelete();
        return redirect()->intended(URL::previous())->with('success_status', 'Data Deleted permanently.');
    }


}

class BannerCreateRequest extends FormRequest
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
            'image' => ['required','image','mimes:jpeg,png,jpg,webp'],
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
