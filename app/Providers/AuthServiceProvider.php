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

        Gate::define('isAdmin', function ($user) {
            return ($user->role ?? $user->type) === 'admin';
        });

        Gate::define('isAgent', function ($user) {
            return ($user->role ?? $user->type) === 'agent';
        });

        Gate::define('isUser', function ($user) {
            return ($user->role ?? $user->type) === 'user';
        });

        Gate::define('isAdminOrAgent', function ($user) {
            return in_array(($user->role ?? $user->type), ['admin', 'agent'], true);
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
