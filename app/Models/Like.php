<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = "likes";

    protected $fillable = [
        'user_id','post_id' ];


    protected $primaryKey = "id";

    public $timestamps=true ;

    public function user(){
        return $this->belongsTo(User::class,'user_id');}

    public function post(){
        return $this->belongsTo(Post::class,'post_id');
    }
}
