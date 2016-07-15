<?php

namespace App\Http\Services;
use App\Question;
use App\Choice;
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

  public function create($request, $user_id){
    $question = new Question($request->all()); // name
    $question->user_id = $user_id;
    $choices = [];
    foreach($request->choices as $c){
      $choices[] = new Choice($c);
    }
    $question->save();
    $question->choices()->saveMany($choices);
    $question->tags()->sync($request->tags);
  }

  public function update($request, $user_id){
    $question = Question::with('choices')->where('id', $request->id)->firstOrFail();
    $old_name = $question->name;
    $choice_ids = [];
    foreach($request->choices as $c){
      if(isset($c['id'])){
        $choice = Choice::where('id', $c['id'])->first();
        $choice->update($c);
        $choice_ids[] = $c['id'];
        Log::info('Update choice '.$choice->id.' for question '.$question->id);
        Log::info(json_encode($c));
      }
      else{
        $choice = new Choice($c);
        $choice->question_id = $question->id;
        $choice->save();
        $choice_ids[] = $choice->id;
        Log::info('Create choice '.$choice->id.' for question '.$question->id);
        Log::info(json_encode($c));
      }
    }
    foreach($question->choices as $c){
      if(!in_array($c->id, $choice_ids)){
        $c->delete();
        Log::info('Delete choice '.$choice->id.' for question '.$question->id);
      }
    }

    $question->update($request->all());
    $question->tags()->sync($request->tags);

    $this->on_question_updated($question->name);
    if($old_name != $question->name){
      $this->on_question_updated($old_name);
    }
  }

  private function on_question_updated($question_name){
    $key = 'how2read_question_'.strtolower($question_name);
    $this->redis->del($key);
  }
}
