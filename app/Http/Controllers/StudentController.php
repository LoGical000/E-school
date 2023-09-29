<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentHistory;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {

        $students = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('students_classrooms', 'students.student_id', '=', 'students_classrooms.student_id')
            ->leftJoin('classrooms', 'students_classrooms.classroom_id', '=', 'classrooms.classroom_id')
            ->select('students.*', 'users.email', 'classrooms.room_number')
            ->get();
        return $this->apiResponse('success', $students);
    }


    public function show($student_id)
    {


        $student = DB::table('students')
            ->join('parents', 'students.parent_id', '=', 'parents.parent_id')
            ->where('students.student_id','=',$student_id)
            ->select('students.*', 'parents.*')
            ->first();

        if(!$student)
            return $this->apiResponse('student not found',null,false);



        return $this->apiResponse('success', $student);
    }


    public function update(Request $request,$student_id)
    {

        $data=$request->all();
        $student=Student::find($student_id);

        if(!$student)
         return $this->apiResponse('Student not found',null,false);

        $room_number='not assigned';
        $room = DB::table('students_classrooms')
            ->where('student_id',$student->student_id)
            ->join('classrooms','classrooms.classroom_id','=','students_classrooms.classroom_id')
            ->exists();
        if($room){
            $room_number = DB::table('students_classrooms')
                ->where('student_id',$student->student_id)
                ->join('classrooms','classrooms.classroom_id','=','students_classrooms.classroom_id')
                ->first()->room_number;

        }

        $student_history = StudentHistory::create([
            'student_id'=>$student->student_id,
            'address'=>$student->address,
            'details'=>$student->details,
            'grade_id'=>$student->grade_id,
            'status'=>$student->status,
            'room_number'=>$room_number
        ]);

        $student->update($data);



        return $this->apiResponse('success',$student);


    }


    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student)
            return $this->apiResponse('Student not found',null,false);

        $student->delete();
        return $this->apiResponse('Student deleted successfully', $student);
    }


    public function searchByName(Request $request)
    {
        $name = $request->name;


        $students = DB::table('students')
            ->where('students.first_name', 'LIKE', "$name%")
            ->orWhere('students.last_name', 'LIKE', "$name%")
            ->get();
        return $this->apiResponse('success',$students);


    }


    public function showByGrade($grade_id) {
        $students = DB::table('students')
            ->where('students.grade_id', '=', $grade_id)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('students_classrooms', 'students.student_id', '=', 'students_classrooms.student_id')
            ->leftJoin('classrooms', 'students_classrooms.classroom_id', '=', 'classrooms.classroom_id')
            ->select('students.*', 'users.email', 'classrooms.room_number')
            ->get();

        return $this->apiResponse('success', $students);
    }


    public function showProfile(){
        $user_id=Auth::id();
        $user=Auth::user();



        $student=DB::table('students')
            ->where('user_id','=',$user_id)
            ->first();



        $parents=DB::table('parents')
        ->where('parent_id','=',$student->parent_id)
        ->first();

        $classroom=DB::table('students_classrooms')
            ->where('student_id','=',$student->student_id)
            ->first();
        if(!$classroom)
            $student->classroom='Not assigned';
        else {
            $classroom_id=$classroom->classroom_id;

            $classroom_name=DB::table('classrooms')
                ->where('classroom_id','=',$classroom_id)
                ->first()->room_number;

            $student->classroom=$classroom_name;
        }

        $student->email=$user->email;
        $student->father_first_name=$parents->father_first_name;
        $student->father_phone_number=$parents->father_phone_number;
        $student->mother_first_name=$parents->mother_first_name;
        $student->mother_last_name=$parents->mother_last_name;
        $student->mother_phone_number=$parents->mother_phone_number;





        return $this->apiResponse('success',$student);
    }

    public function showHome(){
        $user_id=Auth::id();
        $student=DB::table('students')
            ->where('user_id','=',$user_id)
            ->first();


        $classroom=DB::table('students_classrooms')
            ->where('student_id','=',$student->student_id)
            ->first();
        if(!$classroom)
            $student->classroom='Not assigned';
        else {
            $classroom_id=$classroom->classroom_id;

            $classroom_name=DB::table('classrooms')
                ->where('classroom_id','=',$classroom_id)
                ->first()->room_number;

            $student->classroom=$classroom_name;
        }

        return $this->apiResponse('Success',$student);
    }

    public function showByClassroom(Request $request){

        $request->validate([
            'room_number'=>['required'],
            'grade_id'=>['required']
        ]);

        $classroom=DB::table('classrooms')
            ->where('grade_id','=',$request->grade_id)
            ->where('room_number','=',$request->room_number)
            ->first();

        if($classroom){
            $classroom_id=$classroom->classroom_id;

            $students_classrooms= DB::table('students_classrooms')
                ->where('classroom_id','=',$classroom_id)
                ->get();

            $students_ids=array();

            foreach ($students_classrooms as $object){
                array_push($students_ids,$object->student_id);
            }

            $students = DB::table('students')
                ->whereIn('students.student_id', $students_ids)
                ->join('users', 'students.user_id', '=', 'users.id')
                ->join('students_classrooms', 'students.student_id', '=', 'students_classrooms.student_id')
                ->join('classrooms', 'students_classrooms.classroom_id', '=', 'classrooms.classroom_id')
                ->select('students.*', 'users.email', 'classrooms.room_number')
                ->get();



            return $this->apiResponse('success',$students);


        }


        return $this->apiResponse('The classroom does not exist',null,false);




    }

}
