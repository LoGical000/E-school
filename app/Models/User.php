<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
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
    ];


    public function student() : HasMany
    {
        return $this->hasMany(Student::class);

    }

    public function teacher() : HasMany
    {
        return $this->hasMany(Teacher::class);

    }

    public function parent() : HasMany
    {
        return $this->hasMany(Parentt::class);

    }

    public function owner() : HasMany
    {
        return $this->hasMany(Owner::class);

    }

    public function admin() : HasMany
    {
        return $this->hasMany(Admin::class);

    }

    public function posts() : HasMany
    {
        return $this->hasMany(PostDestination::class);

    }
}
