<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request){
        $data=$request->validate([
            'name'=>['required'],
            'url'=>['required', 'url'],
            'grade_id'=>['required','integer']
        ]);

        $exists=DB::table('books')
            ->where('url',$request->url)
            ->where('grade_id',$request->grade_id)
            ->exists();
        if($exists)
            return $this->apiResponse('this grade already have this url assigned to',null,false);

        $res  = Book::create($data);
        return $this->apiResponse('success',$res);
    }

    public function showAdmin(Request $request){
        $grade_id = $request->query('grade_id');

        $res = DB::table('books')
            ->where('grade_id',$grade_id)
            ->get();

        return $this->apiResponse('success',$res);
    }

    public function showStudent(){
        $user_id = Auth::id();
        $grade_id = DB::table('students')
            ->where('user_id',$user_id)
            ->first()->grade_id;

        $res = DB::table('books')
            ->where('grade_id',$grade_id)
            ->get();

        return $this->apiResponse('success',$res);
    }

    public function deleteBook(Request $request, $id)
    {
        DB::table('books')->where('id', $id)->delete();

        return $this->apiResponse('deleted');
    }

    public function updateBook(Request $request)
    {
        $validatedData = $request->validate([
            'id'=>'nullable|integer',
            'name' => 'nullable',
            'grade_id' => 'nullable|integer',
            'url' => 'nullable|url',
        ]);

        $book = Book::find($request->id);
        if(!$book)
            return $this->apiResponse('book not found',null,false);
        $book->update($validatedData);

        return $this->apiResponse('success',$book);


    }

    public function showParent($student_id){
        $grade_id = DB::table('students')
            ->where('student_id',$student_id)
            ->first()->grade_id;

        $res = DB::table('books')
            ->where('grade_id',$grade_id)
            ->get();

        return $this->apiResponse('success',$res);
    }



}
