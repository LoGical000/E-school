<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{//
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $employees = Employee::all();

        return $this->apiResponse('success',$employees);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$validatedData = $request->validate([
            'first_name'=> ['required','string','max=255'],
            'last_name' => ['required','string','max=255'],
            'job'=> ['required','string','max=255'],
            'phone_number' => ['required','string','min:10','max:10'],
            'address' => ['required','string','max:255'],
            'details' => 'nullable',

        ]);*/

        $validatedData = $request->validate([
            'first_name'=> ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'job'=> ['required','string','max:255'],
            'phone_number' => ['required','string','min:10','max:10'],
            'address' => ['required','string','max:255'],
            'details' => 'nullable',
        ]);

        $employee= Employee::create($validatedData);
        return $this->apiResponse('success',$employee);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $employee = Employee::find($request->id);
        if(!$employee)
            return $this->apiResponse('employee not found',null,false);
        return $this->apiResponse('success',$employee);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'first_name' => ['nullable','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            'job'=> ['string','max:25'],
            'phone_number' => ['nullable','string','min:10','max:10'],
            'address' => ['nullable','string','max:255'],
            'details' => 'nullable',
        ]);

        $employee = Employee::find($request->id);
        if(!$employee){
            return $this->apiResponse('employee not found');
        }
        $employee->update($validatedData);
        return $this->apiResponse('success',$employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        if(!$employee)
            return $this->apiResponse('Employee not found');
        $employee->delete();

        return $this->apiResponse('success',$employee);
    }


}
