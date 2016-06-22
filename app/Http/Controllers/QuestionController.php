<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
  public function show($question_id){
    $questions = Question::orderBy('created_at', 'asc')->get();
    return view('questions.index', [
        'questions' => $questions
    ]);
  }

  public function add(){
    return view('questions.add');
  }
}
