<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $table = "books";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

//    public function grade() : BelongsTo
//    {
//        return $this->belongsTo(Grade::class,'grade_id');
//    }
}
