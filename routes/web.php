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
Route::get('/date-sheet', [App\Http\Controllers\StudentController::class, 'datesheet'])->name('student.datesheet');
Route::get('/date-sheet/data', [App\Http\Controllers\StudentController::class, 'datesheetData'])->name('student.datesheet.data');
Route::get('/student/profile', [App\Http\Controllers\StudentController::class, 'profile'])->name('student.profile');
Route::post('/student/profile/reset-credentials', [App\Http\Controllers\StudentController::class, 'resetCredentials'])->name('student.profile.reset');
