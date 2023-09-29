<?php

namespace App\Http\Controllers;

use App\Events\ParentAttendanceEvent;
use App\Events\StudentAttendanceEvent;
use App\Models\Attendance;
use App\Models\Parentt;
use App\Models\Student;
use App\Notifications\Attendance_Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    //////////
    use ApiResponseTrait,NotificationTrait;
    public function store(Request $request)
    {
        //
        //$student = Student::query()
//
        $validatedData = $request->validate([
            'student_id' => 'required|array',
            'date' => 'required|date'
        ]);


        $student_ids = $validatedData['student_id'];
        $date = $validatedData['date'];
        $day = Carbon::createFromFormat('Y-m-d', $date)->format('l');


        foreach ($student_ids as $student_id) {
            if(DB::table('attendances')
                ->where('student_id', $student_id)
                ->where('date', $date)->
                exists()){
                return $this->apiResponse('you have already entered that this student was absent today');
            } else {
                Attendance::query()->Create([
                    'student_id' => $student_id,
                    'date' => $date,
                    'day' => $day
                ]);
            }

        }

        foreach ($student_ids as $student_id) {

            $chiled = DB::table('students')
                ->where('student_id',$student_id)
                ->value('first_name');

            $FCM_child=DB::table('students')
                ->join('tokens','tokens.user_id','=','students.user_id')
                ->where('students.student_id',$student_id)
                ->value('tokens.token');

            $FCM_parent = DB::table('students')
                ->where('students.student_id',$request->student_id)
                ->join('parents','students.parent_id','=','parents.parent_id')
                ->join('tokens','tokens.user_id','=','parents.user_id')
                ->value('tokens.token');

            $bodyCH = 'You were absent today';
            $bodyPr = $chiled . ' was absent today';
            $title = 'Absent';

            $this->sendNotification($title,$FCM_parent,$bodyPr);
            $this->sendNotification($title,$FCM_child,$bodyCH);





        }

        $attend = Attendance::query()->where('date' , '=' , $date)->get();

        return $this->apiResponse('Attendance created successfully', $attend);



    }




    /*public function showforadmin($id)
    {

        $attend = Attendance::query()->where('student_id' , '=' , $id)->get();


        if (!$attend)
            return $this->apiResponse('Student did not absent',null,false);

       return $this->apiResponse('success', $attend);
    }*/
    public function showforadmin($id)
    {
        $attend = Attendance::query()->where('student_id', '=', $id)->get();

        if ($attend->isEmpty()) {
            return $this->apiResponse('Student did not absent', null, false);
        }


        return $this->apiResponse('success', $attend);
    }

    public function showforstudent()
    {
        $student_id = Student::query()->where('user_id' , '=' , Auth::id())->first()->student_id;
        $attend= Attendance::query()->where('student_id' , '=' , $student_id)->get();
        if ($attend->isEmpty())
            return $this->apiResponse('You did not absent',null,false);

        return $this->apiResponse('success', $attend);
    }


    public function showforparent($id)
    {
        $attend= Attendance::query()->where('student_id' , '=' , $id)->get();

        if ($attend->isEmpty())
            return $this->apiResponse('You did not absent',null,false);

        return $this->apiResponse('success', $attend);
    }





}
