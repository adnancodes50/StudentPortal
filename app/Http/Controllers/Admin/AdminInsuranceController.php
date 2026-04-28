<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminInsuranceController extends Controller
{
    public function index()
    {
        return view('admin.insurance.index');
    }
}

