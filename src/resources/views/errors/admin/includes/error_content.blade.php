<div class="row justify-content-center">
    <div class="col-xl-6 text-center">
        <div class="error-500 position-relative">
            <img src="{{asset('admin/images/error500.png')}}" alt="" class="img-fluid error-500-img error-img">
            <h1 class="title text-warning">{{$status_code}}</h1>
        </div>
        <div>
            <h4>{{$message}}!</h4>
            @if(auth()->check())
                <a href="{{route('dashboard')}}" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to dashboard</a>
            @else
                <a href="{{route('login')}}" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to login</a>
            @endif
        </div>
    </div><!-- end col-->
</div>
