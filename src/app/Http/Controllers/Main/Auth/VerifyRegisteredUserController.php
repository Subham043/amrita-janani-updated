<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyRegisteredUserController extends Controller
{
    public function index(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return redirect()->intended(route('content_dashboard'))->with('success_status', 'Oops! you are already a verified user.');
        }
        return view('pages.main.auth.verify_user')->with('breadcrumb','Verify Email');
    }

    public function resend_notification(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return redirect()->intended(route('content_dashboard'))->with('success_status', 'Oops! you are already a verified user.');
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success_status', 'Verification link sent!');
    }

    public function verify_email(EmailVerificationRequest $request, $id, $hash){
        if($request->user()->hasVerifiedEmail()){
            return redirect()->intended(route('content_dashboard'))->with('success_status', 'Oops! you are already a verified user.');
        }
        $request->fulfill();
        return redirect()->intended(route('content_dashboard'))->with('success_status', 'Logged in successfully.');
    }


}
