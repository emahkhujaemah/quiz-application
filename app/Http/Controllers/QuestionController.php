<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with('answers')->get();
        return Inertia::render('Question', [
            'questions' => $questions,
            // 'answers' => $answers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestdata = $request->all();
        $question=$requestdata['question'];

        // save question
        $newQuestion = new Question;
        $newQuestion->question=$question;
        $newQuestion->save();

        $answers = $requestdata['answers'];

        foreach ($answers as $answer) {
            $newAnswer = new Answer;
            $newAnswer->answer = $answer['answer'];
            $newAnswer->question_id = $newQuestion->id;
            $newAnswer->correct_answer = $answer['correct_answer'];
            $newAnswer->save();
        }

        return redirect('/questions')->with('success', 'Question and answer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
