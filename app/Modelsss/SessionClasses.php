<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionClasses extends Model
{
    protected $table = 'session_classes';
    protected $primaryKey = 'session_classes_id';
    public $incrementing = true;
protected $keyType = 'int';// Change this to match your actual primary key column
    public $timestamps = true;
    protected $fillable = [
        'session_id',
        'class_id',
        'section',
        'degree_id',
        'created_by',
        'updated_by'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function course() {
    return $this->belongsTo(CoursesModel::class, 'course_id');
}

    public function degree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
