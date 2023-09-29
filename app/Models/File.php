<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    use HasFactory;

    protected $table = "files";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

    public function classrooom() : BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');

    }
}
