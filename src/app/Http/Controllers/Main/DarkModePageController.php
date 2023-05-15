<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class DarkModePageController extends Controller
{
    public function index(){
        $user = User::where('id',Auth::user()->id)->firstOrFail();
        $user->update(
            [
                'darkMode' => $user->darkMode == 0 ? 1 : 0,
            ]
        );
        return redirect(URL::previous());
    }
}
