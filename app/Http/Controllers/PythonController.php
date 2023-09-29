<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PythonController extends Controller
{
    public function answer(Request $request)
    {
        $context = $request->input('context');
        $question = $request->input('question');


        $response = Http::post('http://127.0.0.1:8001/answer', [
            'context' => $context,
            'question' => $question
        ]);

        // Decode the JSON response
        $responseData = $response->json();

        // Extract the answer from the response data
        $answer = $responseData['answer'];

        // Return the answer as an API response
        return response()->json(['answer' => $answer]);
    }




}
