<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostDestination;
use App\Models\Student;
use App\Models\Student_classroom;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\Attendance_Notification;
use App\Notifications\PostsNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function createForStudent(Request $request){

        $data = $request->validate([
            'body'=>['required'],
            //'type_id'=>['required'],
            'student_id'=>['required']
        ]);
        $creator_id=Auth::id();

        $student=Student::find($request->student_id);
        if(!$student)
            return $this->apiResponse('Student not found',null,false);
        $user_id=$student->user_id;


            $currentDateTimeString = Carbon::now()->format('d F Y');;

            $post=Post::create([
                'body'=>$request->body,
                //'type_id'=>$request->type_id,
                'date'=>$currentDateTimeString,
                'user_id'=>$creator_id
            ]);


            $postDestenation=PostDestination::create([
                'user_id'=>$user_id,
                'post_id'=>$post->post_id
            ]);

        Notification::send($student, new PostsNotification(
                $student-> first_name,
                $student-> last_name,
                $request-> body,
                $request-> date

            )
        );

        return $this->apiResponse('success',$post);





    }

    public function createForClassroom(Request $request){

        $request->validate([
            'body'=>['required'],
            //'type_id'=>['required'],
            'classroom_id'=>['required']
        ]);

        $creator_id=Auth::id();


            $currentDateTimeString = Carbon::now()->format('d F Y');;

            $post=Post::create([
                'body'=>$request->body,
                //'type_id'=>$request->type_id,
                'date'=>$currentDateTimeString,
                'user_id'=>$creator_id
            ]);


           $user_ids= DB::table('students_classrooms')
                ->join('students','students.student_id','=','students_classrooms.student_id')
                ->where('students_classrooms.classroom_id','=',$request->classroom_id)
                ->pluck('students.user_id');


            foreach($user_ids as $id){
                $data = [
                    ['user_id'=>$id, 'post_id'=>$post->post_id],

                ];
                $res=PostDestination::insert($data);
            }

            return $this->apiResponse('success',$post);

    }

    public function createForGrade(Request $request){
        $request->validate([
            'body'=>['required'],
            //'type_id'=>['required'],
            'grade_id'=>['required']
        ]);

        $creator_id=Auth::id();

            $currentDateTimeString = Carbon::now()->format('d F Y');;

            $post=Post::create([
                'body'=>$request->body,
                //'type_id'=>$request->type_id,
                'date'=>$currentDateTimeString,
                'user_id'=>$creator_id
            ]);



        $students = DB::table('students')
            ->where('grade_id','=',$request->grade_id)
            ->pluck('user_id');


            foreach($students as $id){
                $data = [
                    ['user_id'=>$id, 'post_id'=>$post->post_id],

                ];
                $res=PostDestination::insert($data);
            }

            return $this->apiResponse('success',$post);

    }

    public function createForSchool(Request $request){
        $request->validate([
            'body'=>['required'],
            //'type_id'=>['required'],
        ]);

        $creator_id=Auth::id();

            $currentDateTimeString = Carbon::now()->format('d F Y');;

            $post=Post::create([
                'body'=>$request->body,
                //'type_id'=>$request->type_id,
                'date'=>$currentDateTimeString,
                'user_id'=>$creator_id
            ]);

             $user_ids = DB::table('users')
              ->pluck('id');

              //return $this->apiResponse('s',$users);



            foreach($user_ids as $id){
                $data = [
                    ['user_id'=>$id, 'post_id'=>$post->post_id],

                ];
                $res=PostDestination::insert($data);
            }

            return $this->apiResponse('success',$post);


    }

    public function getPosts(Request $request){
//        $request->validate([
//            'type_id'=>['required','integer']
//        ]);
        $user=Auth::user();


        $posts = DB::table('posts_destination')
            ->join('posts', 'posts.post_id', '=', 'posts_destination.post_id')
            ->join('users','users.id','=','posts.user_id')
            ->where('posts_destination.user_id','=', $user->id)
            ->select('posts.post_id','posts.body','posts.date','posts.updated_at','posts.user_id','role')
            ->orderByDesc('posts.post_id')
            ->get();
            //->pluck('body');

        $res=array();

        foreach($posts as $post) {
            $post->likes_count=$this->likes($post->post_id);
            $post->coments_count=$this->comments($post->post_id);
            $post->is_liked=$this->isLiked($post->post_id,$user->id);
            if ($post->role == 4)
            {
                $teacher=DB::table('teachers')
                ->where('user_id','=',$post->user_id)
                ->first();
                $post->publisher=$teacher->first_name . " " . $teacher->last_name;
                array_push($res,$post);
            }
            else{
                $post->publisher='E-School';
                array_push($res,$post);

            }
        }



        return $this->apiResponse('success',$res);



    }

    public function deletePost($post_id){

        $user=Auth::user();

        if($user->role==4){
            $post = Post::find($post_id);
            if($post && $post->user_id == $user->id){
                $res=DB::table('posts')
                    ->where('post_id','=',$post_id)
                    ->delete();
                return $this->apiResponse('Deleted',$res);
            }
                else{
                    return $this->apiResponse('not possible',null,false);}

        }

        try {
            $res=DB::table('posts')
                ->where('post_id','=',$post_id)
                ->delete();
            return $this->apiResponse('Deleted',$res);

        }catch (\Exception $e){
            return $this->apiResponse('not possible');
        }

    }

    public function updatePost(Request $request,$post_id){
        $data = $request->validate([
            'body'=>['required'],
        ]);
        $user = Auth::user();
        $post = Post::find($post_id);
        if($post && $post->user_id == $user->id){
            $post->update($data);
            return $this->apiResponse('success',$post);

        }
            return $this->apiResponse('not possible',null , false);




    }

    public function getAllPosts(){
        $user=Auth::user();


        $posts = DB::table('posts')
            ->join('users','users.id','=','posts.user_id')
            ->select('posts.post_id','posts.body','posts.date','posts.updated_at','posts.user_id','role')
            ->orderByDesc('posts.post_id')
            ->get();



        foreach($posts as $post) {
            $is_mine=false;
            if($post->user_id==$user->id)
                $is_mine=true;
            $post->is_mine=$is_mine;
            $post->likes_count=$this->likes($post->post_id);
            $post->coments_count=$this->comments($post->post_id);
            $post->is_liked=$this->isLiked($post->post_id,$user->id);
            if ($post->role == 4) {
                $teacher=DB::table('teachers')
                    ->where('user_id','=',$post->user_id)
                    ->first();
                $post->publisher=$teacher->first_name . " " . $teacher->last_name;

            }
            else{
                $post->publisher='E-School';
            }
        }



        return $this->apiResponse('success',$posts);

    }

    public function  likes($post_id){
        $post = Post::find($post_id);
        $likes = $post->likes()->get();
        return count($likes);
    }

    public function comments($post_id){
        $post = Post::find($post_id);
        $comments = $post->comments;
        return count($comments);
    }

    public function isLiked($post_id,$user_id){
        $post = Post::find($post_id);
        if($post->likes()->where('user_id',$user_id)->exists())
            return true;
        return false;
    }

    public function showParent($student_id){
        $user_id=DB::table('students')
            ->where('student_id',$student_id)
            ->first()->user_id;


        $user=User::find($user_id);

        $pu=Auth::id();


            $posts = DB::table('posts_destination')
                ->join('posts', 'posts.post_id', '=', 'posts_destination.post_id')
                ->join('users','users.id','=','posts.user_id')
                ->where('posts_destination.user_id','=', $user->id)
                ->select('posts.post_id','posts.body','posts.date','posts.updated_at','posts.user_id','role')
                ->orderByDesc('posts.post_id')
                ->get();
            //->pluck('body');



            foreach($posts as $post) {
                $post->likes_count=$this->likes($post->post_id);
                $post->coments_count=$this->comments($post->post_id);
                $post->is_liked=$this->isLiked($post->post_id,$pu);
                if ($post->role == 4) {
                    $teacher=DB::table('teachers')
                        ->where('user_id','=',$post->user_id)
                        ->first();
                    $post->publisher=$teacher->first_name . " " . $teacher->last_name;

                }
                else $post->publisher='E-School';

            }


            return $this->apiResponse('success',$posts);


    }

}
