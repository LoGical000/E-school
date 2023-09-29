<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    use ApiResponseTrait;

    public function calcResForGrade(Request $request){
        $request->validate([
            'schoolyear'=>['required','string'],
            'grade_id'=>['required','integer']
        ]);
        $grade_id=$request->grade_id;

//       Calc each subject for each student
//        $students = DB::table('students')
//                  ->where('students.grade_id','=',$grade_id)
//                  ->where('students.status','=','active')
//                  ->join('exams','students.student_id','=','exams.student_id')
//                  ->join('subjects','subjects.subject_id','=','exams.subject_id')
//                  ->select('students.student_id','subjects.name','students.first_name','students.last_name', DB::raw('AVG(exams.mark) as average'))
//                  ->groupBy('students.student_id','subjects.name','students.first_name','students.last_name')
//                  ->get();


        $students = DB::table('students')
            ->where('students.grade_id','=',$grade_id)
            ->where('students.status','=','active')
            ->join('exams','students.student_id','=','exams.student_id')
            ->where('exams.schoolyear','=',$request->schoolyear)
            ->join('subjects','subjects.subject_id','=','exams.subject_id')
            ->select('students.student_id','students.first_name','exams.schoolyear','students.last_name', DB::raw('SUM(exams.mark)/4 as average'))
            ->groupBy('students.student_id','students.first_name','students.last_name','exams.schoolyear')
            ->get();


        foreach ($students as $student) {
            Result::updateOrCreate(
                ['student_id' => $student->student_id, 'schoolyear' => $student->schoolyear],
                ['result' => $student->average, 'grade_id' => $grade_id]
            );
        }


        return $this->apiResponse('success',$students);

    }

    public function upgradeStudents(Request $request){
        $request->validate([
            'schoolyear'=>['required','string'],
            'grade_id'=>['required','integer']
        ]);

        $edge = $this->calcEdge();


        $ids = DB::table('results')
            ->where('grade_id', '=', $request->grade_id)
            ->where('schoolyear', '=', $request->schoolyear)
            ->where('result', '>=', $edge)
            ->pluck('student_id');



        $students = Student::whereIn('student_id', $ids)->get();

        foreach($students as $student){
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

            if($student->grade_id >= 9)
                $student->status = 'graduated';
            else $student->grade_id = $student->grade_id + 1;
            $student->save();
        }



        return $this->apiResponse('success',$students);

    }

    public function showResultStudent($student_id){
        $res = DB::table('results')
            ->where('results.student_id',$student_id)
            ->join('students','students.student_id','=','results.student_id')
            ->select('students.student_id','students.first_name','students.last_name','results.*')
            ->get();

        return $this->apiResponse('success',$res);


    }

    public function showStudents(Request $request){
        $request->validate([
            'grade_id'=>['required','integer'],
            'schoolyear'=>['required','string']
        ]);

        $students = DB::table('results')
            ->where('results.grade_id',$request->grade_id)
            ->where('results.schoolyear',$request->schoolyear)
            ->join('students','students.student_id','=','results.student_id')
            ->select('students.student_id','students.first_name','students.last_name','results.*')
            ->orderByDesc('results.result')
            ->get();

        return $this->apiResponse('s',$students);


    }

    public function calcEdge(){

        $subjects_sum=DB::table('subjects')->sum('max_mark');

        $subjects_count = DB::table('subjects')->count() - 1;

        $edge= ($subjects_sum / 2) - 200 ;

        return $edge;
    }
}
