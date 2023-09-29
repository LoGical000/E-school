<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentHistoryController extends Controller

{
    use ApiResponseTrait;

    public function showStudentHistory($student_id){
        $student = DB::table('students_history')
            ->where('student_id',$student_id)
            ->get();
        return $this->apiResponse('success',$student);
    }

}
