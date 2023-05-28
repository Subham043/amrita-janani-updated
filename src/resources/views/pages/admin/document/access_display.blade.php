@extends('layouts.admin.dashboard')


@section('css')
<link href="{{asset('main/dflip/css/dflip.min.css')}}" rel="stylesheet" type="text/css">
<!-- themify-icons.min.css is not required in version 2.0 and above -->
<link href="{{asset('main/dflip/css/themify-icons.min.css')}}" rel="stylesheet" type="text/css">
<style nonce="{{ csp_nonce() }}">
    #canvas_container {
        width: 100%;
        height: 550px;
        overflow: auto;
        position: relative;
    }

    #canvas_container {
    background: #333;
    text-align: center;
    border: solid 3px;
    }

    #pdf_controllers{
        width: 100%;
        background: #222;
        display:flex;
        justify-content: space-between;
        align-items: center;
        padding:10px 15px;
    }

    #pdf_controllers button{
        display:grid;
        place-items: center;
        outline: none;
        border: none;
        background:#96171c;
        color:white;
        border-radius:5px;
        padding:5px 10px;
    }

    #navigation_controls, #zoom_controls{
        display:flex;
        align-items:center;
    }
    #navigation_controls button, #zoom_controls button{
        margin:0 10px;
        min-width:30px;
        height:35px;
    }
    #navigation_controls input{
        max-width:30px;
        height:100%;
        margin:0;
        padding:0;
        text-align:center;
        outline:none;
        border:none;
        width: 30px;
        cursor: pointer;
    }
    #navigation_controls label{
        display:flex;
        align-items:center;
        margin:0;
        padding:0;
        height: 40px;
        width: 80px;
        background-color:white;
        outline:none;
        border:1px solid #eee;
        padding:5px;
        position:relative;
        text-align: center;
        color:black;
        border-radius: 5px;
    }
    #totalPageCount{
        /* color:white; */
        margin-left:10px
    }
</style>
@stop


@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('includes.admin.page_title', [
            'page_name' => "Access Request",
            'current_page' => "View",
        ])

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                                <div>
                                    <a href="{{url()->previous()}}" type="button" class="btn btn-dark add-btn" id="create-btn"><i class="ri-arrow-go-back-line align-bottom me-1"></i> Go Back</a>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    @if($country->User->userType == 2)
                                    <a href="{{route('subadmin_makeUserPreviledge', $country->User->id)}}" type="button" class="btn btn-warning add-btn me-2" id="create-btn"> Grant Access To All Files</a>
                                    @elseif($country->User->userType == 3)
                                    <a href="{{route('subadmin_makeUserPreviledge', $country->User->id)}}" type="button" class="btn btn-warning add-btn me-2" id="create-btn"> Revoke Access To All Files</a>
                                    @endif
                                    @if($country->User->userType == 2)
                                    @if($country->status == 1)
                                    <a href="{{route('document_toggle_access', $country->id)}}" type="button" class="btn btn-success add-btn me-2" id="create-btn"> Revoke Access</a>
                                    @else
                                    <a href="{{route('document_toggle_access', $country->id)}}" type="button" class="btn btn-success add-btn me-2" id="create-btn"> Grant Access</a>
                                    @endif
                                    @endif
                                    <button type="button" class="btn btn-danger add-btn remove-item-btn" data-link="{{route('document_delete_access', $country->id)}}" id="create-btn"><i class="ri-delete-bin-line align-bottom me-1 pointer-events-none"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted">
                            <div class="pt-3 pb-3 border-top border-top-dashed border-bottom border-bottom-dashed mt-4">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Document Title :</p>
                                            <h5 class="fs-15 mb-0">{{$country->DocumentModel->title}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Document UUID :</p>
                                            <h5 class="fs-15 mb-0">{{$country->DocumentModel->uuid}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">User Name :</p>
                                            <h5 class="fs-15 mb-0">{{$country->User->name}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">User Email :</p>
                                            <h5 class="fs-15 mb-0">{{$country->User->email}}</h5>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Requested Date :</p>
                                            <h5 class="fs-15 mb-0">{{$country->created_at}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Accessible :</p>
                                            @if($country->User->userType == 2)
                                            @if($country->status == 1)
                                            <div class="badge bg-success fs-12">Yes</div>
                                            @else
                                            <div class="badge bg-danger fs-12">No</div>
                                            @endif
                                            @else
                                            <div class="badge bg-success fs-12">Yes</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($country->message)
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <h6 class="fw-semibold text-uppercase">Message From {{$country->User->name}}</h6>
                                <p>{!!$country->message!!}</p>
                            </div>
                            @endif

                            <div id="image-container">
                                @if($country->DocumentModel->document)
                                    <div id="flipbookPDFContainer"></div>
                                @endif
                            </div>


                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
        </div>


    </div> <!-- container-fluid -->
</div><!-- End Page-content -->



@stop

@section('javascript')
@include('includes.admin.delete_handler')


@if($country->DocumentModel->document)
<script src="{{asset('main/dflip/js/libs/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('main/dflip/js/dflip.min.js')}}" type="text/javascript"></script>
<script nonce="{{ csp_nonce() }}">
    jQuery(document).ready(function () {

        //FOR PDFs
        var source_pdf = "{{asset('storage/upload/documents/'.$country->DocumentModel->document)}}";
        var option_pdf = {
            webglShadow: true,

            // if enable sound at start (true|false)
            soundEnable: false,

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
            transparent: false, //true or false
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
            scrollWheel: true,

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

@stop
