<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Main\Contracts\CommonController;
use App\Models\PageModel;

class AboutPageController extends CommonController
{
    public function index(){
        $data = PageModel::findOrFail(2);
        return parent::index_base('pages.main.about', $data->title)->with('about', $data);
    }
}
