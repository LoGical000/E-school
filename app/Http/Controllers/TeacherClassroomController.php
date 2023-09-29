<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherClassroomController extends Controller
{

    use ApiResponseTrait;

    public function index()
    {
        $teachers =DB::table('teachers_classrooms')
            ->select('teacher_id', DB::raw('GROUP_CONCAT(classrooms.room_number) as classroom_names'))
            ->join('classrooms', 'teachers_classrooms.classroom_id', '=', 'classrooms.classroom_id')
            ->groupBy('teacher_id')
            ->get();

        return $this->apiResponse('success',$teachers);
    }




}
