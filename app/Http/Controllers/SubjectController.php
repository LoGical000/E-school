<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $subjects = Subject::all();
        return $this->apiResponse('success',$subjects);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'max_mark'=>'required'
            ]);

            $subject = Subject::create($validatedData);

        }catch (\Exception $e){
            if($e->getCode()==23000)
            return $this->apiResponse('subject already exist',null,false);
        }


        return $this->apiResponse('success',$subject);
    }

    public function show($id)
    {
        $subject = Subject::find($id);
         if(!$subject)
             return $this->apiResponse('Subject not found');
        return $this->apiResponse('success',$subject);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'max_mark'=>'required'
        ]);

        $subject = Subject::find($id);
        if(!$subject)
            return $this->apiResponse('Subject not found');
        $subject->update($validatedData);

        return $this->apiResponse('success',$subject);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if(!$subject)
            return $this->apiResponse('Subject not found');

        $teachers = Teacher::where('subject_id', $id)->get();

        if ($teachers->count() > 0) {
            return $this->apiResponse(
                'Cannot delete subject because it is being used by one or more teachers.');
        }

        $subject->delete();

        return $this->apiResponse('deleted');
    }

    public  function searchByName(Request $request) {
        $name = $request->name;

        $subject= Subject::where('name', 'like', "$name%")->get();


        return $this->apiResponse('success',$subject);
    }
}

