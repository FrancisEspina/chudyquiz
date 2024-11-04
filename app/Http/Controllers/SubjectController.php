<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function createSubject(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        // Convert the JSON string in 'payload' to an array

        $new_subject = Subject::create([
            'name' => $validatedData['name'],
        ]);

        return response()->json([
            'message' => 'Subject added successfully!',
            'data' => $new_subject
        ]);
    }

    public function getAllSubjects()
    {
        $subjects = Subject::all();

        return response()->json([
            'message' => 'Subjects Sent!!',
            'data' => $subjects
        ]);
    }

    public function deleteSubject(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'subject' => 'required',
        ]);

        // Convert the JSON string in 'payload' to an array

        $subject = Subject::where('id', $validatedData['subject'])->first();
        $subject->delete();

        return response()->json([
            'message' => 'Subject Deleted!',
        ]);
    }
}
