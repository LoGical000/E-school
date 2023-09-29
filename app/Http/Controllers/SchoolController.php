<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    use ApiResponseTrait;


    public function show(string $id)
    {
        //
        $school = School::find($id);
        if(!$school)
            return $this->apiResponse('School not found');
        return $this->apiResponse('success',$school);

    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {

        $school = School::find($id);
        $school = School::query()->where('id', '=', $id )->update(
            [
            'name' => $request->has('name') ? $request['name'] : $school['name'],
            'address' => $request->has('address') ? $request['address'] : $school['address'],
            'overview' => $request->has('overview') ? $request['overview'] : $school['overview'],
            'phone' => $request->has('phone') ? $request['phone'] : $school['phone'],
            ]);

        $school = School::query()->first();
        if(!$school)
            return $this->apiResponse('School not found');
        return $this->apiResponse('School updated successfully',$school);
    }

}
