<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardResellerController extends Controller
{
    public function index()
    {
        return view('dashboard_reseller');
    }
}
