@extends('layouts.admin.dashboard')


@section('css')
    <link href="{{ asset('admin/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/css/tagify.css') }}" rel="stylesheet" type="text/css" />

    <style nonce="{{ csp_nonce() }}">
        #description, #description_update {
            min-height: 200px;
        }
    </style>
@stop


@section('content')

    <div class="page-content">
        <div class="container-fluid">

            @include('includes.admin.page_title', [
                'page_name' => "Page",
                'current_page' => "FAQ",
            ])

            <div class="row">
                <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">FAQ</h4>
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <button
                                            type="button" class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#myModal"><i
                                                class="ri-add-line align-bottom me-1"></i> Add FAQ</button>
                                    </div>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        @if(count($faq) > 0)
                                        <table class="table align-middle table-nowrap" id="customerTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="sort" data-sort="customer_name">Question</th>
                                                    <th class="sort" data-sort="customer_name">Answer</th>
                                                    <th class="sort" data-sort="date">Created Date</th>
                                                    <th class="sort" data-sort="action">Action</th>
                                                    </tr>
                                            </thead>
                                            <tbody class="list form-check-all">

                                                @foreach ($faq as $item)
                                                <tr>
                                                    <td class="customer_name">{{$item->question}}</td>
                                                    <td class="customer_name">{{$item->answer}}</td>
                                                    <td class="customer_name">{{$item->created_at}}</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <div class="edit">
                                                                <button data-bs-toggle="modal" data-bs-target="#myModalUpdate" class="btn btn-sm btn-success edit-item-btn" data-id="{{$item->id}}" data-question="{!!$item->question!!}" data-answer="{!!$item->answer!!}">Edit</button>
                                                            </div>
                                                            <div class="remove">
                                                                <button class="btn btn-sm btn-danger remove-item-btn" data-link="{{route('faq_delete', $item->id)}}">Remove</button>
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
                                    <!--end row-->
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        @include('pages.admin.faq.add_faq_modal')
        @include('pages.admin.faq.update_faq_modal')

    </div> <!-- container-fluid -->
    </div><!-- End Page-content -->



@stop


@section('javascript')
    <script src="{{ asset('admin/js/pages/axios.min.js') }}"></script>

@include('includes.admin.delete_handler')

@include('pages.admin.faq.add_faq_modal_js')
@include('pages.admin.faq.update_faq_modal_js')

@stop
