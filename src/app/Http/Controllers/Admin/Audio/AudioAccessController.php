<?php

namespace App\Http\Controllers\Admin\Audio;

use App\Http\Controllers\Admin\Contracts\ContentAccessController;
use App\Models\AudioAccess;

class AudioAccessController extends ContentAccessController
{
    public function __construct()
    {
        parent::__construct(AudioAccess::class, 'AudioModel');
    }

    public function viewaccess() {
        return parent::view_access_base('pages.admin.audio.access_list');
    }

    public function deleteAccess($id){
        return parent::delete_access_base('audio_view_access', $id);
    }

    public function toggleAccess($id){
        return parent::toggle_access_base($id);
    }

    public function displayAccess($id) {
        return parent::display_access_base('pages.admin.audio.access_display', $id);
    }
}
