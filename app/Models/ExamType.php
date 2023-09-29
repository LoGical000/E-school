<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamType extends Model
{
    use HasFactory;

    protected $table = "exams_type";

    protected $guarded =[];


    protected $primaryKey = "type_id";

    public $timestamps=true ;

    public function exam(): HasMany
    {
        return $this->hasMany(Exam::class,'type_id');
    }

    public function ExamSchedule_ExamType() : HasMany
    {
        return $this->HasMany(ExamSchedule_ExamType::class);
    }



}
