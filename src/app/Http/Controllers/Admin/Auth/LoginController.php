<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class LoginController extends Controller
{

    public function index(){
        if (Auth::check()) {
            return redirect(route('dashboard'));
        }
        return view('pages.admin.auth.login');
    }

    public function authenticate(Request $request){
        if (Auth::check()) {
            return redirect(route('dashboard'));
        }
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = Purify::clean($request->only('email', 'password'));
        $credentials['status'] = 1;
        $credentials['userType'] = 1;

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('dashboard'))->with('success_status', 'Logged in successfully.');
        }

        return redirect(route('login'))->with('error_status', 'Oops! You have entered invalid credentials');

        return view('pages.admin.auth.login');
    }
}
