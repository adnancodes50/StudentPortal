<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminGroupsController extends Controller
{
    public function index()
    {
        return view('admin.groups.index');
    }
}

