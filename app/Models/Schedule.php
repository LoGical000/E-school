<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $table = "schedules";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;


    public function classroom() : BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');

    }

    public function firstSubject()
    {
        return $this->belongsTo(Subject::class, 'first_subject', 'subject_id');
    }

    public function secondSubject()
    {
        return $this->belongsTo(Subject::class, 'second_subject', 'subject_id');
    }

    public function thirdSubject()
    {
        return $this->belongsTo(Subject::class, 'third_subject', 'subject_id');
    }

    public function fourthSubject()
    {
        return $this->belongsTo(Subject::class, 'fourth_subject', 'subject_id');
    }

    public function fifthSubject()
    {
        return $this->belongsTo(Subject::class, 'fifth_subject', 'subject_id');
    }

    public function sixthSubject()
    {
        return $this->belongsTo(Subject::class, 'sixth_subject', 'subject_id');
    }


}
