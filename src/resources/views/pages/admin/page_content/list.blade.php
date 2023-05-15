@extends('layouts.admin.dashboard')



@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('includes.admin.page_title', [
            'page_name' => "Dynamic Web Pages",
            'current_page' => "List",
        ])

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Dynamic Web Pages</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <button  data-bs-toggle="modal" data-bs-target="#myModal" type="button" class="btn btn-success add-btn" id="create-btn"><i class="ri-add-line align-bottom me-1"></i> Create</button>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    @include('includes.admin.common_search_form', [
                                        'url' => route('dynamic_page_list'),
                                    ])
                                </div>
                            </div>
                            <div class="table-responsive table-card mt-3 mb-1">
                                @if($country->total() > 0)
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sort" data-sort="customer_name">Title</th>
                                            <th class="sort" data-sort="customer_name">Page Name</th>
                                            <th class="sort" data-sort="customer_name">Url</th>
                                            <th class="sort" data-sort="status">Status</th>
                                            <th class="sort" data-sort="status">Restricted</th>
                                            <th class="sort" data-sort="date">Created Date</th>
                                            <th class="sort" data-sort="action">Action</th>
                                            </tr>
                                    </thead>
                                    <tbody class="list form-check-all">

                                        @foreach ($country->items() as $item)
                                        <tr>
                                            <td class="customer_name">{{$item->title}}</td>
                                            <td class="customer_name">{{$item->page_name}}</td>
                                            <td class="customer_name">{{$item->url}}</td>
                                            @if($item->status == 1)
                                            <td class="status"><span class="badge badge-soft-success text-uppercase">Active</span></td>
                                            @else
                                            <td class="status"><span class="badge badge-soft-danger text-uppercase">Inactive</span></td>
                                            @endif
                                            @if($item->restricted == 1)
                                            <td class="status"><span class="badge badge-soft-success text-uppercase">Yes</span></td>
                                            @else
                                            <td class="status"><span class="badge badge-soft-danger text-uppercase">No</span></td>
                                            @endif
                                            <td class="date">{{$item->created_at}}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div class="edit">
                                                        <a href="{{route('edit_dynamic_page', $item->id)}}" class="btn btn-sm btn-success edit-item-btn">Edit</a>
                                                    </div>
                                                    <div class="remove">
                                                        <button class="btn btn-sm btn-danger remove-item-btn" data-link="{{route('deletePage', $item->id)}}">Remove</button>
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

        @include('pages.admin.page_content.create_page_modal')

    </div>
</div>

@stop

@section('javascript')
<script src="{{ asset('admin/js/pages/axios.min.js') }}"></script>

@include('includes.admin.delete_handler')

<script type="text/javascript" nonce="{{ csp_nonce() }}">
    // initialize the validation library
    const validation = new JustValidate('#countryForm', {
        errorFieldCssClass: 'is-invalid',
    });
    // apply rules to form fields
    validation
        .addField('#title', [{
                rule: 'required',
                errorMessage: 'Title is required',
            },
            {
                rule: 'customRegexp',
                value: /^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i,
                errorMessage: 'Title is invalid',
            },
        ])
        .addField('#page_name', [{
                rule: 'required',
                errorMessage: 'Page Name is required',
            },
            {
                rule: 'customRegexp',
                value: /^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i,
                errorMessage: 'Page Name is invalid',
            },
        ])
        .addField('#url', [{
                rule: 'required',
                errorMessage: 'URL is required',
            },
            {
                rule: 'customRegexp',
                value: /^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i,
                errorMessage: 'URL is invalid',
            },
        ])
        .onSuccess(async (event) => {
            // event.target.submit();


            var submitBtn = document.getElementById('submitBtn')
            submitBtn.innerHTML = `
        <span class="d-flex align-items-center">
            <span class="spinner-border flex-shrink-0" role="status">
                <span class="visually-hidden">Loading...</span>
            </span>
            <span class="flex-grow-1 ms-2">
                Loading...
            </span>
        </span>
        `
            submitBtn.disabled = true;

            try {
                var formData = new FormData();
                formData.append('title', document.getElementById('title').value)
                formData.append('page_name', document.getElementById('page_name').value)
                formData.append('url', document.getElementById('url').value)

                const response = await axios.post('{{ route('storePage') }}', formData)
                successToast(response.data.message)
                setTimeout(function() {
                    window.location.replace(response.data.url);
                }, 1000);
            } catch (error) {
                console.log(error);
                if (error?.response?.data?.errors?.title) {
                    errorToast(error?.response?.data?.errors?.title[0])
                }
                if (error?.response?.data?.errors?.page_name) {
                    errorToast(error?.response?.data?.errors?.page_name[0])
                }
                if (error?.response?.data?.errors?.url) {
                    errorToast(error?.response?.data?.errors?.url[0])
                }
            } finally {
                submitBtn.innerHTML = `
            Update
            `
                submitBtn.disabled = false;
            }
        });
</script>

@stop
