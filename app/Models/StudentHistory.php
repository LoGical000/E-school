<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHistory extends Model
{
    use HasFactory;

    protected $table = "students_history";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;
}
