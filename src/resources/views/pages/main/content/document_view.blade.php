@extends('layouts.main.index')

@section('css')
<link href="{{asset('main/dflip/css/dflip.min.css')}}" rel="stylesheet" type="text/css">
<!-- themify-icons.min.css is not required in version 2.0 and above -->
<link href="{{asset('main/dflip/css/themify-icons.min.css')}}" rel="stylesheet" type="text/css">
@stop

@section('content')

@include('includes.main.sub_menu')


<div class="main-content-wrapper">
    @if($document->contentVisible())
        <div class="main-image-container" id="image-container"
            >

            <div id="flipbookPDFContainer"></div>

        </div>
    @else
        @include('pages.main.content.common.denied_img', ['text'=>'document'])
    @endif
    <hr />
    <div class="container">
        <div class="row action-button-row">
            <div class="col-sm-12">
                <div class="info-content">
                    <h5>{{$document->title}}</h5>
                </div>
            </div>
            <div class="col-sm-auto">
                <div class="info-content">
                    {{-- <p><span id="view_count">{{$document->views}} views</span> <span
                            id="favourite_count">{{$document->favourites}} favourites</span></p> --}}
                    <p><span id="view_count">{{$document->views}} views</span> </p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12 action-button-wrapper">
                <a href="{{route('content_document_makeFavourite',$document->uuid)}}"
                    class="action-btn make-favourite-button">
                    @if($document->markedFavorite())
                    <i class="fas fa-heart-broken"></i>
                    @else
                    <i class="far fa-heart"></i>
                    @endif
                </a>
                <button class="action-btn report-button" data-toggle="modal" data-target="#reportModal"><i
                        class="far fa-flag"></i> </button>
            </div>
        </div>
    </div>
    @if($document->description_unformatted)
    <hr />
    <div class="container info-container info-major-content">
        <h6>Description</h6>
        {!!$document->description!!}
    </div>
    @endif
    <hr />
    <div class="container info-container">
        @if($document->deity)<p>Deity : <b>{{$document->deity}}</b></p>@endif
        @if($document->languages->count()>0)
        <p>Language :
        @foreach ($document->languages as $languages)
            <b>{{$languages->name}}</b>,
        @endforeach
        </p>
        @endif
        <p>Number of Pages : <b>{{$document->page_number}}</b></p>
        <p>Uploaded : <b>{{$document->time_elapsed()}}</b></p>
        @if(count($document->getTagsArray())>0)
        <p>Tags :
        @foreach($document->getTagsArray() as $tag)
        <span class="hashtags">#{{$tag}}</span>
        @endforeach
        </p>
        @endif
    </div>


    @include('pages.main.content.common.request_access_modal')

    @include('pages.main.content.common.report_modal', ['text'=>'document'])

</div>

@stop

@section('javascript')
<script src="{{ asset('main/js/plugins/just-validate.production.min.js') }}"></script>
<script src="{{ asset('main/js/plugins/axios.min.js') }}"></script>

@include('pages.main.content.common.search_js', ['search_url'=>route('content_search_query')])

<script nonce="{{ csp_nonce() }}">
$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>

@include('pages.main.content.common.request_access_form_js', ['url'=>route('content_document_requestAccess', $document->uuid)])
@include('pages.main.content.common.report_form_js', ['url'=>route('content_document_report', $document->uuid)])


@include('pages.main.content.common.reload_captcha_js')

@if($document->contentVisible())

