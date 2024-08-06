<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    CONST ROLE = [
        'super_admin' => 'Super Admin',
        'artist_manager' => 'Artist Manager',
        'artist' => 'Artist'
    ];

    const GENDER = [
        'm' => 'Male',
        'f' => 'Female',
        'o' => 'Other',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'dob',
        'gender',
        'role',
        'address'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed',
    ];
    public static function boot()
    {
        parent::boot();
    }
}
