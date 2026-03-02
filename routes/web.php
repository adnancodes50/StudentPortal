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
Route::get('/course-evaluation', [App\Http\Controllers\CourcesEvalutionController::class, 'index'])->name('student.course-evaluation');
Route::post('/course-evaluation/submit', [App\Http\Controllers\CourcesEvalutionController::class, 'submit'])->name('student.course-evaluation.submit');
Route::get('/teacher-evaluation', [App\Http\Controllers\TeacherEvalutionController::class, 'index'])->name('student.teacher-evaluation');
Route::post('/teacher-evaluation/submit', [App\Http\Controllers\TeacherEvalutionController::class, 'submit'])->name('student.teacher-evaluation.submit');
// Course Evaluation Routes
Route::post('student/course-evaluation/check-submitted', [CourcesEvalutionController::class, 'checkSubmitted'])
    ->name('student.course-evaluation.check-submitted');
Route::post('student/course-evaluation/submit', [CourcesEvalutionController::class, 'submit'])
    ->name('student.course-evaluation.submit');

// Teacher Evaluation Routes
Route::post('student/teacher-evaluation/check-submitted', [TeacherEvalutionController::class, 'checkSubmitted'])
    ->name('student.teacher-evaluation.check-submitted');
Route::post('student/teacher-evaluation/submit', [TeacherEvalutionController::class, 'submit'])
    ->name('student.teacher-evaluation.submit');
