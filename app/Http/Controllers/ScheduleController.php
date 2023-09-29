<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student_classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    use ApiResponseTrait;


    function getTeacherIdsFromSubjectIds($subjectIds)
    {
        $subjectIds = array_map('intval', $subjectIds);

        $teacherIds = DB::table('teachers')
            ->whereIn('subject_id', $subjectIds)
            ->pluck('teacher_id')
            ->toArray();

        return $teacherIds;
    }



    public function checkTeacherSubjectScheduleConflict($teacherId, $classroomIds, $dayNumber, $subjectIds)
    {
        $query = DB::table('schedules')
            ->join('teachers_classrooms', 'schedules.classroom_id', '=', 'teachers_classrooms.classroom_id')
            ->whereIn('schedules.classroom_id', $classroomIds)
            ->where('schedules.day_number', $dayNumber);

        foreach ($subjectIds as $index => $subjectId) {
            $column = "first_subject";
            if ($index === 1) {
                $column = "second_subject";
            } elseif ($index === 2) {
                $column = "third_subject";
            } elseif ($index === 3) {
                $column = "fourth_subject";
            } elseif ($index === 4) {
                $column = "fifth_subject";
            } elseif ($index === 5) {
                $column = "sixth_subject";
            } elseif ($index === 6) {
                $column = "seventh_subject";
            }
            $query->where($column, $subjectId);
        }
        $query->where('teachers_classrooms.teacher_id', $teacherId);

        return $query->exists();
    }


    public function create(Request $request){
        $validatedData = $request->validate([
            'day_number' => ['required','integer'],
            'first_subject'=>['required','integer'],
            'second_subject'=>['required','integer'],
            'third_subject'=>['required','integer'],
            'fourth_subject'=>['required','integer'],
            'fifth_subject'=>['required','integer'],
            'sixth_subject'=>['required','integer'],
            'seventh_subject'=>['nullable','integer'],
        ]);
        $request->validate([
            'room_number'=>['required'],
            'grade_id'=>['required','integer'],
        ]);


        $classroom = DB::table('classrooms')
            ->where('room_number','=',$request->room_number)
            ->where('grade_id','=',$request->grade_id)
            ->first();

        if(!$classroom)
            return $this->apiResponse('classroom not found',null,false);

        $classroom_id=$classroom->classroom_id;



        $scheduleExists = DB::table('schedules')
            ->where('classroom_id', $classroom_id)
            ->where('day_number', $request->day_number)
            ->exists();

        if($scheduleExists)
            return $this->apiResponse('A schedule already exists for this classroom and day.',null,false);


        // Fetch the teacher IDs from the subjects
        $subjectIds = array_values(array_slice($validatedData, 1));
        $teacherIds = $this->getTeacherIdsFromSubjectIds($subjectIds);

        // Check for teacher subject schedule conflicts
        foreach ($teacherIds as $teacherId) {
            $classroomIds = DB::table('teachers_classrooms')
                ->where('teacher_id', $teacherId)
                ->pluck('classroom_id')
                ->toArray();
        }


        $teacherSubjectConflict = $this->checkTeacherSubjectScheduleConflict($teacherId, $classroomIds, $request->day_number, $subjectIds);

        if ($teacherSubjectConflict) {
            return $this->apiResponse('The teacher is already teaching one of the assigned subjects at the same time in another classroom.', null, false);
        }

        // Create the schedule
        $validatedData['classroom_id']=$classroom_id;


        $schedule = Schedule::create($validatedData);

        return $this->apiResponse('Created', $schedule);
    }



    public function showClassroomScheduleStudent(Request $request){
        $request->validate([
            //'classroom_id'=>'required',
            'day_number'=>'required',
        ]);
        $user_id=Auth::id();

        $student_id=DB::table('students')
            ->where('user_id','=',$user_id)
            ->first()->student_id;

        $classroom_id=DB::table('students_classrooms')
            ->where('student_id','=',$student_id)
            ->first();

        if(!$classroom_id)
            return $this->apiResponse('student not assigned to a classroom',null,false);

        $classroom_id=DB::table('students_classrooms')
            ->where('student_id','=',$student_id)
            ->first()->classroom_id;



        $data=DB::table('schedules')
            ->where('classroom_id','=',$classroom_id)
            ->where('day_number','=',$request->day_number)
            ->first();

        if(!$data)
            return $this->apiResponse('data not found',null,false);

        $data->first_subject=Subject::find($data->first_subject)->name;
        $data->second_subject=Subject::find($data->second_subject)->name;
        $data->third_subject=Subject::find($data->third_subject)->name;
        $data->fourth_subject=Subject::find($data->fourth_subject)->name;
        $data->fifth_subject=Subject::find($data->fifth_subject)->name;
        $data->sixth_subject=Subject::find($data->sixth_subject)->name;
        $data->seventh_subject=Subject::find($data->seventh_subject)->name;




        return $this->apiResponse('success',$data);



    }


    public function showTeachersSchedule($day_number){
//
        $user_id=Auth::id();

        $teacher=DB::table('teachers')
            ->where('user_id','=',$user_id)
            ->first();



        $data = DB::table('schedules as s')
            ->where('s.day_number', '=', $day_number)
            ->select('s.day_number')
            ->addSelect(DB::raw('
        MAX(CASE WHEN s.first_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `First Period`,
        MAX(CASE WHEN s.second_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Second Period`,
        MAX(CASE WHEN s.third_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Third Period`,
        MAX(CASE WHEN s.fourth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Forth Period`,
        MAX(CASE WHEN s.fifth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Fifth Period`,
        MAX(CASE WHEN s.sixth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Sixth Period`,
        MAX(CASE WHEN s.seventh_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Seventh Period`
    '))
            ->join('classrooms as c', 's.classroom_id', '=', 'c.classroom_id')
            ->join('teachers_classrooms as tc', 'c.classroom_id', '=', 'tc.classroom_id')
            ->where('tc.teacher_id', '=', $teacher->teacher_id)

            ->join('teachers as t', function ($join) use ($teacher) {
                $join
                    ->where('s.first_subject', '=', $teacher->subject_id)
                    ->orWhere('s.second_subject', '=', $teacher->subject_id)
                    ->orWhere('s.third_subject', '=', $teacher->subject_id)
                    ->orWhere('s.fourth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.fifth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.sixth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.seventh_subject', '=', $teacher->subject_id);
            })
            ->where('t.subject_id', '=', $teacher->subject_id)
            ->groupBy('s.day_number', 't.subject_id')
            ->get();



        $schedule = $data->first();






        if(!$data)
            return $this->apiResponse('data not found',null,false);



        return $this->apiResponse('success',$schedule);



    }


    public function showTeachersScheduleForAdmin($id , $day_number){
//

        $teacher=DB::table('teachers')
            ->where('teacher_id','=',$id)
            ->first();


        $data = DB::table('schedules as s')
            ->where('s.day_number', '=', $day_number)
            ->select('s.day_number')
            ->addSelect(DB::raw('
        MAX(CASE WHEN s.first_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `First Period`,
        MAX(CASE WHEN s.second_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Second Period`,
        MAX(CASE WHEN s.third_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Third Period`,
        MAX(CASE WHEN s.fourth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Forth Period`,
        MAX(CASE WHEN s.fifth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Fifth Period`,
        MAX(CASE WHEN s.sixth_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Sixth Period`,
        MAX(CASE WHEN s.seventh_subject = ' . $teacher->subject_id . ' THEN c.room_number END) AS `Seventh Period`
    '))
            ->join('classrooms as c', 's.classroom_id', '=', 'c.classroom_id')
            ->join('teachers_classrooms as tc', 'c.classroom_id', '=', 'tc.classroom_id')
            ->where('tc.teacher_id', '=', $teacher->teacher_id)

            ->join('teachers as t', function ($join) use ($teacher) {
                $join
                    ->where('s.first_subject', '=', $teacher->subject_id)
                    ->orWhere('s.second_subject', '=', $teacher->subject_id)
                    ->orWhere('s.third_subject', '=', $teacher->subject_id)
                    ->orWhere('s.fourth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.fifth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.sixth_subject', '=', $teacher->subject_id)
                    ->orWhere('s.seventh_subject', '=', $teacher->subject_id);
            })
            ->where('t.subject_id', '=', $teacher->subject_id)
            ->groupBy('s.day_number', 't.subject_id')
            ->get();



        $schedule = $data->first();



        if(!$data)
            return $this->apiResponse('data not found',null,false);



        return $this->apiResponse('success',$schedule);



    }


    public function index(){
        $teachers = Schedule::all();
        return $this->apiResponse('success',$teachers);
    }

    public function showClassroomSchedule(Request $request){
        $request->validate([
            'room_number'=>['required'],
            'grade_id'=>['required','integer'],
            'day_number'=>['required','integer'],
        ]);

        $classroom = DB::table('classrooms')
            ->where('room_number','=',$request->room_number)
            ->where('grade_id','=',$request->grade_id)
            ->first();

        if(!$classroom)
            return $this->apiResponse('classroom not found',null,false);

        $classroom_id=$classroom->classroom_id;



        $data=DB::table('schedules')
            ->where('classroom_id','=',$classroom_id)
            ->where('day_number','=',$request->day_number)
            ->first();

        if(!$data)
            return $this->apiResponse('data not found',null,false);

        $data->first_subject=Subject::find($data->first_subject)->name;
        $data->second_subject=Subject::find($data->second_subject)->name;
        $data->third_subject=Subject::find($data->third_subject)->name;
        $data->fourth_subject=Subject::find($data->fourth_subject)->name;
        $data->fifth_subject=Subject::find($data->fifth_subject)->name;
        $data->sixth_subject=Subject::find($data->sixth_subject)->name;
        $data->seventh_subject=Subject::find($data->seventh_subject)->name;




        return $this->apiResponse('success',$data);
    }

    public function showClassroomScheduleParent(Request $request,$student_id){
        $request->validate([
            //'classroom_id'=>'required',
            'day_number'=>'required',
        ]);



        $classroom_id=DB::table('students_classrooms')
            ->where('student_id','=',$student_id)
            ->first();

        if(!$classroom_id)
            return $this->apiResponse('student not assigned to a classroom',null,false);

        $classroom_id=DB::table('students_classrooms')
            ->where('student_id','=',$student_id)
            ->first()->classroom_id;



        $data=DB::table('schedules')
            ->where('classroom_id','=',$classroom_id)
            ->where('day_number','=',$request->day_number)
            ->first();

        if(!$data)
            return $this->apiResponse('data not found',null,false);

        $data->first_subject=Subject::find($data->first_subject)->name;
        $data->second_subject=Subject::find($data->second_subject)->name;
        $data->third_subject=Subject::find($data->third_subject)->name;
        $data->fourth_subject=Subject::find($data->fourth_subject)->name;
        $data->fifth_subject=Subject::find($data->fifth_subject)->name;
        $data->sixth_subject=Subject::find($data->sixth_subject)->name;
        $data->seventh_subject=Subject::find($data->seventh_subject)->name;




        return $this->apiResponse('success',$data);



    }
}
