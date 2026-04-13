<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\DateSheet;
use App\Models\User;
use App\Models\StudentModel;
use App\Models\TeacherClasses;
use App\Models\TimeTableMOdel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class StudentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    $totalusers = count(User::all());

        return view('home', compact('totalusers'));
    }


}
