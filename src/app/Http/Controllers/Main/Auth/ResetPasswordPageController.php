<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Stevebauman\Purify\Facades\Purify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordValidation;

class ResetPasswordPageController extends Controller
{
    public function index($token){
        return view('pages.main.auth.reset_password')->with('breadcrumb','Reset Password');

    }

    public function requestResetPassword(Request $request, $token) {

        (new RateLimitService($request))->ensureIsNotRateLimited(3);

        $request->validate([
            'email' => ['required','string','email','max:255','exists:App\Models\User,email'],
            'password_confirmation' => 'string|min:8|required_with:password|same:password',
            'password' => ['required',
                'string',
                PasswordValidation::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
            ],
        ]);

        $status = Password::reset(
            [...$request->only('email', 'password', 'password_confirmation'), 'token' => $token],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make(Purify::clean($password))
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET){
            (new RateLimitService($request))->clearRateLimit();
            return redirect(route('signin'))->with('success_status', __($status));
        }
        return back()->with(['error_popup' => __($status)]);

    }
}
