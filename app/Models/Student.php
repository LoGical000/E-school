<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'first_name',
        'last_name',
        'religion',
        'email',
        'password',
        'date_of_birth',
        'address',
        //'details',
        'grade_id',
        'gender_id',
        'parent_id',
        'user_id',
        'status',

    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];



    protected $table = "students";

    protected $guarded =[];


    protected $primaryKey = "student_id";

    public $timestamps=true ;

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.Student.'.$this->id;
    }

    public function attendace() : HasMany
    {
        return $this->hasMany(Attendance::class,'student_id');

    }

    public function student_parents() : HasMany
    {
        return $this->hasMany(Student_Parent::class,'student_id');

    }



    public function student_classroom() : HasMany
    {
        return $this->hasMany(Student_classroom::class,'student_id');

    }

    public function post_destination() : HasMany
    {
        return $this->hasMany(PostDestination::class,'student_id');

    }

    public function exams() : HasMany
    {
        return $this->hasMany(Exam::class,'student_id');

    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');

    }

    public function notice() : HasMany
    {
        return $this->hasMany(Notice::class,'Student_id');

    }

    public function parents() : BelongsTo
    {
        return $this->belongsTo(Parentt::class,'parent_id');

    }


}


