@extends('layouts.main.index')

@section('content')

    <!-- 404 Section  -->

    <section class="church-about-area section-space--ptb_120">
        <div class="container">
            <div class="row justify-content-center error-404">
                <div class="col-lg-12 text-center">
                    <h1><strong>{{$status_code}}</strong></h1>
                    <h5 class="text mt-3">{{$message}}!</h5>
                    <div class="link-btn mt-3">
                        <a href="{{route('contact')}}" class="submit-btn"><span>Get In Touch</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- @include('main.includes.common_contact') --}}
@stop
