@extends('layouts.main.index')

@section('css')
    <style nonce="{{ csp_nonce() }}">
        .panel{
            max-height: 100%;
            height:auto;
        }
    </style>
@stop

@section('content')

@include('includes.main.sub_menu')

@include('includes.main.breadcrumb')

<div class="content-holder">
    <div class="container content-container pb-0">
        <div class="media-container">
            <h3>
                AUDIO
            </h3>

        </div>

    </div>
</div>
<div class="content-holder">
    <div class="container content-container pt-0">
        <div class="media-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row sort-row">
                        <div class="col-lg-2 col-md-12 mb-3 sort-div">
                            <i class="fas fa-sort-amount-down"></i>
                            <select name="sort" id="sort">
                                <option value="newest" @if(app('request')->has('sort') && app('request')->input('sort')=="newest") selected @endif>Sort by Newest</option>
                                <option value="oldest" @if(app('request')->has('sort') && app('request')->input('sort')=='oldest') selected @endif>Sort by Oldest</option>
                                <option value="a-z" @if(app('request')->has('sort') && app('request')->input('sort')=="a-z") selected @endif>Sort by A-Z</option>
                                <option value="z-a" @if(app('request')->has('sort') && app('request')->input('sort')=="z-a") selected @endif>Sort by Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">

                    <div class="filter-holder">
                        <hr>

                        <button class="accordion active">Other Filter</button>
                        <div class="panel">
                            <ul>
                                <li>
                                    <label for="filter_check">
                                    <input type="checkbox" id="filter_check" name="filter" value="favourite" @if(app('request')->has('filter') && app('request')->input('filter')=="favourite") checked @endif>
                                        My Favourite Audio
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <hr>

                        @if(count($languages) > 0)
                        <button class="accordion active">Language</button>
                        <div class="panel">
                            <ul>

                                @foreach($languages as $languages)
                                <li>
                                    <label for="language{{$languages->id}}">
                                        <input type="checkbox" name="language" id="language{{$languages->id}}" value="{{$languages->id}}" @if(app('request')->has('language') && in_array($languages->id, explode('_', app('request')->input('language'))) ) checked @endif>
                                        {{$languages->name}}
                                    </label>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                        <hr>
                        @endif


                    </div>
                    <div class="text-left">
                        <button id="filter_button" class="filter_button"> Apply </button>
                        <a href="{{route('content_audio')}}" class="filter_button"> Clear </a>
                    </div>

                </div>

                <div class="col-lg-9">

                    <div class="row">

                        @if($audios->count() > 0)

                        @foreach($audios->items() as $audio)
                        <div class="col-lg-4 col-sm-12">
                            <a class="media-href" title="{{$audio->title}}" href="{{route('content_audio_view', $audio->uuid)}}">
                                <div class="img-holder">
                                    <img class="icon-img" src="{{Vite::asset('resources/images/audio-book.webp')}}" alt="">
                                </div>
                                <div class="media-holder">
                                    <h5>{{$audio->title}}</h5>
                                    <p class="desc">{{$audio->description_unformatted}}</p>
                                    {{-- <p>Format : {{$audio->file_format()}}</p> --}}
                                    @if($audio->languages->count()>0)
                                    <p>Language :
                                    @foreach ($audio->languages as $languages)
                                        {{$languages->name}},
                                    @endforeach
                                    </p>
                                    @endif
                                    <p>Duration : {{$audio->duration}}</p>
                                    <p>Uploaded : {{$audio->time_elapsed()}}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach

                        @else
                        <div class="col-lg-12 col-sm-12 text-center">
                            <h6>No items are available.</h6>
                        </div>
                        @endif

                    </div>
                </div>
                <div class="col-lg-3"></div>
                <div class="col-lg-9 my-4 nav-flex-direction-end">
                    {{-- @if($audios->previousPageUrl()==null)
                    <p>Showing {{(($audios->perPage() * $audios->currentPage()) - $audios->perPage() + 1)}} to {{($audios->currentPage() * $audios->perPage())}} of {{$audios->total()}} entries</p>
                    @else
                    <p>Showing {{(($audios->perPage() * $audios->currentPage()) - $audios->perPage() + 1)}} to {{($audios->total())}} of {{$audios->total()}} entries</p>
                    @endif --}}

                    {{ $audios->links('pagination::bootstrap-4') }}

                </div>
            </div>

        </div>

    </div>
</div>



@stop

@section('javascript')
<script src="{{ asset('main/js/plugins/axios.min.js') }}"></script>

@include('pages.main.content.common.search_js', ['search_url'=>route('content_audio_search_query')])

@include('pages.main.content.common.multimedia_search_handler', ['search_url'=>route('content_audio')])
@include('pages.main.content.common.accordian_js')

@stop
