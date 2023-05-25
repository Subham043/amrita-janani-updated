@extends('layouts.main.auth')

@section('css')

    <meta name="description" content="Sign in with Amrita Janani"/>

    <meta property="og:title" content="Sign In - Amrita Janani" />
    <meta property="og:description" content="Sign in with Amrita Janani" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/hero/banner4.jpg') }}" />

    <meta name="twitter:title" content="Sign In - Amrita Janani">
    <meta name="twitter:description" content="Sign in with Amrita Janani">
    <meta name="twitter:image" content="{{ Vite::asset('resources/images/hero/banner4.jpg') }}">

    <script type="application/ld+json" nonce="{{ csp_nonce() }}">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "Amrita Janani",
            "item": "{{url()->to('/')}}"
          },{
            "@type": "ListItem",
            "position": 2,
            "name": "Sign In"
          }]
        }
    </script>

<style nonce="{{ csp_nonce() }}">
.just-validate-error-label,
.invalid-message {
    color: #fff !important;
}

.btn-captcha {
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
    <!-- <p>Access to the most powerfull tool in amrita janani.</p> -->
    <div class="page-links">
        <a aria-label="ssign in" href="{{route('signin')}}" class="active">Login</a><a aria-label="sign up" href="{{route('signup')}}">Register</a>
    </div>
    <h3>Get access to Amrita Janani by logging in</h3><br/>
    <form action="{{route('signin_authenticate')}}" method="post" id="loginForm">
        @csrf
        <div class="mb-2">
            <input class="form-control" type="email" name="email" id="email" placeholder="E-mail Address*"
                value="{{old('email')}}" required>
            @error('email')
            <div class="invalid-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-2">
            <input class="form-control" type="password" name="password" id="password" placeholder="Password*" required>
            @error('password')
            <div class="invalid-message">{{ $message }}</div>
            @enderror
        </div>

        <input type="checkbox" id="chk1"><label for="chk1">Remember me</label>
        <div class="form-button">
            <button id="submitBtn" type="submit" class="ibtn">Login</button> <a
                href="{{route('forgot_password')}}">Forgot password?</a>
        </div>
    </form>
    <!-- <div class="other-links">
        <span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
    </div> -->
</div>





@stop

@section('javascript')
<script src="{{ asset('main/js/plugins/axios.min.js') }}"></script>
<script type="text/javascript" nonce="{{ csp_nonce() }}">
// initialize the validation library
const validation = new JustValidate('#loginForm', {
    errorFieldCssClass: 'is-invalid',
    focusInvalidField: true,
    lockForm: true,
});
// apply rules to form fields
validation
    .addField('#email', [{
            rule: 'required',
            errorMessage: 'Email is required',
        },
        {
            rule: 'email',
            errorMessage: 'Email is invalid!',
        },
    ])
    .addField('#password', [{
        rule: 'required',
        errorMessage: 'Password is required',
    }])
    //   .showErrors(errors:{ '#email': 'The email is invalid' })
    .onSuccess((event) => {
        // event.target.showErrors({ '#email': 'The email is invalid' })
        event.target.submit();
    });
</script>

@stop
