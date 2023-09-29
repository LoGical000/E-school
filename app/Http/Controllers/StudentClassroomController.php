<?php

namespace App\Http\Controllers;

use App\Models\Student_classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentClassroomController extends Controller
{
    use ApiResponseTrait;


    public function create(Request $request){
        $exists = DB::table('students_classrooms')
            //->where('classroom_id', $request->classroom_id)
            ->where('student_id', $request->student_id)
            ->exists();

        if($exists)
            return $this->apiResponse('The student already assigned for a classroom',null,false);

        $res=Student_classroom::create([
            'student_id'=>$request->student_id,
            'classroom_id'=>$request->classroom_id,
        ]);



        return $this->apiResponse('s',$res);
    }

    public function clearClassrooms(){
        DB::table('students_classrooms')->truncate();

        return $this->apiResponse('Cleared');
    }

    public function update(Request $request){
        $request->validate([
            'student_id'=>['required','integer'],
            'classroom_id'=>['required','integer']
        ]);
        $res=DB::table('students_classrooms')
            ->where('student_id',$request->student_id)
            ->update(['classroom_id'=>$request->classroom_id]);
        if($res==0)
            return $this->apiResponse('failed',null,false);

        return $this->apiResponse('success',$res);
    }
}
