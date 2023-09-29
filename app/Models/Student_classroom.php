<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student_classroom extends Model
{
    use HasFactory;

    protected $table = "students_classrooms";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;


    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id');

    }
    public function classroom() : BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');

    }
}
