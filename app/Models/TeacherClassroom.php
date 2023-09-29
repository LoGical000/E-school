<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherClassroom extends Model
{
    use HasFactory;

    protected $table = "teachers_classrooms";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

    public function teacher() : BelongsTo
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function classroom() : BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');
    }
}
