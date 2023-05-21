<?php

namespace App\Services;

use App\Exceptions\CustomExceptions\RateLimitException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RateLimitService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Ensure the request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(int $number_of_request = 60): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $number_of_request)) {
            RateLimiter::hit($this->throttleKey());
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $message = 'Too many attempts! You may try again in ';
        if($seconds > 60){
            $message .= ceil($seconds / 60).' minutes.';
        }else{
            $message .= $seconds.' seconds.';
        }

        throw new RateLimitException(
            $message,
            429
        );

    }

    /**
     * Ensure the limit is cleared on successful attempt.
     *
     */
    public function clearRateLimit(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        if(Auth::check()){
            return Str::transliterate(Str::lower($this->request->user()->email).'|'.$this->request->user()->id.'|'.$this->request->ip());
        }else{
            return Str::transliterate(
                $this->request->email ? Str::lower($this->request->email) : ''.'|'.$this->request->ip()
            );
        }
    }

}
