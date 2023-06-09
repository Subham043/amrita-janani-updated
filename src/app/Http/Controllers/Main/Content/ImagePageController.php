<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Main\Contracts\CommonContentController;
use App\Http\Controllers\Main\Contracts\ContentRequest;
use Illuminate\Support\Facades\Response;
use App\Models\ImageModel;
use App\Models\ImageFavourite;
use App\Models\ImageAccess;
use App\Models\ImageReport;
use Illuminate\Support\Facades\File;

class ImagePageController extends CommonContentController
{
    public function __construct()
    {
        parent::__construct(ImageModel::class, ImageFavourite::class, ImageAccess::class, ImageReport::class);
    }

    public function index(){
        return parent::index_base('pages.main.content.image', 'Image', 'image', 'ImageFavourite');
    }

    public function view($uuid){
        return parent::view_base('pages.main.content.image_view', 'image', $uuid);
    }

    public function thumbnail($uuid){
        $image = ImageModel::where('uuid', $uuid)->where('status', 1)->firstOrFail();
        try {
            //code...
            $file = File::get(storage_path('app/public/upload/images/compressed-').$image->image);
            $response = Response::make($file, 200);
            $response->header('Content-Type', 'image/'.File::extension($image->image));
            $response->header('Cache-Control', 'public, max_age=3600');
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            abort(404, 'file not found');
        }
    }

    public function imageFile($uuid){
        $image = ImageModel::where('uuid', $uuid)->where('status', 1)->firstOrFail();

        if($image->contentVisible()){
            try {
                //code...
                $file = File::get(storage_path('app/public/upload/images/').$image->image);
                $response = Response::make($file, 200);
                $response->header('Content-Type', 'image/'.File::extension($image->image));
                $response->header('Cache-Control', 'public, max_age=3600');
                return $response;
            } catch (\Throwable $th) {
                //throw $th;
                abort(404, 'file not found');
            }
        }else{
            return redirect()->intended(route('content_image_view', $uuid));
        }
    }

    public function makeFavourite($uuid){
        return parent::make_favourite_base('content_image_view', 'image', $uuid);
    }

    public function requestAccess(ContentRequest $req, $uuid){
        return parent::request_access_base($req, 'image', $uuid);
    }

    public function report(ContentRequest $req, $uuid){
        return parent::report_base($req, 'image', $uuid);
    }

    public function search_query(){
        return parent::search_query_base("Images");
    }
}
