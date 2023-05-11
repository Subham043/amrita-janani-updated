@extends('layouts.admin.dashboard')



@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('includes.admin.page_title', [
            'page_name' => "Enquiry",
            'current_page' => "List",
        ])

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Enquiries</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <a href={{route('enquiry_excel')}} type="button" class="btn btn-info add-btn" id="create-btn"><i class="ri-file-excel-fill align-bottom me-1"></i> Excel</a>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <form  method="get" action="{{route('enquiry_view')}}">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <input type="text" name="search" class="form-control search" placeholder="Search..." value="@if(app('request')->has('search')) {{app('request')->input('search')}} @endif">
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
                                            <th class="sort" data-sort="customer_name">Name</th>
                                            <th class="sort" data-sort="customer_name">Phone</th>
                                            <th class="sort" data-sort="status">Email</th>
                                            <th class="sort" data-sort="date">Created Date</th>
                                            <th class="sort" data-sort="action">Action</th>
                                            </tr>
                                    </thead>
                                    <tbody class="list form-check-all">

                                        @foreach ($country->items() as $item)
                                        <tr>
                                            <td class="customer_name">{{$item->name}}</td>
                                            <td class="customer_name">{{$item->phone}}</td>
                                            <td class="customer_name">{{$item->email}}</td>
                                            <td class="date">{{$item->created_at}}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div class="edit">
                                                        <a href="{{route('enquiry_display', $item->id)}}" class="btn btn-sm btn-info edit-item-btn">View</a>
                                                    </div>
                                                    <div class="remove">
                                                        <button class="btn btn-sm btn-danger remove-item-btn" data-link="{{route('enquiry_delete', $item->id)}}">Delete</button>
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

{{-- <script src="{ asset('admin/libs/list.js/list.min.js') }}"></script> --}}
{{-- <script src="{ asset('admin/libs/list.pagination.js/list.pagination.min.js') }}"></script> --}}

<!-- listjs init -->
{{-- <script src="{ asset('admin/js/pages/listjs.init.js') }}"></script> --}}

@include('includes.admin.delete_handler')

@stop
