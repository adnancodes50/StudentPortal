<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function createdClassCourses()
{
    return $this->hasMany(ClassesCourses::class, 'created_by');
}

public function updatedClassCourses()
{
    return $this->hasMany(ClassesCourses::class, 'updated_by');
}

public function createdAttendances()
{
    return $this->hasMany(AttendanceModel::class, 'created_by');
}

public function updatedAttendances()
{
    return $this->hasMany(AttendanceModel::class, 'updated_by');
}


public function createdTimeTables()
{
    return $this->hasMany(\App\Models\TimeTableMOdel::class, 'created_by', 'id');
}

}
