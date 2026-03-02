<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourcesEvalutionController extends Controller
{
    public function index()
    {
        return view('admin.cources-evalution.index');
    }
}
