<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Support\Types\UserType;
use App\Models\User;
use App\Exports\UserExport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\URL;
use Stevebauman\Purify\Facades\Purify;

class UserController extends Controller
{
    public function __construct()
    {

       View::share('common', [
         'user_type' => UserType::lists()
        ]);
    }

    protected function query(){
        return User::where('id', '!=' , Auth::user()->id)->where("userType", "!=" , 1)->orderBy('id', 'DESC');
    }

    public function create() {

        return view('pages.admin.user.create');
    }

    public function store(UserCreateRequest $req) {

        $result = User::create([
            ...$req->except('phone'),
            'otp' => rand(1000,9999),
            'phone' => !empty($req->phone) ? $req->phone : null
        ]);
        if($result){
            return redirect()->intended(route('subadmin_view'))->with('success_status', 'Data Stored successfully.');
        }else{
            return redirect()->intended(route('subadmin_create'))->with('error_status', 'Something went wrong. Please try again');
        }
    }

    public function edit($id) {
        $country = $this->query()->findOrFail($id);
        return view('pages.admin.user.edit')->with('country',$country);
    }

    public function update(UserUpdateRequest $req, $id) {
        $country = User::findOrFail($id);

        $result = $country->update([
            ...$req->except(['password', 'phone']),
            'otp' => rand(1000,9999),
            'phone' => !empty($req->phone) ? $req->phone : null
        ]);

        if($result){
            return redirect()->intended(route('subadmin_edit',$country->id))->with('success_status', 'Data Updated successfully.');
        }else{
            return redirect()->intended(route('subadmin_edit',$country->id))->with('error_status', 'Something went wrong. Please try again');
        }
    }

    public function delete($id){
        $country = $this->query()->findOrFail($id);
        $country->forceDelete();
        return redirect()->intended(route('subadmin_view'))->with('success_status', 'Data Deleted successfully.');
    }

    public function view(Request $request) {
        $country = $this->query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $country->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $country = $country->paginate(10);
        return view('pages.admin.user.list')->with('country', $country);
    }

    public function display($id) {
        $country = $this->query()->findOrFail($id);
        return view('pages.admin.user.display')->with('country',$country);
    }

    public function makeUserPreviledge($id){
        $country = $this->query()->findOrFail($id);
        $country->update([
            'userType' => $country->userType==2 ? 3 : 2,
        ]);
        return redirect()->intended(URL::previous())->with('success_status', 'Changed user accessibility successfully.');
    }

    public function excel(){
        return Excel::download(new UserExport, 'user.xlsx');
    }

}


class UserCreateRequest extends FormRequest
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
            'name' => ['required','regex:/^[a-zA-Z0-9\s]*$/'],
            'userType' => ['required','regex:/^[a-zA-Z0-9\s]*$/'],
            'email' => ['required','email','unique:users'],
            'phone' => ['nullable','regex:/^[0-9]*$/','unique:users'],
            'password' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
            'cpassword' => ['required_with:password|same:password'],
            'status' => ['nullable'],
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
            'userType.required' => 'Please enter the user type !',
            'userType.regex' => 'Please enter the valid user type !',
            'email.required' => 'Please enter the email !',
            'email.email' => 'Please enter the valid email !',
            'phone.required' => 'Please enter the phone !',
            'phone.regex' => 'Please enter the valid phone !',
            'password.required' => 'Please enter the password !',
            'password.regex' => 'Please enter the valid password !',
            'cpassword.required' => 'Please enter your confirm password !',
            'cpassword.same' => 'password & confirm password must be the same !',
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

class UserUpdateRequest extends UserCreateRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','regex:/^[a-zA-Z0-9\s]*$/'],
            'userType' => ['required','regex:/^[a-zA-Z0-9\s]*$/'],
            'email' => ['required','email','unique:users,email,'.$this->route('id')],
            'phone' => empty($this->phone) ? ['nullable'] : ['nullable','regex:/^[0-9]*$/','unique:users,phone,'.$this->route('id')],
            'password' => ['nullable'],
            'cpassword' => ['nullable'],
            'status' => ['nullable'],
        ];
    }
}
