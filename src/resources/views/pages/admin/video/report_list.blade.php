@extends('layouts.admin.dashboard')

@section('css')
<style nonce="{{ csp_nonce() }}">
    .w-130{
        width: 130px;
    }
</style>
@stop

@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('includes.admin.page_title', [
            'page_name' => "Reports",
            'current_page' => "List",
        ])

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Videos</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">

                                <div class="col-sm row mt-4 justify-content-end">
                                    @include('includes.admin.filter_search_report_form', [
                                        'url' => route('video_view_report'),
                                    ])
                                </div>
                            </div>
                            <div class="table-responsive table-card mt-3 mb-1">
                                @if($country->total() > 0)
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sort" data-sort="customer_name">Video Title</th>
                                            <th class="sort" data-sort="customer_name">Video UUID</th>
                                            <th class="sort" data-sort="customer_name">User Name</th>
                                            <th class="sort" data-sort="customer_name">User Email</th>
                                            <th class="sort" data-sort="status">Status</th>
                                            <th class="sort" data-sort="date">Requested Date</th>
                                            <th class="sort" data-sort="action">Action</th>
                                            </tr>
                                    </thead>
                                    <tbody class="list form-check-all">

                                        @foreach ($country->items() as $item)
                                        <tr>
                                            <td class="customer_name">{{$item->VideoModel->title}}</td>
                                            <td class="customer_name">{{$item->VideoModel->uuid}}</td>
                                            <td class="customer_name">{{$item->User->name}}</td>
                                            <td class="customer_name">{{$item->User->email}}</td>
                                            @if($item->status == 2)
                                            <td class="status"><span class="badge badge-soft-success text-uppercase">Completed</span></td>
                                            @elseif($item->status == 1)
                                            <td class="status"><span class="badge badge-soft-info text-uppercase">In Progress</span></td>
                                            @else
                                            <td class="status"><span class="badge badge-soft-danger text-uppercase">Pending</span></td>
                                            @endif
                                            <td class="date">{{$item->created_at}}</td>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="search-box edit w-130">
                                                        <form action="{{route('video_toggle_report', $item->id)}}" method="get">
                                                            <select class="form-control status-handler" name="status" class="w-100">
                                                                <option value="0" {{ $item->status==0 ? 'selected':''}}>Pending</option>
                                                                <option value="1" {{ $item->status==1 ? 'selected':''}}>In progress</option>
                                                                <option value="2" {{ $item->status==2 ? 'selected':''}}>Completed</option>
                                                            </select>
                                                        </form>
                                                        <i class="ri-arrow-up-down-line search-icon"></i>
                                                    </div>
                                                    <div class="edit">
                                                        <a href="{{route('video_display_report', $item->id)}}" class="btn btn-sm btn-info edit-item-btn">View</a>
                                                    </div>
                                                    <div class="edit">
                                                        <a href="{{route('video_display', $item->VideoModel->id)}}" class="btn btn-sm btn-warning edit-item-btn">Go To Video</a>
                                                    </div>
                                                    <div class="remove">
                                                        <button class="btn btn-sm btn-danger remove-item-btn" data-link="{{route('video_delete_report', $item->id)}}">Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                @else
                                    @include('includes.admin.no_result')
                                @endif
                            </div>

                            {{$country->onEachSide(5)->links('includes.admin.pagination')}}
                        </div>
                    </div><!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    </div>
</div>

@stop

@section('javascript')

@include('includes.admin.delete_handler')
@include('includes.admin.call_search_handler', ['url'=>route('video_view_report')])
@include('includes.admin.call_status_handler')

@stop
