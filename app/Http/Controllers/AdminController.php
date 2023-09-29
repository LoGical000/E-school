<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\Parentt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    use ApiResponseTrait;

    public function index()
    {
        $admins = Admin::all();
        return $this->apiResponse('success',$admins);
    }

    public function getStatistics()
    {


        $statistics['admins']=DB::table('admins')->count();
        $statistics['teachers']=DB::table('teachers')->count();
        $statistics['students']=DB::table('students')->count();
        $statistics['employees']=DB::table('employees')->count();

        return $this->apiResponse('success',$statistics);

    }

    public function show(Request $request)
    {
        $admin = Admin::find($request->id);
        if(!$admin)
            return $this->apiResponse('admin not found',null,false);
        return $this->apiResponse('success',$admin);

    }

    public function ComplaintIndex()
    {
        $complaintsWithParentNames = Complaint::selectRaw(
            'complaints.*,
    CONCAT(parents.father_first_name, " ", parents.father_last_name) as parent_name'
        )
            ->join('parents', 'complaints.parent_id', '=', 'parents.parent_id')
            ->get();



        if(!$complaintsWithParentNames)
        {
            return $this->apiResponse('there is no pending complaints');
        }

        return $this->apiResponse('success',$complaintsWithParentNames);
    }

    public function ComplaintResolve(Request $request, Complaint $complaint)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $status = $validatedData['status'] === '1' ? 'resolved' : 'pending';
        $complaint->update(['status' => $status]);

        return $this->apiResponse('Complaint resolved successfully',$complaint);
    }


}
