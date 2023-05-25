@extends('layouts.main.index')

@section('css')

    <meta name="description" content="Dr. N. Prahlada Sastry (Sri Amritananda Natha Saraswati) was born in Vishakapatnam (in Andhra Pradesh) to Sri Narasimha Rao and Smt. Lakshminarasamma. His disciples affectionately refer to him as Guruji. Guruji started his spiritual quest at a very young age when he was blessed with many divine experiences early in his childhood; His young mind was teeming with questions in search of the truth. The stage was set at a very tender age for his spiritual exploration that was to intensify later in his life."/>

    <meta property="og:title" content="Know More About Guruji Sri Amritananda Natha Saraswati" />
    <meta property="og:description" content="Dr. N. Prahlada Sastry (Sri Amritananda Natha Saraswati) was born in Vishakapatnam (in Andhra Pradesh) to Sri Narasimha Rao and Smt. Lakshminarasamma. His disciples affectionately refer to him as Guruji. Guruji started his spiritual quest at a very young age when he was blessed with many divine experiences early in his childhood; His young mind was teeming with questions in search of the truth. The stage was set at a very tender age for his spiritual exploration that was to intensify later in his life." />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/hero/banner1.jpg') }}" />

    <meta name="twitter:title" content="Know More About Guruji Sri Amritananda Natha Saraswati">
    <meta name="twitter:description" content="Dr. N. Prahlada Sastry (Sri Amritananda Natha Saraswati) was born in Vishakapatnam (in Andhra Pradesh) to Sri Narasimha Rao and Smt. Lakshminarasamma. His disciples affectionately refer to him as Guruji. Guruji started his spiritual quest at a very young age when he was blessed with many divine experiences early in his childhood; His young mind was teeming with questions in search of the truth. The stage was set at a very tender age for his spiritual exploration that was to intensify later in his life.">
    <meta name="twitter:image" content="{{ Vite::asset('resources/images/hero/banner1.jpg') }}">

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
            "name": "About Us"
          }]
        }
    </script>

<style nonce="{{ csp_nonce() }}">
.about-tai-content img{
    width: 50%;
    object-fit: contain;
    margin: 0 15px 15px 15px;
}
.about-tai-content p{
    font-size: 17px;
    text-align: justify;
}

.float-left{ float: left; }
.float-right{ float: right; }

@media only screen and (max-width: 767px) {
    .float-left{ float: none !important; }
    .float-right{ float: none !important; }
    .about-tai-content img{
        width: 100%;
        object-fit: contain;
        margin: 0px;
        margin-bottom: 15px;
    }
}
</style>
@stop

@section('content')

@include('includes.main.breadcrumb')

<!-- ======== Church About Area Start ========== -->
<div class="church-about-area section-space--ptb_120">
    <div class="container">
        <div class="row ">
            @if($about->PageContentModel->count()>0)
            @foreach ($about->PageContentModel as $item)
            <div class="col-lg-12 mb-5">
                <div class="about-tai-content">
                    <div class="section-title-wrap">
                        <h3 class="section-title--two  left-style mb-30">{{$item->heading}}</h3>
                    </div>
                    <div>
                        @if($item->image)
                        <img src="{{asset('storage/upload/pages/'.$item->image)}}"  alt="{{$item->heading}}"  title="{{$item->heading}}" class="{{$item->image_position==1?'img-fluid float-left':'img-fluid float-right'}}">
                        @endif
                        <div class="section-title-wrap d-inline">
                            {!!$item->description!!}
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

@stop
