<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSchedule_ExamType extends Model
{
    use HasFactory;

    protected $table = "exam_schedule__exam_type_pivot";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;


    public function examType() : BelongsToMany
    {
        return $this->BelongsToMany(ExamType::class);
    }

    public function ExamSchedule() : BelongsToMany
    {
        return $this->BelongsToMany(ExamSchedule::class);
    }


}
