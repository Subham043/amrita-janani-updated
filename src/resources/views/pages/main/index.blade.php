@extends('layouts.main.index')

@section('css')
    <meta name="description" content="Amrita Janani is an online digital knowledge repository containing the teachings of Guruji Sri Amritananda Natha Saraswati of Devipuram, Vizag, India. The teachings are in the form of lectures, practice manuals, guided meditations, etc."/>

    <meta property="og:title" content="Know More About Amrita Janani" />
    <meta property="og:description" content="Amrita Janani is an online digital knowledge repository containing the teachings of Guruji Sri Amritananda Natha Saraswati of Devipuram, Vizag, India. The teachings are in the form of lectures, practice manuals, guided meditations, etc." />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/hero/banner1.jpg') }}" />

    <meta name="twitter:title" content="Know More About Amrita Janani">
    <meta name="twitter:description" content="Amrita Janani is an online digital knowledge repository containing the teachings of Guruji Sri Amritananda Natha Saraswati of Devipuram, Vizag, India. The teachings are in the form of lectures, practice manuals, guided meditations, etc.">
    <meta name="twitter:image" content="{{ Vite::asset('resources/images/hero/banner1.jpg') }}">

    <script type="application/ld+json" nonce="{{ csp_nonce() }}">
        {
          "@context": "https://schema.org/",
          "@type": "WebSite",
          "name": "Amrita Janani",

          "url": "{{request()->url()}}",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{request()->url()}}?s={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
    </script>


    <script type="application/ld+json" nonce="{{ csp_nonce() }}">
        {
           "@context": "https://schema.org",
           "@type": "Organization",
           "name": "Amrita Janani",
           "alternateName": "Amrita Janani",
           "image": [
                "{{ Vite::asset('resources/images/hero/banner4.jpg') }}",
                "{{ Vite::asset('resources/images/hero/banner1.jpg') }}",
                "{{ Vite::asset('resources/images/hero/banner9.jpg') }}"
            ],
           "url": "{{request()->url()}}",
           "logo": "{{ Vite::asset('resources/images/logo/logo.webp') }}",
           "address": {
                "@type": "PostalAddress",
                "streetAddress": "Devipuram Via Nidanam Doddi, Sabbavaram (M), Visakhapatnam District",
                "addressLocality": "Andhra Pradesh",
                "addressRegion": "",
                "postalCode": "531035",
                "addressCountry": "India"
            },
            "telephone": "+919440845333",
            "email": "admin@amrita-janani.org",
           "sameAs": [
             "https://www.facebook.com/srividya.devipuram/",
             "https://twitter.com/devipuram",
             "https://www.instagram.com/Devipuram1/",
             "https://www.youtube.com/devipuram1"
           ]
        }
    </script>

    <script type="application/ld+json" nonce="{{ csp_nonce() }}">
        {
          "@context": "https://schema.org",
          "@type": "VideoObject",
          "name": "Guruji Sri Amritananda Natha Saraswati",
          "description": "Learn more about Guruji Sri Amritananda Natha Saraswati from a part of the documentary on Guruji by renowned documentary filmmaker Raja Choudhury.",
          "thumbnailUrl": "https://i3.ytimg.com/vi/5UWwNpilnz0/maxresdefault.jpg",
          "uploadDate": "2016-01-15",
          "publisher": {
            "@type": "Organization",
            "name": "Amrita Janani",
            "logo": {
              "@type": "ImageObject",
              "url": "{{ Vite::asset('resources/images/logo/logo.webp') }}",
              "width": "",
              "height": ""
            }
          },
          "contentUrl": "https://www.youtube.com/watch?v=5UWwNpilnz0"
        }
    </script>

    <style nonce="{{ csp_nonce() }}">
        .about-tai-content img {
            height: 235px;
            object-fit: contain;
            margin: 0 15px 15px 15px;
        }

        .about-tai-content p {
            font-size: 17px;
            text-align: justify;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .section-space--pb_50 {
            padding-bottom: 50px;
        }

        @media only screen and (max-width: 767px) {
            .float-left {
                float: none !important;
            }

            .float-right {
                float: none !important;
            }

            .about-tai-content img {
                height: auto;
                width: 100%;
                object-fit: contain;
                margin: 0px;
                margin-bottom: 15px;
            }
        }

        .banner-image-backend{
            background:url({{asset('storage/upload/banners/'.$bannerImage->image)}});
        }
        .fs-20{
            font-size: 20px;
        }
    </style>
@stop

@section('content')

    <!-- ======== Hero Area Start ========== -->
    <div class="hero-area hero-style-02 christian-hero-bg-two bg-overlay-black banner-image-backend">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero-content text-center">
                        <h1 class="text-white"><i>{{$bannerQuote->quote}}</i>
                        <br/><span class="fs-20"> - Guruji Amritananda Natha Saraswati</span></h1>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======== Hero Area End ========== -->

    <!-- ======== Church About Area Start ========== -->
    <div class="church-about-area  section-space--pt_120  section-space--pb_50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-tai-content">
                        <img src="{{ Vite::asset('resources/images/hero/banner7.jpg') }}" class="img-fluid float-left"
                            alt="Guruji Sri Amritananda Natha Saraswati" title="Guruji Sri Amritananda Natha Saraswati">
                        <div class="section-title-wrap d-inline">
                            <h3 class="section-title--two  left-style mb-30">What is Amrita Janani?</h3>
                            <p>Amrita Janani is an online digital knowledge repository containing the teachings of Guruji
                                Sri Amritananda Natha Saraswati of Devipuram, Vizag, India. The teachings are in the form of
                                lectures, practice manuals, guided meditations, etc.</p>
                            <div class="text-left">
                                <a rel="nofollow" aria-label="about page" href="{{ route('about') }}" class="submit-btn">Learn More</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="church-about-area">
        <div class="container">
            <div class="row">
                @if ($home->PageContentModel->count() > 0)
                    @foreach ($home->PageContentModel as $item)
                        <div class="col-lg-12 mb-5">
                            <div class="about-tai-content">
                                <div class="section-title-wrap">
                                    <h3 class="section-title--two  left-style mb-30">{{ $item->heading }}</h3>
                                </div>
                                <div>
                                    @if ($item->image)
                                        <img src="{{ asset('storage/upload/pages/' . $item->image) }}"
                                            class="{{ $item->image_position == 1 ? 'img-fluid float-left' : 'img-fluid float-right' }}"
                                            alt="{{$item->heading}}"  title="{{$item->heading}}">
                                    @endif
                                    <div class="section-title-wrap d-inline">
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- ======== Church About Area End ========== -->

    <!-- ======== Hindu Video Area Start ========== -->
    <div class="hindu-video-area  section-space--pb_120">
        <div class="container">
            <div class="hindu-video-bg hindu-video-section-pb bg-overlay-black border-radius-5">
                <div class="row">
                    <div class="col-lg-8 ml-auto mr-auto">
                        <a rel="nofollow" href="https://www.youtube.com/watch?v=5UWwNpilnz0" aria-label="youtube video" class="video-link popup-youtube">
                            <div class="video-content-wrap text-center">
                                <div class="icon">
                                    <img src="{{ Vite::asset('resources/images/icons/play-circle.webp') }}" alt="Video Icon" title="Video Icon">
                                </div>
                                <div class="content section-space--mt_80">
                                    <h3 class="text-white mb-10">Who is Guruji Sri Amritananda Natha Saraswati</h3>
                                    <p class="text-white">Learn more about Guruji Sri Amritananda Natha Saraswati from a
                                        part of the documentary on Guruji by renowned documentary filmmaker Raja Choudhury.
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ========  Hindu Video Area End ========== -->

@stop
