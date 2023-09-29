<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = "tokens";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;
}