<script src="{{asset('main/dflip/js/libs/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('main/dflip/js/dflip.min.js')}}" type="text/javascript"></script>
<script nonce="{{ csp_nonce() }}">
    jQuery(document).ready(function () {

        //FOR PDFs
        var source_pdf = "{{asset('storage/upload/documents/'.$document->document)}}";
        var option_pdf = {
            webglShadow: true,

            // if enable sound at start (true|false)
            soundEnable: false,

            // height of the container
            // value(eg: 320) or percentage (eg: '50%')
            // calculaton limit: minimum 320, max window height
            height: window.innerHeight - 200,

            // set to true to show outline on open (true|false)
            autoEnableOutline: false,

            // set to true to show thumbnail on open (true|false)
            autoEnableThumbnail: false,

            // set to true if PDF inbuilt outline is to be removed (true|false)
            overwritePDFOutline: false,

            // enableDownload of PDF files (true|false)
            enableDownload: false,

            // duration of page turn in milliseconds
            duration: 800,

            //direction of flipbook
            //DFLIP.DIRECTION.LTR or 1	for left to right(default),
            //DFLIP.DIRECTION.RTL or 2	for right to left,
            direction: DFLIP.DIRECTION.LTR,

            //set as
            //DFLIP.PAGE_MODE.AUTO	 				for auto-detect(default),
            //DFLIP.PAGE_MODE.SINGLE or 1 			for singleview,
            //DFLIP.PAGE_MODE.DOUBLE or 2 			for doubleview,
            pageMode: DFLIP.PAGE_MODE.AUTO,

            //set as
            //DFLIP.SINGLE_PAGE_MODE.AUTO	 				for auto-detect(default),
            //DFLIP.SINGLE_PAGE_MODE.ZOOM or 1 				for normal zoom single view,
            //DFLIP.SINGLE_PAGE_MODE.BOOKLET or 2 			for Booklet mode,
            singlePageMode: DFLIP.SINGLE_PAGE_MODE.AUTO,

            //color value in hexadecimal
            backgroundColor: "#fff",

            forceFit: true, //very rare usage leave it as true unless page are not fitting wrong...
            transparent: true, //true or false
            hard: "none", //possible values are "all", "none", "cover"


            annotationClass: "",

            autoPlay: false,
            autoPlayDuration: 5000,
            autoPlayStart: false,

            // texture settings
            maxTextureSize: 1600,	//max page size to be rendered. for pdf files only
            minTextureSize: 256,	//min page size to be rendered. for pdf files only
            rangeChunkSize: 524288,

            // icons for the buttons
            icons: {
                'altnext': 'ti-angle-right',
                'altprev': 'ti-angle-left',
                'next': 'ti-angle-right',
                'prev': 'ti-angle-left',
                'end': 'ti-angle-double-right',
                'start': 'ti-angle-double-left',
                'share': 'ti-sharethis',
                'help': 'ti-help-alt',
                'more': 'ti-more-alt',
                'download': 'ti-download',
                'zoomin': 'ti-zoom-in',
                'zoomout': 'ti-zoom-out',
                'fullscreen': 'ti-fullscreen',
                'fitscreen': 'ti-arrows-corner',
                'thumbnail': 'ti-layout-grid2',
                'outline': 'ti-menu-alt',
                'close': 'ti-close',
                'doublepage': 'ti-book',
                'singlepage': 'ti-file',
                'sound': 'ti-volume',
                'facebook': 'ti-facebook',
                'google': 'ti-google',
                'twitter': 'ti-twitter-alt',
                'mail': 'ti-email',
                'play': 'ti-control-play',
                'pause': 'ti-control-pause'
            },

            // TRANSLATION text to be displayed
            text: {

                toggleSound: "Turn on/off Sound",
                toggleThumbnails: "Toggle Thumbnails",
                toggleOutline: "Toggle Outline/Bookmark",
                previousPage: "Previous Page",
                nextPage: "Next Page",
                toggleFullscreen: "Toggle Fullscreen",
                zoomIn: "Zoom In",
                zoomOut: "Zoom Out",
                toggleHelp: "Toggle Help",

                singlePageMode: "Single Page Mode",
                doublePageMode: "Double Page Mode",
                downloadPDFFile: "Download PDF File",
                gotoFirstPage: "Goto First Page",
                gotoLastPage: "Goto Last Page",
                play: "Start AutoPlay",
                pause: "Pause AutoPlay",

                share: "Share"
            },

            //valid controlnames:
            //altPrev,pageNumber,altNext,outline,thumbnail,zoomIn,zoomOut,fullScreen,share
            //more,download,pageMode,startPage,endPage,sound
            allControls: "altPrev,pageNumber,altNext,play,outline,thumbnail,zoomIn,zoomOut,fullScreen,more,pageMode,startPage,endPage",
            moreControls: "pageMode,startPage,endPage",
            hideControls: "",

            controlsPosition: DFLIP.CONTROLSPOSITION.BOTTOM,
            paddingTop: 30,
            paddingLeft: 50,
            paddingRight: 50,
            paddingBottom: 30,

            //set if the zoom changes on mouse scroll (true|false)
            scrollWheel: false,

            // callbacks
            onCreate: function (flipBook) {
            // after flip book is created is fired
            },
            onCreateUI: function (flipBook) {
            // after ui created event is fired
            },
            onFlip: function (flipBook) {
            // after flip event is fired
            },
            beforeFlip: function (flipBook) {
            // before flip event is fired
            },
            onReady: function (flipBook) {
            // after flip book is completely loaded
            },

            zoomRatio: 1.5,
            pageSize: DFLIP.PAGE_SIZE.AUTO,


            //(NON-OPTION) developer parameters
            enableDebugLog: false,
            canvasToBlob: false,//as of 1.2.9 canvas are better optimized and secure
            enableAnnotation: true,
            pdfRenderQuality: 0.90,

            pageRatio: null, 		//equals to width/height

            pixelRatio: window.devicePixelRatio || 1,
            thumbElement: 'div',

            /*3D settings*/
            spotLightIntensity: 0.22,
            ambientLightColor: "#fff",
            ambientLightIntensity: 0.8,
            shadowOpacity: 0.15
        };

        var flipBook_pdf = $("#flipbookPDFContainer").flipBook(source_pdf,option_pdf);
    });
</script>

@endif

@include('pages.main.content.common.dashboard_search_handler', ['search_url'=>route('content_dashboard')])


@stop
