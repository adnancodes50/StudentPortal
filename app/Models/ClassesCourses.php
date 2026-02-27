<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Session;
use App\Models\CoursesModel;
use App\Models\ClassModel;
use App\Models\Degrees;   // <-- FIXED IMPORT

class ClassesCourses extends Model
{
    protected $table = 'classes_courses';
    protected $primaryKey = 'class_course_id';

    public $timestamps = true;

    protected $fillable = [
        'degree_id',
        'class_id',
        'course_id',
        'session_id',
        'section',
        'created_by',
        'updated_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

   public function course()
{
    return $this->belongsTo(\App\Models\CoursesModel::class, 'course_id', 'course_id');
}


    //Fix degree relationship
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }

    public function dateSheets()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'course_id', 'course_id');
}

}
