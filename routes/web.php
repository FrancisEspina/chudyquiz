<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});



Route::middleware(['cors'])->group(function () {
    Route::get('/create_question', [QuestionController::class, 'createQuestion']);
    Route::get('/seed_question', [QuestionController::class, 'seedQuestion']);
    Route::get('/remove_question', [QuestionController::class, 'removeQuestion']);

    Route::get('/get_questions', [QuestionController::class, 'getSubjectQuestions']);

    Route::get('/get_subjects', [SubjectController::class, 'getAllSubjects']);
    Route::get('/add_subject', [SubjectController::class, 'createSubject']);
    Route::get('/delete_subject', [SubjectController::class, 'deleteSubject']);
});

require __DIR__ . '/auth.php';
