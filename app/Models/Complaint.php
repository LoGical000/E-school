<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = "complaints";

    protected $guarded =[];

    protected $primaryKey = "complaint_id";

    public $timestamps=true ;

    public function parentt()
    {
        return $this->belongsTo(Parentt::class, 'parent_id', 'parent_id');
    }
}

