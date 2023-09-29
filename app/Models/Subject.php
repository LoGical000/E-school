<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{

    //ghassan is your uncle
    use HasFactory;

    protected $table = "subjects";

    protected $guarded =[];


    protected $primaryKey = "subject_id";

    public $timestamps=true ;


    public function teachers() : HasMany
    {
        return $this->hasMany(Teacher::class,'subject_id');
    }
    public function TeacherSubject() : HasMany
    {
        return $this->hasMany(TeacherSubjectController::class,'subject_id');
    }

    public function schedule() : HasMany
    {
        return $this->hasMany(Schedule::class,'subject_id');
    }
}
