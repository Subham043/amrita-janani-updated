@extends('layouts.admin.dashboard')


@section('content')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Audio</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <div>
                                    <a href="{{route('audio_display', $country->AudioModel->id)}}" type="button" class="btn btn-success add-btn" id="create-btn"><i class="ri-arrow-go-back-line"></i> Go To Audio</a>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <form action="{{route('audio_toggle_report', $country->id)}}" method="get" class="mr-3">
                                        <select class="form-control mr-2" name="status" onchange="this.form.submit()">
                                            <option value="0" {{ $country->status==0 ? 'selected':''}}>Pending</option>
                                            <option value="1" {{ $country->status==1 ? 'selected':''}}>In progress</option>
                                            <option value="2" {{ $country->status==2 ? 'selected':''}}>Completed</option>
                                        </select>
                                    </form>
                                    <button data-link="{{route('audio_delete_report', $country->id)}}" type="button" class="btn btn-danger add-btn remove-item-btn" id="create-btn"><i class="ri-delete-bin-line align-bottom me-1"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted">
                            <div class="pt-3 pb-3 border-top border-top-dashed border-bottom border-bottom-dashed mt-4">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Audio Title :</p>
                                            <h5 class="fs-15 mb-0">{{$country->AudioModel->title}}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div>
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Audio UUID :</p>
                                            <h5 class="fs-15 mb-0">{{$country->AudioModel->uuid}}</h5>
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
                                            <p class="mb-2 text-uppercase fw-medium fs-13">Status :</p>
                                            @if($country->status == 2)
                                            <div class="badge bg-success fs-12">Completed</div>
                                            @elseif($country->status == 1)
                                            <div class="badge bg-info fs-12">In Progress</div>
                                            @else
                                            <div class="badge bg-danger fs-12">Pending</div>
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
                                @if($country->AudioModel->audio)
                                <div class="pt-3 pb-3 border-bottom border-bottom-dashed mt-4">
                                    <h6 class="fw-semibold text-uppercase">Audio</h6>
                                    <audio id="player" controls>
                                        <source src="{{asset('storage/upload/audios/'.$country->AudioModel->audio)}}" type="audio/mp3" />
                                    </audio>
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

@include('includes.admin.audio_player_script')
@include('includes.admin.delete_handler')

@stop
