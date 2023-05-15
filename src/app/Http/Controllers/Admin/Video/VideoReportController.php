<?php

namespace App\Http\Controllers\Admin\Video;

use App\Http\Controllers\Admin\Contracts\ContentReportController;
use App\Models\VideoReport;

class VideoReportController extends ContentReportController
{
    public function __construct()
    {
        parent::__construct(VideoReport::class, 'VideoModel');
    }

    public function viewreport() {
        return parent::view_report_base('pages.admin.video.report_list');
    }

    public function deleteReport($id){
        return parent::delete_report_base('video_view_report', $id);
    }

    public function toggleReport($id){
        return parent::toggle_report_base($id);
    }

    public function displayReport($id) {
        return parent::delete_access_base('pages.admin.video.report_display', $id);
    }
}
