<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use App\Services\RateLimitService;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginPageController extends Controller
{
    public function index(){
        return view('pages.main.auth.login')->with('breadcrumb','Sign In');
    }

    public function authenticate(Request $request){

        (new RateLimitService($request))->ensureIsNotRateLimited(3);

        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','regex:/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i'],
        ],
        [
            'email.required' => 'Please enter the email !',
            'email.email' => 'Please enter the valid email !',
            'password.required' => 'Please enter the password !',
            'password.regex' => 'Please enter the valid password !',
        ]);

        $credentials = Purify::clean($request->only('email', 'password', 'remember'));
        // $credentials['status'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            (new RateLimitService($request))->clearRateLimit();
            return redirect()->intended(route('content_dashboard'));
        }

        return redirect(route('signin'))->with('error_popup', 'Oops! You have entered invalid credentials');
    }
}
