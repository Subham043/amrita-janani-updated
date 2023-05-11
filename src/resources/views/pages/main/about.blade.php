@extends('layouts.main.index')

@section('css')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
        integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
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
                        <img src="{{asset('storage/upload/pages/'.$item->image)}}" class="{{$item->image_position==1?'img-fluid float-left':'img-fluid float-right'}}" alt="About Images">
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
