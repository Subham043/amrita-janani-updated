<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Main\Contracts\CommonContentController;
use App\Http\Controllers\Main\Contracts\ContentRequest;
use App\Models\DocumentModel;
use App\Models\DocumentFavourite;
use App\Models\DocumentAccess;
use App\Models\DocumentReport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DocumentPageController extends CommonContentController
{
    public function __construct()
    {
        parent::__construct(DocumentModel::class, DocumentFavourite::class, DocumentAccess::class, DocumentReport::class);
    }

    public function index(){
        return parent::index_base('pages.main.content.document', 'Document', 'document');
    }

    public function view($uuid){
        return parent::view_base('pages.main.content.document_view', 'document', $uuid);
    }

    public function documentFile($uuid){
        $document = DocumentModel::where('uuid', $uuid)->where('status', 1)->firstOrFail();

        if($document->contentVisible()){
            $file = File::get(storage_path('app/public/upload/documents/').$document->document);
            $response = Response::make($file, 200);
            $response->header('Content-Type', 'application/'.File::extension($document->document));
            $response->header('Cache-Control', 'public, max_age=3600');
            return $response;
        }else{
            return redirect()->intended(route('content_document_view', $uuid));
        }
    }

    public function makeFavourite($uuid){
        return parent::make_favourite_base('content_document_view', 'document', $uuid);
    }

    public function requestAccess(ContentRequest $req, $uuid){
        return parent::request_access_base($req, 'document', $uuid);
    }

    public function report(ContentRequest $req, $uuid){
        return parent::report_base($req, 'document', $uuid);
    }

    public function search_query(){
        return parent::search_query_base("Documents");
    }
}
