<!--========== Footer Area Start ==========-->
<footer class="footer-area bg-footer">
        <div class="footer-top section-space--ptb_80 section-pb text-white">
            <div class="container">
                <div class="row">
                <div class="col-lg-4 col-md-6">
                        <div class="widget-footer mt-30">
                            <div class="footer-title">
                                <h6>Related Links</h6>
                            </div>
                            <div class="footer-logo mb-15">
                                <a rel="nofollow" href="https://devipuram.org/" aria-label="devipuram" target="_blank"><img class="height-70" src="{{ Vite::asset('resources/images/logo/devipuram-logo.webp') }}" alt="devipuram logo" title="devipuram logo"></a>
                            </div>
                            <div class="footer-logo mb-15">
                                <a rel="nofollow" href="https://forum.amritananda.org/" aria-label="forum" target="_blank"><img class="height-80" src="{{ Vite::asset('resources/images/logo/footer-logo.webp') }}" alt="forum logo" title="forum logo"></a>
                            </div>
                            <div class="footer-contents">

                                <ul class="footer-social-share mt-20">
                                    <li><a rel="nofollow" href="https://www.facebook.com/srividya.devipuram/" aria-label="facebook" target="_blank"><i class="flaticon-facebook"></i></a></li>
                                    <li><a rel="nofollow" href="https://twitter.com/devipuram" aria-label="twitter" target="_blank"><i class="flaticon-twitter"></i></a></li>
                                    <li><a rel="nofollow" href="https://www.instagram.com/Devipuram1/" aria-label="instagram" target="_blank"><i class="flaticon-instagram"></i></a></li>
                                    <li><a rel="nofollow" href="https://www.youtube.com/devipuram1" aria-label="youtube" target="_blank"><i class="flaticon-youtube"></i></a></li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="widget-footer mt-30">
                            <div class="footer-title">
                                <h6>Sitemap</h6>
                            </div>
                            <div class="footer-contents">
                                <ul>
                                    <li><a aria-label="home page" rel="nofollow" href="{{route('index')}}">Home</a></li>
                                    <li><a aria-label="about page" rel="nofollow" href="{{route('about')}}">About</a></li>
                                    <li><a aria-label="faq page" rel="nofollow" href="{{route('faq')}}">FAQs</a></li>
                                    <li><a aria-label="contact page" rel="nofollow" href="{{route('contact')}}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="widget-footer mt-30">
                            <div class="footer-title">
                                <h6>Address</h6>
                            </div>
                            <div class="footer-contents">
                                <ul>
                                    <li><b>Devipuram</b><br/>Via Nidanam Doddi, Sabbavaram (M),<br/>
                                Visakhapatnam District,<br/>
                                Andhra Pradesh - 531035<br/>
                                India<br/>
                                <a rel="nofollow" aria-label="email" href="mailto:admin@amrita-janani.org">Email: admin@amrita-janani.org</a><br/>
                                <a rel="nofollow" aria-label="phone" href="tel:+919440845333">Phone: +91 94408 45333</a>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copy-right-box">
                            <p class="text-white">Copyright &copy; {{date('Y')}} Amrita Janani and Devipuram All Right Reserved.</p>
                            <p class=" text-white"><a aria-label="privacy page" href="{{route('privacy_policy')}}">Privacy policy</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--==========// Footer Area End ==========-->





    <!--====================  scroll top ====================-->
    <a href="#" aria-label="scroll to top" rel="nofollow" class="scroll-top" id="scroll-top">
        <i class="arrow-top flaticon-up-arrow"></i>
        <i class="arrow-bottom flaticon-up-arrow"></i>
    </a>
    <!--====================  End of scroll top  ====================-->
