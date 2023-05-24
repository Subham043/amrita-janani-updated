<!DOCTYPE html>
<html class="no-js" lang="zxx">


<head>
    <title>Amrita Janani - {{$breadcrumb}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="{{request()->url()}}" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ Vite::asset('resources/images/fav/apple-icon-144x144.png')}}">
    <meta name="theme-color" content="#96171c">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta property="og:url" content="{{request()->url()}}" />
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
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ Vite::asset('resources/images/fav/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/images/fav/favicon-16x16.png')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">

    @cspMetaTag(App\Http\Policies\ContentSecurityPolicy::class)
    <!-- CSS
        ============================================ -->

    @yield('css')

    @vite(['resources/css/main/app.css'])

    <style nonce="{{ csp_nonce() }}">
        .height-70{
            height: 70px;
        }
        .height-80{
            height: 80px;
        }
    </style>

    @if(Auth::check() && Auth::user()->darkMode==1)
        @vite(['resources/css/main/dark.css'])
    @endif

</head>

<body>





    @include('includes.main.header')



    <div class="site-wrapper-reveal">
        @yield('content')
    </div>


    @include('includes.main.footer')




    @include('includes.main.mobile_header')


    <!-- JS
    ============================================ -->

    <!-- Modernizer JS -->
    {{-- <script src="{{ asset('main/js/vendor/modernizr-2.8.3.min.js') }}"></script> --}}

    <!-- jquery JS -->
    <script src="{{ asset('main/js/vendor/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('main/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('main/js/vendor/bootstrap.min.js') }}"></script>

    <!-- Swiper Slider JS -->
    <script src="{{ asset('main/js/plugins/swiper.min.js') }}"></script>

    <!-- Waypoints JS -->
    <script src="{{ asset('main/js/plugins/waypoints.min.js') }}"></script>

    <!-- Counterup JS -->
    <script src="{{ asset('main/js/plugins/counterup.min.js') }}"></script>

    <!-- Magnific Popup JS -->
    <script src="{{ asset('main/js/plugins/jquery.magnific-popup.min.js') }}"></script>

    <!-- wow JS -->
    <script src="{{ asset('main/js/plugins/wow.min.js') }}"></script>

    <script src="{{ asset('main/js/plugins/iziToast.min.js') }}"></script>

    <!-- Plugins JS (Please remove the comment from below plugins.min.js for better website load performance and remove plugin js files from avobe) -->


    <!-- <script src="{{ asset('main/js/plugins/plugins.min.js') }}"></script> -->


    <!-- Main JS -->
    <script src="{{ asset('main/js/main.js') }}"></script>

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



</body>


</html>
