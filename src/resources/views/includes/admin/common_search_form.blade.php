<form  method="get" action="{{$url}}">
    <div class="d-flex justify-content-sm-end">
        <div class="search-box ms-2">
            <input type="text" name="search" class="form-control search" placeholder="Search..." value="@if(app('request')->has('search')){{app('request')->input('search')}}@endif">
            <i class="ri-search-line search-icon"></i>
        </div>
    </div>
</form>
