<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{//
    use HasFactory;

    protected $table = "attendances";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id');
    }
}
