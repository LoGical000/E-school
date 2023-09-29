<?php

namespace App\Models;

use App\Http\Controllers\TeacherClassroomController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Classroom extends Model
{
    use HasFactory;

    protected $table = "classrooms";

    protected $guarded =[];


    protected $primaryKey = "classroom_id";

    public $timestamps=true ;

//    public function grade() : BelongsTo
//    {
//        return $this->belongsTo(Grade::class,'grade_id');
//    }

    public function student_classroom() : HasMany
    {
        return $this->hasMany(Student_classroom::class,'classroom_id');
    }

    public function schedule() : HasOne
    {
        return $this->hasOne(Schedule::class,'classroom_id');
    }

    public function file() : HasMany
    {
        return $this->hasMany(File::class,'classroom_id');

    }

    public function teacher_classroom() : HasMany
    {
        return $this->hasMany(TeacherClassroomController::class,'classroom_id');

    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teachers_classrooms', 'classroom_id', 'teacher_id');
    }
}
