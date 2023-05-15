<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Main\Contracts\CommonController;
use App\Models\FAQModel;

class FAQPageController extends CommonController
{
    public function index(){
        return parent::index_base('pages.main.faq', 'Frequently Asked Questions')
        ->with('faq', FAQModel::all());
    }
}
