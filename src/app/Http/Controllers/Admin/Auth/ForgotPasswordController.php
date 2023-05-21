<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RateLimitService;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function index(){
        return view('pages.admin.auth.forgotpassword');
    }

    public function requestForgotPassword(Request $request) {

        (new RateLimitService($request))->ensureIsNotRateLimited(3);

        $request->validate([
            'email' => ['required','string','email','max:255','exists:App\Models\User,email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        if($status === Password::RESET_LINK_SENT){
            (new RateLimitService($request))->clearRateLimit();
            return redirect(route('forgotPassword'))->with(['success_status' => __($status)]);
        }
        return redirect(route('forgotPassword'))->with(['error_status' => __($status)]);

    }
}
