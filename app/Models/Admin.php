<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'first_name',
        'last_name',
        'user_id'
    ];

    protected $hidden = [
       'password',
//        'remember_token',
    ];



    protected $table = "admins";



    protected $primaryKey = "id";

    public $timestamps=true ;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');

    }





}
