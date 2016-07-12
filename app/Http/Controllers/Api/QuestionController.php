<?php

namespace App\Http\Controllers\Api;
use App\Question;
use App\Choice;
use App\QuestionVote;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redis;
use Log;

class QuestionController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function create(Request $request){
    $user_id = Auth::id();
    $question = $request->all();
    Log::info('User tries to create a question.');
    Log::info($question);

    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:questions|max:255',
      'correctChoice' => 'required_if:correctChoiceChecked,true',
      'choices' => 'required|array|'
    ]);

    if ($validator->fails()) {
      Log::error('validation failed:'.json_encode($validator->messages()));
      return response()->json(['result'=> False]);
    }

    $question = new Question;
    $question->name = $request->name;
    $question->description = $request->description;
    $question->source_type = $request->source_type;
    $question->source_url = $request->source_url;
    $question->remark = $request->remark;
    $question->user_id = $user_id;
    $choices = [];
    foreach($request->choices as $c){
      $choice = new Choice;
      $choice->name_ipa = $c['name_ipa'];
      $choice->name_alias = $c['name_alias'];
      $choice->name_cn = $c['name_cn'];
      $choice->audio_url = '';
      $choice->is_correct = False;
      $choices[]=$choice;
    }
    if($request->correctChoiceChecked){
      $choice = new Choice;
      $choice->name_ipa = $request->correctChoice['name_ipa'];
      $choice->name_alias = $request->correctChoice['name_alias'];
      $choice->name_cn = $request->correctChoice['name_cn'];
      $choice->audio_url = $request->correctChoice['audio_url'];
      $choice->is_correct = True;
      $choices[]=$choice;
    }
    $question->save();
    $question->choices()->saveMany($choices);

    return response()->json(['result'=> True]);
  }

  public function vote($question_name, $choice_id){
    try{
      $user_id = Auth::id();
      $question = $this->get_question($question_name);
      $correct_choices = [];
      foreach ($question->choices as $choice) {
        if($choice->is_correct){
          $correct_choices[] = $choice->id;
        }
      }
      $is_correct = in_array($choice_id, $correct_choices);
      Log::info('User vote '.$question_name.', result: '.$is_correct);

      $question_vote = QuestionVote::where('user_id', $user_id)->where('issue_id', $question->issue_id)->where('question_id', $question->id)->first();
      if(empty($question_vote)){
        $question_vote = new QuestionVote;
        $question_vote->user_id = $user_id;
        $question_vote->issue_id = $question->issue_id;
        $question_vote->question_id = $question->id;
      }
      $question_vote->choice_id = $choice_id;
      $question_vote->is_correct = $is_correct;
      $question_vote->save();

      return response()->json(array('result'=>$is_correct, 'correct_choices'=>$correct_choices));
    } catch(ModelNotFoundException $e) {
      Log::info('User Failed to vote '.$question_name.': '.json_encode($e));
      return response()->json(['result'=> False]);
    }
  }

  public function query($name){
    $question = $this->get_question($name);
    if(empty($question)){
      return response()->json([]);
    }
    return response()->json($question);
  }

  public function find_by_name($name){
    $question = Question::where('name', $name)->first();
    if(empty($question)){
      return response()->json([]);
    }
    return response()->json($question);
  }

  private function get_question($name){
    $key = 'how2read_question_'.strtolower($name);
    $question =$this->redis->get($key);
    if(empty($question)){
      $question = Question::with('choices')->where('name', $name)->firstOrFail();
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
