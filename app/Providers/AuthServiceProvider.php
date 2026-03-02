<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        // Gate for course evaluation
       Gate::define('view-course-evaluation', function ($user) {

    return !DB::table('course_evaluation_reports')
        ->where('student_id', $user->id)
        ->exists();
});

Gate::define('view-teacher-evaluation', function ($user) {

    return !DB::table('teacher_evaluation_reports')
        ->where('student_id', $user->id)
        ->exists();
});
    }
}
