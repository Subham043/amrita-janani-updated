<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //custom link for reset password
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return URL::temporarySignedRoute($user->userType==1 ? 'reset_password' : 'resetPassword', now()->addMinutes(60), ['token' => $token]);
        });
    }
}
