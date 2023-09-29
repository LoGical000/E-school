<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Notice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    use ApiResponseTrait,NotificationTrait;


    public function store(Request $request)
    {
        //
//
        $validatedData = $request->validate([
            'student_id' => 'required',
            'type' => 'required',
            'content' => 'required',
            'date' => 'required|date',
        ]);

        $student_id = $validatedData['student_id'];
        $type = $validatedData['type'];
        $content = $validatedData['content'];
        $date = $validatedData['date'];
        $day = Carbon::createFromFormat('Y-m-d', $date)->format('l');


        $notice=  Notice::query()->Create([
            'student_id' => $student_id,
            'type' =>$type,
            'content' =>$content,
            'date' => $date,
            'day' => $day
        ]);

        $chiled = DB::table('students')
            ->where('student_id',$request->student_id)
            ->value('first_name');

        $FCM_child=DB::table('students')
            ->join('tokens','tokens.user_id','=','students.user_id')
            ->where('students.student_id',$request->student_id)
            ->value('tokens.token');

        $FCM_parent = DB::table('students')
            ->where('students.student_id',$request->student_id)
            ->join('parents','students.parent_id','=','parents.parent_id')
            ->join('tokens','tokens.user_id','=','parents.user_id')
            ->value('tokens.token');

        $body = $content;
        $titleCh = 'Notice';
        $titlePr = 'Notice for ' . $chiled;

        $this->sendNotification($titlePr,$FCM_parent,$body);
        $this->sendNotification($titleCh,$FCM_child,$body);


        return $this->apiResponse('Notice sent successfully', $notice);

    }



    public function showforadmin($id)
    {
        $notice = Notice::query()->where('student_id', '=', $id)->get();

        if ($notice->isEmpty()) {
            return $this->apiResponse('Student dont have any notices', null, false);
        }


        return $this->apiResponse('success', $notice);
    }

    public function showforstudent()
    {
        $student_id = Student::query()->where('user_id' , '=' , Auth::id())->first()->student_id;
        $notice= Notice::query()->where('student_id' , '=' , $student_id)->get();
        if ($notice->isEmpty())
            return $this->apiResponse('You do not have any notices',null,false);

        return $this->apiResponse('success', $notice);
    }


    public function showforparent($id)
    {
        $notice= Notice::query()->where('student_id' , '=' , $id)->get();

        if ($notice->isEmpty())
            return $this->apiResponse('Your son do not have any notices',null,false);

        return $this->apiResponse('success', $notice);
    }
}
