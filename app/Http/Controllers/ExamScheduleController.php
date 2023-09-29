<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\ExamSchedule_ExamType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{

    use  ApiResponseTrait;

    public function index()
    {

        $exam_schedule = ExamSchedule_ExamType::query()
            ->join('exam_schedules' , 'exam_schedules.exam_schedule_id' , '=' ,
            'exam_schedule__exam_type_pivot.exam_schedule_id')
            ->orderBy('exam_schedules.exam_schedule_id','DESC')
            ->get();

        if($exam_schedule->isEmpty())
            return $this->apiResponse('schedule is not found',null,false);

        return $this->apiResponse('success',$exam_schedule);
    }

    public function create(Request $request)
    {
        $request->validate([
            'grade_id'=>['required','integer'],
            'School_Year'=>['required','integer'],
            'type_id'=>['required','integer'],
            'image'=> ['required', 'image','mimes:jpeg,png,bmp,jpg'],
        ]);

            $file_extintion = $request->file('image') ->getClientOriginalExtension();
            $file_name = time().'.'.$file_extintion;
            $path = 'ExamScheduleImages';
            $request ->file('image')-> move($path,$file_name);


        $insertedData = ExamSchedule::create([
            'grade_id' => $request->grade_id,
            'School_Year'=>$request->School_Year,
            'image' => $file_name,
        ]);

        $exam_schedule = ExamSchedule::query()
            ->orderBy('created_at','DESC')
            ->first();


        $insertedData2 = ExamSchedule_ExamType::create([
            'exam_schedule_id' => $exam_schedule->exam_schedule_id,
            'type_id'=>$request->type_id,
        ]);

        $exam_type =  DB::table('exams_type')
            ->where('type_id',$request->type_id)
            ->first()->name;

        $exam_schedule->type_name=$exam_type;


        return $this->apiResponse('Schedule uploaded successfully',$exam_schedule);

    }

    public function showByGrade($grade_id)
    {

        $exam_schedule = DB::table('exam_schedules')
            ->where('grade_id','=',$grade_id)
            ->orderBy('created_at', 'DESC')
            ->first();

        if(!$exam_schedule)
            return $this->apiResponse('schedule is not found',null,false);



        $exam_type =  ExamSchedule_ExamType::query()
            ->where('exam_schedule_id','=',$exam_schedule->exam_schedule_id)
            ->first();

        $exam_name =  DB::table('exams_type')
            ->where('type_id',$exam_type->type_id)
            ->first()->name;

        $exam_schedule->type_name=$exam_name;



        return $this->apiResponse('success',$exam_schedule);


    }

    public function showToken()
    {
        $grade_id = DB::table('students')
            ->where('user_id',Auth::id())
            ->first()
            ->grade_id;

        $exam_schedule = DB::table('exam_schedules')
            ->where('grade_id','=',$grade_id)
            ->orderBy('created_at', 'DESC')
            ->first();

        if(!$exam_schedule)
            return $this->apiResponse('schedule is not found',null,false);



        $exam_type =  ExamSchedule_ExamType::query()
            ->where('exam_schedule_id','=',$exam_schedule->exam_schedule_id)
            ->first();

        $exam_name =  DB::table('exams_type')
            ->where('type_id',$exam_type->type_id)
            ->first()->name;

        $exam_schedule->type_name=$exam_name;



        return $this->apiResponse('success',$exam_schedule);


    }


//    public function destroy(ExamSchedule $examSchedule)
//    {
//        //
//    }

}
