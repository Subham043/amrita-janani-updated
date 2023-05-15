<?php

namespace App\Http\Controllers\Admin\Document;

use App\Http\Controllers\Admin\Contracts\ContentReportController;
use App\Models\DocumentReport;

class DocumentReportController extends ContentReportController
{
    public function __construct()
    {
        parent::__construct(DocumentReport::class, 'DocumentModel');
    }

    public function viewreport() {
        return parent::view_report_base('pages.admin.document.report_list');
    }

    public function deleteReport($id){
        return parent::delete_report_base('document_view_report', $id);
    }

    public function toggleReport($id){
        return parent::toggle_report_base($id);
    }

    public function displayReport($id) {
        return parent::delete_access_base('pages.admin.document.report_display', $id);
    }

}
