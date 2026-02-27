<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\StudentController::class, 'index'])->name('home');
Route::get('/admin/student-courses', [App\Http\Controllers\StudentController::class, 'viewcources'])->name('student.courses');
Route::get('/timetable', [App\Http\Controllers\StudentController::class, 'timetable'])->name('student.timetable');
Route::get('/attendence', [App\Http\Controllers\StudentController::class, 'attendence'])->name('student.attendence');
