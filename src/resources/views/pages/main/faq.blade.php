@extends('layouts.main.index')

@section('css')
    <meta name="description" content="Welcome to frequently ask (FAQ) page. Here you found questions and answers most of the followers asked about Amrita Janani"/>

    <meta property="og:title" content="FAQ – Amrita Janani" />
    <meta property="og:description" content="Welcome to frequently ask (FAQ) page. Here you found questions and answers most of the followers asked about Amrita Janani" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/hero/banner4.jpg') }}" />

    <meta name="twitter:title" content="FAQ – Amrita Janani">
    <meta name="twitter:description" content="Welcome to frequently ask (FAQ) page. Here you found questions and answers most of the followers asked about Amrita Janani">
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
            "name": "FAQ"
          }]
        }
    </script>

    <script type="application/ld+json" nonce="{{ csp_nonce() }}">
        {
          "@context": "https://schema.org",
          "@type": "FAQPage",
          "mainEntity": [
            @foreach ($faq as $key=>$value)
            {
                "@type": "Question",
                "name": "{{$value->question}}",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "<p>{{$value->answer}}</p>"
                }
            }{{$key+1==count($faq) ? '' : ','}}
            @endforeach
          ]
        }
    </script>

<style nonce="{{ csp_nonce() }}">
h5.section-title-normal{
    color:#96171c;
}
</style>
@stop

@section('content')

@include('includes.main.breadcrumb')

<!--=========== Causes Details Area Start ==========-->
<div class="causes-details-area section-space--pb_120 section-space--pt_70">
    <div class="container">
        <div class="row">
            @foreach ($faq as $key=>$value)
            <div class="col-lg-12 mt-5">
                <div class="mission-wrap mr-lg-5">
                    <div class="section-title-wrap text-left">
                        <h5 class="section-title-normal mb-30">{{$key+1}}. {{$value->question}}</h5>
                    </div>

                    <div class="target-content">
                        <p>{{$value->answer}}</p>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
<!--=========== Causes Details Area End ==========-->




@stop
