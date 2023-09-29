<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $table = "exam_schedules";

    protected $guarded =[];


    protected $primaryKey = "exam_schedules_id";

    public $timestamps=true ;


    public function ExamSchedule_ExamType() : HasMany
    {
        return $this->HasMany(ExamSchedule_ExamType::class);
    }

}
