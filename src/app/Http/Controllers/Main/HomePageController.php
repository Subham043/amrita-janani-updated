<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Main\Contracts\CommonController;
use App\Models\PageModel;
use App\Models\BannerModel;
use App\Models\BannerQuoteModel;

class HomePageController extends CommonController
{
    public function index(){
        $data = PageModel::findOrFail(1);
        return parent::index_base('pages.main.index', 'Home')
        ->with('home', $data)
        ->with('bannerImage', BannerModel::inRandomOrder()->firstOrFail())
        ->with('bannerQuote', BannerQuoteModel::inRandomOrder()->firstOrFail());
    }
}
