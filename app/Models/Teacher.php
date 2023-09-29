<?php

namespace App\Models;

use App\Http\Controllers\TeacherClassroomController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'address',
        'details',
        'subject_id',
        'user_id'

    ];



    protected $table = "teachers";

    protected $guarded =[];

    protected $hidden = [
        'password',

    ];


    protected $primaryKey = "teacher_id";

    public $timestamps=true ;


    public function subjects() : BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'teachers_classrooms', 'teacher_id', 'classroom_id');
    }

    public function TeacherSubject() : HasMany
    {
        return $this->hasMany(TeacherSubjectController::class,'teacher_id');
    }

    public function teacher_classroom() : HasMany
    {
        return $this->hasMany(TeacherClassroomController::class,'teacher_id');

    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');

    }
}
