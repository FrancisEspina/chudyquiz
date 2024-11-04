<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Difficulty;
use App\Models\Item;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    //

    public function __construct()
    {
        //
    }

    public function createQuestion(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'payload' => 'required|string',
        ]);

        // Convert the JSON string in 'payload' to an array
        $questionData = json_decode($validatedData['payload'], true); // true to get an associative array

        $new_question = Question::create([
            'question' => $questionData['question'],
            'qtype_id' => QuestionType::where('name', $questionData['questionType'])->value('id'),
            'difficulty' => (int) $questionData['difficulty'],
            'subject_id' => Subject::where('name', $questionData['selectedSubject'])->value('id'),
        ]);

        $new_question->save();


        if ($new_question['qtype_id'] == 1) {
            $choices = $questionData['choice'];

            $new_items = Item::create([
                'question_id' => $new_question->id,
                'A' => $choices['A'],
                'B' => $choices['B'],
                'C' => $choices['C'],
                'D' => $choices['D'],
                'E' => $choices['E'],
                'F' => $choices['F'],
            ]);
        }

        $answer = Answer::create([
            'question_id' => $new_question['id'],
            'answer' => $questionData['correctAnswer']
        ]);

        return response()->json([
            'message' => 'Data received successfully!',
            'data' => $new_question
        ]);
    }


    public function getSubjectQuestions(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required',

        ]);

        $subject_id = Subject::where('name', $validatedData['subject'])->first();
        $questions = Question::where('subject_id', $subject_id['id'])->get()->map(function ($question) {
            return [
                'id' => $question->id,
                'question' => $question->question,
                'question_type' => $question->questionType?->name,
                'difficulty' =>  $question->difficultyLevel?->name,
                'subject' => $question->subject?->name,
                'choices' => $question->choices->map(function ($choice) {
                    return [
                        'A' => $choice->A,
                        'B' => $choice->B,
                        'C' => $choice->C,
                        'D' => $choice->D,
                        'E' => $choice->E,
                        'F' => $choice->F,
                    ];
                }),
                'correct_answer' => $question->correct_answer?->answer

            ];
        });

        return response()->json([
            'message' => 'Questions Sent!',
            'data' => $questions
        ]);
    }
}
