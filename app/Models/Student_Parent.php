<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student_Parent extends Model
{
    use HasFactory;

    protected $table = "students_parents";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;


    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Parent::class,'parent_id');
    }
}
