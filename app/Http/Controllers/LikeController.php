<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    use ApiResponseTrait;
    public function index(Request $request ,$post_id)
    {
        $post = Post::find($post_id);
        $likes = $post->likes()->get();

        foreach ($likes as $like){
            $user = User::find($like->user_id);
            $role = $user->role;
            $like->role=$role;
            if($role == 0){
                $like->publisher = "E-School";

            }

            if($role == 1){
                $like->publisher = "E-School";

            }

            if($role == 2){
                $student = DB::table('students')->where('user_id','=',$user->id)->first();
                $like->publisher = $student->first_name . " " . $student->last_name;

            }

            if($role == 3){
                $parent = DB::table('parents')->where('user_id','=',$user->id)->first();
                $like->publisher = $parent->father_first_name . " " . $parent->father_last_name;

            }

            if($role == 4){
                $teacher = DB::table('teachers')->where('user_id','=',$user->id)->first();
                $like->publisher = $teacher->first_name . " " . $teacher->last_name;

            }

        }
        return $this->apiResponse('success',$likes);
    }

    public function store(Request $request ,$post_id)

    {
        $post = Post::find($post_id);
        if($post->likes()->where('user_id',Auth::id())->exists())
        {
            $post->likes()->where('user_id',Auth::id())->delete();
            return $this->apiResponse('unliked',count($post->likes));
        }
        else
        {

            $post->likes()->create(['user_id'=>Auth::id()]);
            return $this->apiResponse('liked',count($post->likes));
        }


    }
}
