<?php

namespace App\Http\Controllers;

use App\Models\Parentt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParenttController extends Controller
{
    use ApiResponseTrait;
    public function search(Request $request){
//
        $name=$request->name;
        $parents=DB::table('parents')
            ->where('father_first_name','like',"$name%")
            ->orWhere('father_last_name','like',"$name%")
            ->orWhere('mother_first_name','like',"$name%")
            ->orWhere('mother_last_name','like',"$name%")
            ->first();

        return $this->apiResponse('success',$parents);
    }

    public function showHome(){
        $parent = DB::table('parents')
            ->where('user_id',Auth::id())
            ->first();


        $kids=DB::table('students')
            ->where('parent_id','=',$parent->parent_id)
            ->get();


        $children=[];
        //$counter =1;
        foreach ($kids as $kid){
           // $parent->{"kid_number_$counter"}=$kid->student_id;
            $children[]= (object)['kid_name' => $kid->first_name,'kid_id'=>$kid->student_id];
            //$counter++;

        }
        $parent->children = $children;


        return $this->apiResponse('success',$parent);
//        return response()->json([
//            'status'=>true,
//            'message'=>'login successfully',
//            'children'=>$children,
//            'data'=>$parent,
//        ],200);


    }

    public function profile (Request $request){

        $parent=DB::table('parents')
            ->where('user_id',Auth::id())
            ->first();


        $kids_count=DB::table('students')
            ->where('parent_id' , '=' , $parent->parent_id)
            ->count();

        $profile_data=[
            'first name' => $parent->father_first_name,
            'last name' => $parent->father_last_name,
            'mother first name' => $parent->mother_first_name,
            'mother last name' => $parent->mother_last_name,
            'father phone number' => $parent->father_phone_number,
            'mother phone number' => $parent->mother_phone_number,
            'national id' => $parent->national_id,
            'kids' => $kids_count,

        ];


        return $this->apiResponse('success',$profile_data);

    }

}
