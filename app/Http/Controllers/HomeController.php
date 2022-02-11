<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show()
    {
        return view('app');
    }
}
