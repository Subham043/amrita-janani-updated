@extends('layouts.main.auth')

@section('css')
<style nonce="{{ csp_nonce() }}">
    .just-validate-error-label, .invalid-message{
        color: #fff !important;
    }
    .btn-captcha{
        background: #ffcc00;
        color: #000;
        border-radius: 5px;
        padding: 5px 15px;
        border: 1px solid #ddd;
        font-size: 10px;
        cursor: pointer;
    }
</style>

@stop

@section('content')

<div class="form-items">
    <h3>Verify Email.</h3>
    <p>{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
    <div>
        <form action="{{route('verification.send')}}" method="post" id="loginForm">
            @csrf
            </div>

            <div class="form-button">
                <button type="submit" class="ibtn">Resend Verification Email</button>
                <a type="button" href="{{route('signout')}}" class="ibtn">
                    {{ __('Log Out') }}
                </a>
            </div>
        </form>
    </div>
</div>

@stop
