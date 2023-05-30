<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class LoginController extends Controller
{

    public function index(){
        return view('pages.admin.auth.login');
    }

    public function authenticate(Request $request){

        (new RateLimitService($request))->ensureIsNotRateLimited(3);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = Purify::clean($request->only('email', 'password'));
        $credentials['status'] = 1;
        $credentials['userType'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            (new RateLimitService($request))->clearRateLimit();
            return redirect()->intended(route('dashboard'));
        }

        return redirect(route('login'))->with('error_status', 'Oops! You have entered invalid credentials');

    }
}
