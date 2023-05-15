<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Admin\Contracts\ContentAccessController;
use App\Models\DocumentAccess;

class DocumentAccessController extends ContentAccessController
{
    public function __construct()
    {
        parent::__construct(DocumentAccess::class, 'DocumentModel');
    }

    public function viewaccess() {
        return parent::view_access_base('pages.admin.document.access_list');
    }

    public function deleteAccess($id){
        return parent::delete_access_base('document_view_access', $id);
    }

    public function toggleAccess($id){
        return parent::toggle_access_base($id);
    }

    public function displayAccess($id) {
        return parent::delete_access_base('pages.admin.document.access_display', $id);
    }

}
