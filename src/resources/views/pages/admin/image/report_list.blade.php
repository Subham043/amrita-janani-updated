@extends('layouts.admin.dashboard')



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
                        <h4 class="card-title mb-0">Images</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">

                                <div class="col-sm row mt-4 justify-content-end">
                                    <form  method="get" action="{{route('image_view_report')}}" class="col-sm-auto" onsubmit="return callSearchHandler()">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <select name="filter" id="filter" class="form-control" onchange="return callSearchHandler()">
                                                    <option value="all" @if((app('request')->has('filter')) && (app('request')->input('filter')=='all')) selected @endif>All</option>
                                                    <option value="0" @if(app('request')->has('filter') && (app('request')->input('filter')=='0')) selected @endif>Pending</option>
                                                    <option value="1" @if(app('request')->has('filter') && (app('request')->input('filter')=='1')) selected @endif>In Progress</option>
                                                    <option value="2" @if(app('request')->has('filter') && (app('request')->input('filter')=='2')) selected @endif>Completed</option>
                                                </select>
                                                <i class="ri-arrow-up-down-line search-icon"></i>
                                            </div>
                                        </div>
                                    </form>
                                    <form  method="get" action="{{route('image_view_report')}}" class="col-sm-auto" onsubmit="return callSearchHandler()">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <input type="text" id="search" name="search" class="form-control search" placeholder="Search..." value="@if(app('request')->has('search')) {{app('request')->input('search')}} @endif">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive table-card mt-3 mb-1">
                                @if($country->total() > 0)
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sort" data-sort="customer_name">Image Title</th>
                                            <th class="sort" data-sort="customer_name">Image UUID</th>
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
                                            <td class="customer_name">{{$item->ImageModel->title}}</td>
                                            <td class="customer_name">{{$item->ImageModel->uuid}}</td>
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
                                                    <div class="search-box edit">
                                                        <form action="{{route('image_toggle_report', $item->id)}}" method="get">
                                                            <select class="form-control" name="status" onchange="this.form.submit()" class="w-100">
                                                                <option value="0" {{ $item->status==0 ? 'selected':''}}>Pending</option>
                                                                <option value="1" {{ $item->status==1 ? 'selected':''}}>In progress</option>
                                                                <option value="2" {{ $item->status==2 ? 'selected':''}}>Completed</option>
                                                            </select>
                                                        </form>
                                                        <i class="ri-arrow-up-down-line search-icon"></i>
                                                    </div>
                                                    <div class="edit">
                                                        <a href="{{route('image_display_report', $item->id)}}" class="btn btn-sm btn-info edit-item-btn">View</a>
                                                    </div>
                                                    <div class="edit">
                                                        <a href="{{route('image_display', $item->ImageModel->id)}}" class="btn btn-sm btn-warning edit-item-btn">Go To Image</a>
                                                    </div>
                                                    <div class="remove">
                                                        <button class="btn btn-sm btn-danger remove-item-btn" data-link="{{route('image_delete_report', $item->id)}}">Delete</button>
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
@include('includes.admin.call_search_handler', ['url'=>route('image_view_report')])

@stop
