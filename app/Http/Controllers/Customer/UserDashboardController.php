<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserDashboardController extends Controller
{
    public function index(): Response
    {
        return response()->noContent();
    }
}

