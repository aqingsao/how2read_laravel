<?php

namespace App\Http\Services;
use App\Question;
use Redis;
use Log;

class QuestionService
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function get_question($name){
    $key = 'how2read_question_'.strtolower($name);
    $question =$this->redis->get($key);
    if(empty($question)){
      $question = Question::with('choices', 'tags')->where('name', $name)->firstOrFail();
      Log::info('question: '.json_encode($question));
      $next_question = Question::where('issue_id', $question->issue_id)->where('id', '>', $question->id)->select('name')->first();
      Log::info('next question: '.json_encode($next_question));
      if(!empty($next_question)){
        $question['next'] = $next_question['name'];
      }
      else{
        $question['next'] = '';
      }
      $question = json_encode($question);

      $this->redis->set($key, $question);
    }
    return json_decode($question);
  }
}
