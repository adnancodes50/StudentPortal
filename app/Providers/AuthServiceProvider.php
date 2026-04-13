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

        /*
        |--------------------------------------------------------------------------
        | ROLE GATES
        |--------------------------------------------------------------------------
        */

        // ✅ Admin Gate
        Gate::define('isAdmin', function ($user) {
            return $user->type === 'admin';
        });

        // ✅ User Gate (optional)
        Gate::define('isUser', function ($user) {
            return $user->type === 'user';
        });

        /*
        |--------------------------------------------------------------------------
        | EXISTING GATES
        |--------------------------------------------------------------------------
        */

        // Course Evaluation
        // Gate::define('view-course-evaluation', function ($user) {
        //     return !DB::table('course_evaluation_reports')
        //         ->where('student_id', $user->id)
        //         ->exists();
        // });

        // Teacher Evaluation
        // Gate::define('view-teacher-evaluation', function ($user) {
        //     return !DB::table('teacher_evaluation_reports')
        //         ->where('student_id', $user->id)
        //         ->exists();
        // });
    }
}
