<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Redis;
use Log;

class QuestionController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function show($question_name){
    try{
      $question = $this->get_question($question_name);
      return view('questions.show', [
          'question' => $question
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('question does not exist: '.$question_name);
      return redirect()->action('IssueController@index');
    }
  }

  public function add(){
    Log::info('User tries to add a new question');
    return view('questions.add');
  }

  private function get_question($name){
    $key = 'how2read_question_'.strtolower($name);
    $question =$this->redis->get($key);
    if(!empty($question)){
      return json_decode($question);
    }

    $question = Question::with('choices', 'tags')->where('name', $name)->firstOrFail();
    $next_question = Question::where('issue_id', $question->issue_id)->where('id', '>', $question->id)->select('name')->first();
    if(!empty($next_question)){
      $question['next'] = $next_question['name'];
    }
    else{
      $$question['next'] = '';
    }

    $this->redis->set($key, $question);
    return $question;
  }
}
