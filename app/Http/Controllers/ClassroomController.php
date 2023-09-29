<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    use ApiResponseTrait;


    public function index()
    {
        $classrooms = Classroom::all();
        return $this->apiResponse('success',$classrooms);
    }



    public function store(Request $request){
        $data=$request->validate([
            'room_number'=>['required','string'],
            'capacity'=>['required','integer'],
            'grade_id'=>['required','integer'],
        ]);

        $exists = DB::table('classrooms')
            ->where('room_number', $request->room_number)
            ->where('grade_id', $request->grade_id)
            ->exists();

        if($exists)
            return $this->apiResponse('The classroom for this grade already exist',null,false);

        $classroom=Classroom::create($data);
        return $this->apiResponse('success',$classroom);
    }


    public function showByGrade($grade_id){
       $classrooms= DB::table('classrooms')
            ->where('grade_id','=',$grade_id)
            ->get();

      if($classrooms->isEmpty())
       return $this->apiResponse('classrooms not found',null,false);
           return $this->apiResponse('success',$classrooms);
    }
}
