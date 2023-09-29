<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = "posts";

    protected $guarded =[];


    protected $primaryKey = "post_id";

    public $timestamps=true ;

    public function postType(): BelongsTo
    {

        return $this->belongsTo(PostType::class,'type_id');
    }

    public function post_destination() : HasMany
    {
        return $this->hasMany(PostDestination::class,'post_id');

    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class,'post_id');}

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class,'post_id');
    }

    public $withCount = ['comments' , 'likes'];


}
