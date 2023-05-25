<!--====================  mobile menu overlay ====================-->
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
        <div class="mobile-menu-overlay__inner">
            <div class="mobile-menu-overlay__header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-8">
                            <!-- logo -->
                            <div class="logo">
                                <a aria-label="home page" href="{{route('index')}}">
                                    <img src="{{ Vite::asset('resources/images/logo/logo.webp') }}" class="img-fluid"  alt="amrita janani logo"  title="amrita janani logo">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-4">
                            <!-- mobile menu content -->
                            <div class="mobile-menu-content text-right">
                                <span class="mobile-navigation-close-icon" id="mobile-menu-close-trigger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile-menu-overlay__body">
                <nav class="offcanvas-navigation">
                    <ul>
                        <li class="has-children">
                            <a aria-label="home page" href="{{route('index')}}">Home</a>
                        </li>
                        <li class="has-children">
                            <a aria-label="about page" href="{{route('about')}}">About</a>
                        </li>
                        <li class="has-children">
                            <a aria-label="faq page" href="{{route('faq')}}">FAQs</a>
                        </li>
                        <li class="has-children">
                            <a aria-label="contact page" href="{{route('contact')}}">Contact</a>
                        </li>
                        @if(Auth::check())
                        <li class="has-children">
                            <a href="#">Content</a>
                            <ul class="sub-menu">
                                <li><a aria-label="content dashboard" href="{{route('content_dashboard')}}"><span>Dashboard</span></a></li>
                                <li><a aria-label="content image" href="{{route('content_image')}}"><span>Images</span></a></li>
                                <li><a aria-label="content video" href="{{route('content_video')}}"><span>Videos</span></a></li>
                                <li><a aria-label="content audio" href="{{route('content_audio')}}"><span>Audio</span></a></li>
                                <li><a aria-label="content document" href="{{route('content_document')}}"><span>Documents</span></a></li>
                            </ul>
                        </li>
                        <li class="has-children">
                            <a href="#">Account</a>
                            <ul class="sub-menu">
                                <li><a aria-label="profile" href="{{route('userprofile')}}"><span>User Profile</span></a></li>
                                <li><a aria-label="change password" href="{{route('display_profile_password')}}"><span>Change Password</span></a></li>
                                <li><a aria-label="search history" href="{{route('search_history')}}"><span>Search History</span></a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="has-children">
                            @if(Auth::check())
                            <a aria-label="logout" href="{{route('signout')}}">Logout</a>
                            @else
                            <a aria-label="sign in" href="{{route('signin')}}">Login</a>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!--====================  End of mobile menu overlay  ====================-->
