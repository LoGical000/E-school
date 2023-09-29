<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSubjectController extends Model
{
    use HasFactory;

    protected $table = "teachers_subjects";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;


    public function teacher() : BelongsTo
    {
        return $this->belongsTo(Teacher::class,'teacher_id');

    }
    public function subject() : BelongsTo
    {
        return $this->belongsTo(Subject::class,'Subject_id');

    }
}
