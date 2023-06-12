<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Validation\Rules\Password as PasswordValidation;
use Illuminate\Auth\Events\Registered;

class RegisterPageController extends Controller
{
    public function index(){
        return view('pages.main.auth.register')->with('breadcrumb','Sign Up');
    }

    public function store(Request $req) {
        $req->validate([
            'name' => ['required','regex:/^[a-zA-Z0-9\s]*$/'],
            'email' => ['required','email','unique:users'],
            'phone' => ['nullable','regex:/^[0-9]*$/','unique:users'],
            'password' => ['required',
                'string',
                PasswordValidation::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
            'cpassword' => ['required_with:password', 'same:password'],
        ],
        [
            'name.required' => 'Please enter the name !',
            'name.regex' => 'Please enter the valid name !',
            'email.required' => 'Please enter the email !',
            'email.email' => 'Please enter the valid email !',
            'phone.required' => 'Please enter the phone !',
            'phone.regex' => 'Please enter the valid phone !',
            'password.required' => 'Please enter the password !',
            'password.regex' => 'Please enter the valid password !',
            'cpassword.required' => 'Please enter your confirm password !',
            'cpassword.same' => 'password & confirm password must be the same !',
        ]);

        $user = new User;
        $user->name = Purify::clean($req->name);
        $user->email = Purify::clean($req->email);
        $user->phone = Purify::clean($req->phone);
        $user->userType = 2;
        $user->password = Hash::make(Purify::clean($req->password));
        $user->otp = rand(1000,9999);
        $user->status = 0;
        $user->save();


        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('content_dashboard'))->with('success_status', 'Logged in successfully.');

    }

}
