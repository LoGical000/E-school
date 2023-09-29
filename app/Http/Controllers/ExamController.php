<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Notifications\ExamResult;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationController;



class ExamController extends Controller
{
    use ApiResponseTrait,NotificationTrait;

     public function store(Request $request){
         $validatedData=$request->validate([
             'student_id'=>['required','integer'],
             'subject_name'=>['required','string'],
             'type_id'=>['required','integer'],
             'mark'=>['required'],
             'date'=>['required','date','date_format:Y-m-d'],
             'schoolyear'=>['required'],
         ]);
            $subject_id=DB::table('subjects')
                ->where('name','=',$request->subject_name)
                ->first()
                ->subject_id;

         $subject_max_mark=DB::table('subjects')
             ->where('name','=',$request->subject_name)
             ->first()
             ->max_mark;

         if($request->mark>$subject_max_mark)
             return $this->apiResponse('The mark you entered is greater than the max mark',null,false);
//         $dateString = $request->date;
//         $date = Carbon::createFromFormat('Y-m-d', $dateString);
//         $monthNumber = $date->format('m');
         $examExists = DB::table('exams')
             ->where('student_id', $request->student_id)
             ->where('subject_id', $subject_id)
             ->where('type_id','=',$request->type_id)
             ->where('schoolyear','=',$request->schoolyear)
             ->exists();

         if($examExists)
             return $this->apiResponse('The student already have an exam with this subject and type registered',null,false);

         $exam=Exam::create([
             'student_id'=>$request->student_id,
             'subject_id'=>$subject_id,
             'type_id'=>$request->type_id,
             'mark'=>$request->mark,
             'date'=>$request->date,
             'schoolyear'=>$request->schoolyear
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




         $body = $request->subject_name . ' ' .$request->mark;
         $titleCh = 'New Mark';
         $titlePr = 'New Mark for ' . $chiled;

         $this->sendNotification($titlePr,$FCM_parent,$body);
         $this->sendNotification($titleCh,$FCM_child,$body);


         return $this->apiResponse('success',$exam);



     }

     public function showForAdmin(Request $request){
         $request->validate([
             'grade_id'=>['required','integer'],
             'room_number'=>['required','string'],
             'subject_name'=>['required','string'],
             'type_id'=>['required','integer'],
             'schoolyear'=>['required'],
         ]);

         $student_ids=DB::table('students_classrooms')
             ->join('classrooms','students_classrooms.classroom_id','=','classrooms.classroom_id')
             ->where('classrooms.grade_id','=',$request->grade_id)
             ->where('classrooms.room_number','=',$request->room_number)
             ->pluck('students_classrooms.student_id');

         if ($student_ids->isEmpty())
             return $this->apiResponse('No students in the classroom you entered',null,false);

         $subject=DB::table('subjects')
             ->where('name','=',$request->subject_name)
             ->first();

         if(!$subject)
             return $this->apiResponse('subject not found',null,false);




         $marks=DB::table('exams')
             ->whereIn('exams.student_id',$student_ids)
             ->join('students','students.student_id','=','exams.student_id')
             ->join('subjects','subjects.subject_id','=','exams.subject_id')
             ->where('exams.type_id','=',$request->type_id)
             ->where('exams.subject_id','=',$subject->subject_id)
             ->select('exams.*','students.first_name', 'students.last_name','subjects.name','subjects.max_mark')
             ->get();

         if ($marks->isEmpty())
             return $this->apiResponse('No marks for the following classroom',null,false);
         return $this->apiResponse('success',$marks);

     }

     public function showForStudent(Request $request){
         $request->validate([
             'schoolyear'=>['required'],
             'type_id'=>['required','integer'],
         ]);
         $student_id=DB::table('students')
             ->where('user_id','=',Auth::id())
             ->first()->student_id;

         $exams=DB::table('exams')
             ->where('student_id','=',$student_id)
             ->where('schoolyear','=',$request->schoolyear)
             ->where('type_id','=',$request->type_id)
             ->join('subjects','subjects.subject_id','=','exams.subject_id')
             ->select('subjects.name','subjects.max_mark','exams.*')
             ->get();
         if ($exams->isEmpty())
             return $this->apiResponse('Students does not have any marks',null,false);

         return $this->apiResponse('success',$exams);


     }

    public function showForParent(Request $request,$student_id){
        $request->validate([
            'schoolyear'=>['required'],
            'type_id'=>['required','integer'],
        ]);

        $exams=DB::table('exams')
            ->where('student_id','=',$student_id)
            ->where('schoolyear','=',$request->schoolyear)
            ->where('type_id','=',$request->type_id)
            ->join('subjects','subjects.subject_id','=','exams.subject_id')
            ->select('subjects.name','subjects.max_mark','exams.*')
            ->get();
        if ($exams->isEmpty())
            return $this->apiResponse('Students does not have any marks',null,false);

        return $this->apiResponse('success',$exams);


    }

}
