@extends('layouts.main.index')

@section('css')
    <meta name="description" content="Amrita Janani respect the privacy of your personal information and, as such, make every effort to ensure your information is protected and remains private."/>

    <meta property="og:title" content="Privacy Policy – Amrita Janani" />
    <meta property="og:description" content="Amrita Janani respect the privacy of your personal information and, as such, make every effort to ensure your information is protected and remains private." />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/hero/banner4.jpg') }}" />

    <meta name="twitter:title" content="Privacy Policy – Amrita Janani">
    <meta name="twitter:description" content="Amrita Janani respect the privacy of your personal information and, as such, make every effort to ensure your information is protected and remains private.">
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
            "name": "Privacy Policy"
          }]
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
            <div class="col-lg-12 mt-5">
                <div class="mission-wrap mr-lg-5">
                    <div class="section-title-wrap text-left">
                        <h5 class="section-title-normal mb-30">Terms and Conditions:</h5>
                    </div>

                    <div class="target-content">
                        <p>The information on this website is intended purely for private and non-commercial use.</p>
                        <p>Commercial use of any information (full or partial), provided in this website without prior consent of Devipuram Trust® is strictly prohibited.</p>
                        <p>All future communications with you will be made to the email ID provided during the registration.</p>
                        <p>AmritaJanani holds all the rights to revoke/reject access to any user.</p>
                    </div>

                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="mission-wrap mr-lg-5">
                    <div class="section-title-wrap text-left">
                        <h5 class="section-title-normal mb-30">Privacy Policy:</h5>
                    </div>

                    <div class="target-content">
                        <p>We value your privacy.</p>
                        <p>The information shared by you to create an account will only be used by AmritaJanani and Devipuram teams to communicate with you on the relevant topics.</p>
                        <p>The information provided by you will not be shared with any third-party individual or entity for any commercial or non-commercial purposes.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!--=========== Causes Details Area End ==========-->




@stop
