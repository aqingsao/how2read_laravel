<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

class QuestionController extends Controller
{
  public function show($question_name){
    try{
      $question = Question::with('choices')->where('name', $question_name)->firstOrFail();
      $next_question = Question::where('issue_id', $question->issue_id)->where('id', '>', $question->id)->select('name')->first($question->id);
      $next_question_name = '';
      if(!empty($next_question)){
        $next_question_name = $next_question->name;
      }
      return view('questions.show', [
          'question' => $question, 'next_question_name'=>$next_question_name
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('question does not exist: '.$question_name);
      return redirect()->action('IssueController@index');
    }
  }

  public function add(){
    Log::info('in add');
    return view('questions.add');
  }
}
