<!--====================  header area ====================-->
<div class="header-area header-area--default">


<!-- Header Bottom Wrap Start -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex align-items-center">
                <div class="header__logo">
                    <div class="logo">
                        <a href="{{route('index')}}" aria-label="home page"><img src="{{ Vite::asset('resources/images/logo/logo.webp') }}" alt="amrita janani logo" title="amrita janani logo"></a>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header__navigation menu-style-three d-none d-lg-block">
                        <nav class="navigation-menu">
                            <ul>
                                <li class="has-children active">
                                    @if(Auth::check())
                                    <a aria-label="content page" href="{{route('content_dashboard')}}"><span>Home</span></a>
                                    @else
                                    <a aria-label="home page" href="{{route('index')}}"><span>Home</span></a>
                                    @endif
                                </li>
                                <li class="has-children">
                                    <a aria-label="about page" href="{{route('about')}}"><span>About</span></a>
                                </li>
                                <li class="has-children">
                                    <a aria-label="faq page" href="{{route('faq')}}"><span>FAQs</span></a>
                                </li>
                                <li class="has-children">
                                    <a aria-label="contact page" href="{{route('contact')}}"><span>Contact</span></a>
                                </li>

                            </ul>
                        </nav>

                    </div>

                    @if(Auth::check())
                    @if(Auth::check() && Auth::user()->darkMode==1)
                    <a aria-label="dark mode" href="{{route('darkmode')}}"><i id="darkModeToggleBtn" class="fas fa-sun"></i></a>
                    @else
                    <a aria-label="light mode" href="{{route('darkmode')}}"><i id="darkModeToggleBtn" class="fas fa-moon"></i></a>
                    @endif
                    @endif

                    <div class="header-btn text-right d-none d-sm-block ml-lg-4">
                        @if(Auth::check())
                        <a aria-label="logout" class="btn-circle btn-default btn" href="{{route('signout')}}">Logout</a>
                        @else
                        <a aria-label="sign in" class="btn-circle btn-default btn" href="{{route('signin')}}">Login</a>
                        @endif
                    </div>

                    <!-- mobile menu -->
                    <div class="mobile-navigation-icon d-block d-lg-none" id="mobile-menu-trigger">
                        <i></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Bottom Wrap End -->

</div>
<!--====================  End of header area  ====================-->
