<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'passport_no',
    'address',
    'status',
    'type',
    'role',
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


    public function adminlte_profile_url()
{
    return route('home'); // or profile page if you have one
}

public function adminlte_image()
{
    return 'https://via.placeholder.com/150'; // or your user image path
}

public function adminlte_desc()
{
    return $this->email;
}

public function hasRole(string $role): bool
{
    return ($this->role ?? $this->type) === $role;
}
}
