<?php

namespace App\Http\Controllers\Admin\Image;

use App\Http\Controllers\Admin\Contracts\ContentReportController;
use App\Models\ImageReport;

class ImageReportController extends ContentReportController
{
    public function __construct()
    {
        parent::__construct(ImageReport::class, 'ImageModel');
    }

    public function viewreport() {
        return parent::view_report_base('pages.admin.image.report_list');
    }

    public function deleteReport($id){
        return parent::delete_report_base('image_view_report', $id);
    }

    public function toggleReport($id){
        return parent::toggle_report_base($id);
    }

    public function displayReport($id) {
        return parent::display_report_base('pages.admin.image.report_display', $id);
    }
}
