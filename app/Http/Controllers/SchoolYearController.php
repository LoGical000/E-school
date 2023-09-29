<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
  use ApiResponseTrait;
    public function index()
    {
        $years = SchoolYear::all();
        return $this->apiResponse('success',$years);
    }

    public function store(Request $request)
    {


            $validatedData = $request->validate([
                'name' => 'required',
            ]);

        try {
            $year = SchoolYear::create($validatedData);
            return $this->apiResponse('success',$year);
        }



        catch (\Exception $e){
            if($e->getCode()==23000)
                return $this->apiResponse('year already exist',null,false);
        }



    }




//    public function destroy(SchoolYear $id)
//    {
//        $year = SchoolYear::find($id);
//        if(!$year)
//            return $this->apiResponse('year not found');
//
//
//        $year->delete();
//
//        return $this->apiResponse('deleted');
//    }
}
