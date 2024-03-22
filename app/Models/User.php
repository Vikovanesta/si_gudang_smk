<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function isStudent()
    {
        return $this->role_id === 2;
    }

    public function isTeacher()
    {
        return $this->role_id === 3;
    }

    public function isLaboran()
    {
        return $this->role_id === 4;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
    
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function laboran()
    {
        return $this->hasOne(Laboran::class);
    }

    public function sentRequests()
    {
        return $this->hasMany(Request::class, 'sender_id');
    }

    public function handledRequests()
    {
        return $this->hasMany(Request::class, 'handler_id');
    }
}
