<?php

namespace App\Http\Controllers;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string'
        ]);

        $parent_id=DB::table('parents')
            ->where('user_id','=', auth()->user()->id)
            ->first()->parent_id;


        $complaint = Complaint::create([
            'parent_id' => $parent_id,
            'date' =>  Carbon::now()->toDateString(),
            'description' => $validatedData['description'],
        ]);

        return $this->apiResponse('Complaint submitted successfully',$complaint);

    }


    public function getParentComplaints(Request $request)
    {
        $parent_id=DB::table('parents')
            ->where('user_id','=', auth()->user()->id)
            ->first()->parent_id;

        $complaints = Complaint::where('parent_id', $parent_id)
            ->orderByDesc('created_at')
            ->get();

        if(!$complaints)
        {
            return $this->apiResponse('there is no complaints');
        }

        return $this->apiResponse('success',$complaints);
    }





}
