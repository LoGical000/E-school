<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Parentt extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'father_first_name',
        'father_last_name',
        'father_phone_number',
        'mother_first_name',
        'mother_last_name',
        'mother_phone_number',
        'email',
        'password',
        'national_id',
        'user_id',
      //  'gender_id',

    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];



    protected $table = "parents";

    protected $guarded =[];


    protected $primaryKey = "parent_id";

    public $timestamps=true ;

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.Parent.'.$this->id;
    }

     public function student_parents() : HasMany
{
    return $this->hasMany(Student_Parent::class,'parent_id');

}
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');

    }
}
