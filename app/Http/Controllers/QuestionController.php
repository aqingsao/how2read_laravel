<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use Log;

class QuestionController extends Controller
{
  public function show($question_id){
    try{
      Log::info('in show');
      $question = Question::with('choices')->findOrFail($question_id);
      $next_question = Question::where('issue_id', $question->issue_id)->where('id', '>', $question->id)->select('id')->first($question_id);
      $next_question_id = 0;
      if(!empty($next_question)){
        $next_question_id = $next_question->id;
      }
      return view('questions.show', [
          'question' => $question, 'next_question_id'=>$next_question_id
      ]);
    } catch(ModelNotFoundException $e) {
        Log::info('question does not exist: '.$question_id);
        return redirect()->action('IssueController@questions');
    }
  }

  public function add(){
    Log::info('in add');
    return view('questions.add');
  }
}
