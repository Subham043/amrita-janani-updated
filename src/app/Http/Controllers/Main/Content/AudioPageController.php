<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Main\Contracts\CommonContentController;
use App\Http\Controllers\Main\Contracts\ContentRequest;
use App\Models\AudioModel;
use App\Models\AudioFavourite;
use App\Models\AudioAccess;
use App\Models\AudioReport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AudioPageController extends CommonContentController
{
    public function __construct()
    {
        parent::__construct(AudioModel::class, AudioFavourite::class, AudioAccess::class, AudioReport::class);
    }

    public function index(){
        return parent::index_base('pages.main.content.audio', 'Audio', 'audio', 'AudioFavourite');
    }

    public function view($uuid){
        return parent::view_base('pages.main.content.audio_view', 'audio', $uuid);
    }

    public function audioFile($uuid){
        $audio = AudioModel::where('uuid', $uuid)->where('status', 1)->firstOrFail();

        if($audio->contentVisible()){
            try {
                //code...
                $file = File::get(storage_path('app/public/upload/audios/').$audio->audio);
                $response = Response::make($file, 200);
                $response->header('Content-Type', 'audio/'.File::extension($audio->audio));
                $response->header('Cache-Control', 'public, max_age=3600');
                return $response;
            } catch (\Throwable $th) {
                //throw $th;
                abort(404, 'file not found');
            }
        }else{
            return redirect()->intended(route('content_audio_view', $uuid));
        }
    }

    public function makeFavourite($uuid){
        return parent::make_favourite_base('content_audio_view', 'audio', $uuid);
    }

    public function requestAccess(ContentRequest $req, $uuid){
        return parent::request_access_base($req, 'audio', $uuid);
    }

    public function report(ContentRequest $req, $uuid){
        return parent::report_base($req, 'audio', $uuid);
    }

    public function search_query(){
        return parent::search_query_base("Audios");
    }
}
