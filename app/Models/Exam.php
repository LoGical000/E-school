<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $table = "exams";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id');

    }

    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subject::class,'subject_id');

    }

    public function examType() : BelongsTo
    {
        return $this->belongsTo(ExamType::class,'type_id');

    }
}
