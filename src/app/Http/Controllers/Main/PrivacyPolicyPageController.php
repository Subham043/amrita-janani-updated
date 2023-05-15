<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Main\Contracts\CommonController;

class PrivacyPolicyPageController extends CommonController
{
    public function index(){
        return parent::index_base('pages.main.privacy_policy', 'Privacy Policy');
    }
}
