<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Main\Contracts\CommonContentController;
use App\Http\Controllers\Main\Contracts\ContentRequest;
use App\Models\VideoModel;
use App\Models\VideoFavourite;
use App\Models\VideoAccess;
use App\Models\VideoReport;

class VideoPageController extends CommonContentController
{
    public function __construct()
    {
        parent::__construct(VideoModel::class, VideoFavourite::class, VideoAccess::class, VideoReport::class);
    }

    public function index(){
        return parent::index_base('pages.main.content.video', 'Video', 'video', 'VideoFavourite');
    }

    public function view($uuid){
        return parent::view_base('pages.main.content.video_view', 'video', $uuid);
    }

    public function makeFavourite($uuid){
        return parent::make_favourite_base('content_video_view', 'video', $uuid);
    }

    public function requestAccess(ContentRequest $req, $uuid){
        return parent::request_access_base($req, 'video', $uuid);
    }

    public function report(ContentRequest $req, $uuid){
        return parent::report_base($req, 'video', $uuid);
    }

    public function search_query(){
        return parent::search_query_base("Videos");
    }
}
