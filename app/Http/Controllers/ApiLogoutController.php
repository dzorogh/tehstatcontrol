<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLogoutController extends Controller
{
    public function __invoke(Request $request) {

        $request->session()->invalidate();
        Auth::logout();

        return abort(401);
    }
}
