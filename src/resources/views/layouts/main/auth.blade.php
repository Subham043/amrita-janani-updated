<!DOCTYPE html>
<html class="no-js" lang="zxx">


<head>
    <title>Amrita Janani - {{$breadcrumb}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="{{request()->getRequestUri()}}" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ Vite::asset('resources/images/fav/apple-icon-144x144.png')}}">
    <meta name="theme-color" content="#96171c">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta property="og:url" content="{{request()->getRequestUri()}}" />
    <meta property="og:site_name" content="Amrita Janani" />
    <meta name="twitter:site" content="@devipuram">
    <meta name="twitter:creator" content="@devipuram">
    <meta name="keywords" content="online digital knowledge repository, Guruji Sri Amritananda Natha Saraswati, Devipuram, Amrita Janani, digital repository, Dr. N. Prahlada Sastry, Sri Amritananda Natha Saraswati" >
    <!-- Favicon -->

    <link rel="apple-touch-icon" sizes="57x57" href="{{ Vite::asset('resources/images/fav/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ Vite::asset('resources/images/fav/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ Vite::asset('resources/images/fav/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ Vite::asset('resources/images/fav/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ Vite::asset('resources/images/fav/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ Vite::asset('resources/images/fav/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ Vite::asset('resources/images/fav/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ Vite::asset('resources/images/fav/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/images/fav/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ Vite::asset('resources/images/fav/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/images/fav/favicon-16x16.png')}}">

    @cspMetaTag(App\Http\Policies\ContentSecurityPolicy::class)
    <!-- CSS
        ============================================ -->

    @yield('css')

    @vite(['resources/css/auth/auth.css'])

    <style nonce="{{ csp_nonce() }}">
        .bg-backend{
            background-size: cover;
            background-position: left bottom;
            background-image: linear-gradient(45deg, #000000bd, #000000ba), url({{Vite::asset('resources/images/hero/banner3.jpg')}});
        }
    </style>

</head>

<body>

    <div class="form-body">
        <div class="website-logo">
            <a aria-label="home page" href="{{route('index')}}">
                <div class="logo">
                    <img alt="amrita janani logo" title="amrita janani logo" class="" src="{{ Vite::asset('resources/images/logo/logo.webp') }}" >
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg bg-backend"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('auth/js/popper.min.js') }}"></script>
    <script src="{{ asset('auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('main/js/plugins/iziToast.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/just-validate.production.min.js') }}"></script>

    <script type="text/javascript" nonce="{{ csp_nonce() }}">

        const errorToast = (message) =>{
            iziToast.error({
                title: 'Error',
                message: message,
                position: 'topRight',
                timeout:7000
            });
        }
        const successToast = (message) =>{
            iziToast.success({
                title: 'Success',
                message: message,
                position: 'topRight',
                timeout:6000
            });
        }
        @if (session('success_status'))
            successToast('{{ Session::get('success_status') }}')
        @endif
        @if (session('error_status'))
            errorToast('{{ Session::get('error_status') }}')
        @endif

    </script>

    @yield('javascript')

    <script type="text/javascript" nonce="{{ csp_nonce() }}">!function(t,e){t.artibotApi={l:[],t:[],on:function(){this.l.push(arguments)},trigger:function(){this.t.push(arguments)}};var a=!1,i=e.createElement("script");i.async=!0,i.type="text/javascript",i.src="https://app.artibot.ai/loader.js",e.getElementsByTagName("head").item(0).appendChild(i),i.onreadystatechange=i.onload=function(){if(!(a||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState)){new window.ArtiBot({i:"6c3037f1-249f-4e96-9ab8-630ae8bad965"});a=!0}}}(window,document);</script>


</body>


</html>
