<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherKin extends Model
{
    protected $table = "teacher_kin";
    protected $primaryKey = "teacher_kin_id";
    public $timestamps = true;

    protected $fillable = [
        'teacher_kin_name',
        'teacher_kin_relation',
        'kin_address',
        'kin_suburb',
        'kin_state',
        'kin_post_code',
        'teacher_work',
        'teacher_kin_mobile_no',
        'teacher_id'
        ];


        public function teacher()
        {
            return $this->belongsTo(TeacherModel::class, 'teacher_id');
        }
}
