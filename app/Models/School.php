<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class School extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'address',
        'overview',
        'phone',
    ];


    protected $hidden = [];

    protected $table = "schools";

    protected $guarded =[];


    protected $primaryKey = "id";

    public $timestamps=true ;

}
