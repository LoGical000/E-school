<?php

namespace App\Http\Controllers;

trait ApiResponseTrait
{
   public function apiResponse($message=null,$data=null,$status=true,$statuscode=200){


       return response()->json([
           'status'=>$status,
           'message' => $message,
           'data'=> $data
       ], $statuscode);
   }
}
