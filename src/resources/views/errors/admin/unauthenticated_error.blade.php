@extends('layouts.admin.auth')



@section('content')

    @include('errors.admin.includes.error_content', ['exception'=> $exception])

@stop
