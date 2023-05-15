<?php

namespace App\Http\Controllers\Admin\Image;

use App\Http\Controllers\Admin\Contracts\ContentAccessController;
use App\Models\ImageAccess;

class ImageAccessController extends ContentAccessController
{
    public function __construct()
    {
        parent::__construct(ImageAccess::class, 'ImageModel');
    }

    public function viewaccess() {
        return parent::view_access_base('pages.admin.image.access_list');
    }

    public function deleteAccess($id){
        return parent::delete_access_base('image_view_access', $id);
    }

    public function toggleAccess($id){
        return parent::toggle_access_base($id);
    }

    public function displayAccess($id) {
        return parent::display_access_base('pages.admin.image.access_display', $id);
    }
}
