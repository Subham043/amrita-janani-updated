<?php

namespace App\Http\Controllers\Admin\Audio;

use App\Http\Controllers\Admin\Contracts\ContentReportController;
use App\Models\AudioReport;

class AudioReportController extends ContentReportController
{
    public function __construct()
    {
        parent::__construct(AudioReport::class, 'AudioModel');
    }

    public function viewreport() {
        return parent::view_report_base('pages.admin.audio.report_list');
    }

    public function deleteReport($id){
        return parent::delete_report_base('audio_view_report', $id);
    }

    public function toggleReport($id){
        return parent::toggle_report_base($id);
    }

    public function displayReport($id) {
        return parent::delete_access_base('pages.admin.audio.report_display', $id);
    }

}
