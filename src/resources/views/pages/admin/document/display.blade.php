@extends('layouts.admin.dashboard')


@section('css')
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
            'page_name' => "Document",
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
                                    <a href="{{route('document_edit', $country->id)}}" type="button" class="btn btn-success add-btn me-2" id="create-btn"><i class="ri-edit-line align-bottom me-1"></i> Edit</a>
                                    <button type="button" class="btn btn-danger add-btn remove-item-btn" data-link="{{route('document_delete', $country->id)}}" id="create-btn"><i class="ri-delete-bin-line align-bottom me-1 pointer-events-none"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted">
                            <div class="pt-3 pb-3 border-top border-top-dashed border-bottom border-bottom-dashed mt-4">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Title :</p>
                                            <h5 class="fs-15 mb-0">{{$country->title}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Year :</p>
                                            <h5 class="fs-15 mb-0">{{$country->year}}</h5>
                                        </div>
                                    </div>
                                    @if($country->languages->count()>0)
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Languages :</p>
                                            @foreach ($country->languages as $languages)
                                                <div class="badge bg-secondary fs-12">{{$languages->name}}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Deity :</p>
                                            <h5 class="fs-15 mb-0">{{$country->deity}}</h5>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Version :</p>
                                            <h5 class="fs-15 mb-0">{{$country->version}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Uploaded By :</p>
                                            <h5 class="fs-15 mb-0">{{$country->getAdminName()}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Status :</p>
                                            @if($country->status == 1)
                                            <div class="badge bg-success fs-12">Active</div>
                                            @else
                                            <div class="badge bg-danger fs-12">Inactive</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Restricted :</p>
                                            @if($country->restricted == 1)
                                            <div class="badge bg-success fs-12">Yes</div>
                                            @else
                                            <div class="badge bg-danger fs-12">No</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Total Number of Pages :</p>
                                            <h5 class="fs-15 mb-0">{{$country->page_number}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Total Favourites :</p>
                                            <h5 class="fs-15 mb-0">{{$country->favourites}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Total Views :</p>
                                            <h5 class="fs-15 mb-0">{{$country->views}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Create Date :</p>
                                            <h5 class="fs-15 mb-0">{{$country->created_at}}</h5>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @if($country->tags)
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        @php $tags = explode(",",$country->tags); @endphp
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Tags :</p>
                                            @foreach($tags as $tag)
                                            <div class="badge bg-success fs-12">{{$tag}}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($country->description_unformatted)
                            <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                <h6 class="fw-semibold text-uppercase">Description</h6>
                                <p>{!!$country->description!!}</p>
                            </div>
                            @endif

                            <div id="image-container">
                                @if($country->document)
                                <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                    <h6 class="fw-semibold text-uppercase">Document</h6>

                                    <div id="my_pdf_viewer" oncontextmenu="return false">
                                        <div id="pdf_controllers">

                                            <div id="navigation_controls">
                                                <button id="go_previous" title="previous page"><i class="bx bx-skip-previous"></i></button>
                                                <label>
                                                    <input id="current_page" value="1" type="text"/>
                                                    /<span id="totalPageCount">0</span>
                                                </label>
                                                <button id="go_next" title="next page"><i class="bx bx-skip-next"></i></button>
                                            </div>

                                            <div id="zoom_controls">
                                                <button id="zoom_in" title="zoom in"><i class="ri-zoom-in-line"></i></button>
                                                <button id="zoom_out" title="zoom out"><i class="ri-zoom-out-line"></i></button>
                                            </div>
                                        </div>

                                        <div id="canvas_container">
                                            <canvas id="pdf_renderer"></canvas>
                                        </div>


                                    </div>

                                </div>
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
<script src="http://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>
@include('includes.admin.delete_handler')

<script nonce="{{ csp_nonce() }}">


    var myState = {
        pdf: null,
        currentPage: 1,
        zoom: 1
    }

    pdfjsLib.getDocument('{{asset('storage/upload/documents/'.$country->document)}}').then((pdf) => {

        myState.pdf = pdf;
        document.getElementById("totalPageCount").innerHTML = myState.pdf._pdfInfo.numPages;
        render();

    });

    function render() {
        myState.pdf.getPage(myState.currentPage).then((page) => {

            var canvas = document.getElementById("pdf_renderer");
            var ctx = canvas.getContext('2d');

            var viewport = page.getViewport(myState.zoom);

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({
                canvasContext: ctx,
                viewport: viewport
            });
        });
    }



    document.getElementById('go_previous').addEventListener('click', (e) => {
        if(myState.pdf == null || myState.currentPage == 1) return false;
        myState.currentPage -= 1;
        document.getElementById("current_page").value = myState.currentPage;
        render();
    });

    document.getElementById('go_next').addEventListener('click', (e) => {
        if(myState.pdf == null || myState.currentPage >= myState.pdf._pdfInfo.numPages) return false;
        myState.currentPage += 1;
        document.getElementById("current_page").value = myState.currentPage;
        render();
    });

    document.getElementById('current_page').addEventListener('keypress', (e) => {
        if(myState.pdf == null) return;

        // Get key code
        var code = (e.keyCode ? e.keyCode : e.which);

        // If key code matches that of the Enter key
        if(code == 13) {
            var desiredPage =
            document.getElementById('current_page').valueAsNumber;

            if(desiredPage >= 1 && desiredPage <= myState.pdf._pdfInfo.numPages) {
                myState.currentPage = desiredPage;
                document.getElementById("current_page").value = desiredPage;
                render();
            }
        }
    });

    document.getElementById('zoom_in').addEventListener('click', (e) => {
        if(myState.pdf == null) return;
        myState.zoom += 0.1;
        render();
    });

    document.getElementById('zoom_out').addEventListener('click', (e) => {
        if(myState.pdf == null) return;
        myState.zoom -= 0.1;
        render();
    });

</script>
@stop
