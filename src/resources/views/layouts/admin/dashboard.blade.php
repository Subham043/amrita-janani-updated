<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover">

<head>

        <meta charset="utf-8" />
        <title>Amrita Janani- Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Amrita Janani" name="description" />
        <meta content="Amrita Janani" name="author" />
        <!-- App favicon -->
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

        @cspMetaTag(App\Http\Policies\ContentSecurityPolicy::class)
        <!-- Layout config Js -->
        <script src="{{ asset('admin/js/layout.js') }}"></script>
        <!-- Bootstrap Css -->
        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/iziToast.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        @yield('css')

        <style nonce="{{ csp_nonce() }}">
            .invalid-message{
                color:red;
            }
            .pointer-events-none{
                pointer-events: none;
            }
        </style>


    </head>

    <body>

        <!-- Begin page -->
        <div id="layout-wrapper">

            @include('includes.admin.header')

            @include('includes.admin.menu')

             <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                @yield('content')

                @include('includes.admin.footer')

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->



        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('admin/js/pages/just-validate.production.min.js') }}"></script>
        <script src="{{ asset('admin/js/pages/iziToast.min.js') }}"></script>
        <script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
        {{-- <script src="{{ asset('admin/js/plugins.js') }}"></script> --}}




        <!-- App js -->
        {{-- <script src="{{ asset('admin/js/app.js') }}"></script> --}}
        <script src="{{ asset('admin/js/main.js') }}"></script>
        <script type="text/javascript" nonce="{{ csp_nonce() }}">
            const errorToast = (message) =>{
                iziToast.error({
                    title: 'Error',
                    message: message,
                    position: 'topRight',
                    timeout:0
                });
            }
            const successToast = (message) =>{
                iziToast.success({
                    title: 'Success',
                    message: message,
                    position: 'topRight',
                    timeout:0
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
