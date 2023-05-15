<?php

namespace App\Http\Controllers\Admin\Video;

use App\Http\Controllers\Admin\Contracts\ContentAccessController;
use App\Models\VideoAccess;

class VideoAccessController extends ContentAccessController
{
    public function __construct()
    {
        parent::__construct(VideoAccess::class, 'VideoModel');
    }

    public function viewaccess() {
        return parent::view_access_base('pages.admin.video.access_list');
    }

    public function deleteAccess($id){
        return parent::delete_access_base('video_view_access', $id);
    }

    public function toggleAccess($id){
        return parent::toggle_access_base($id);
    }

    public function displayAccess($id) {
        return parent::display_access_base('pages.admin.video.access_display', $id);
    }
}
