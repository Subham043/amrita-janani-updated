<?php

namespace App\Http\Controllers\Main\Contracts;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function index_base(string $view, string $breadcrumb){
        return view($view)->with('breadcrumb',$breadcrumb);
    }

}
