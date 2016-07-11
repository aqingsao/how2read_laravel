<?php

namespace App\Http\Controllers\Api;
use App\Issue;
use App\Question;
use App\QuestionVote;
use App\UserVote;
use App\Choice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Redis;
use Log;

class IssueController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }
  public function first_question($issue_id){
    try{
      $issue = $this->get_issue($issue_id);
      $question = $this->get_question($issue->next_question);
      return response()->json($question);
    } catch(ModelNotFoundException $e) {
      return response()->json([]);;
    }
  }

  public function detail($issue_id){
    try{
      $issue = Issue::with('questions')->with(array('questions.choices'=>function($query){
        $query->select(['id', 'question_id', 'name_ipa', 'name_alias', 'name_cn', 'audio_url']);
      }))->where('status', 1)->where('id', $issue_id)->firstOrFail();
      return response()->json($issue);
    } catch(ModelNotFoundException $e) {
      return response()->json([]);;
    }
  }
  public function questions($issue_id){
    try{
      $questions = Question::where('issue_id', $issue_id)->get();
      return response()->json($questions);
    } catch(ModelNotFoundException $e) {
      return response()->json([]);;
    }
  }

  public function summary($issue_id){
    try{
      $summary = $this->get_issue_summary($issue_id);
      return response()->json($summary);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }

  public function vote_finish($issue_id){
    $user_id = Auth::id();
    $correct_count = QuestionVote::where('issue_id', $issue_id)->where('user_id', $user_id)->where('is_correct', True)->count();
    $user_vote = UserVote::where('user_id', $user_id)->where('issue_id', $issue_id)->first();
    if(empty($user_vote)){
      $user_vote = new UserVote;
      $user_vote->user_id = $user_id;
      $user_vote->issue_id=$issue_id;
    }
    $user_vote->correct_count = $correct_count;
    $user_vote->save();
    Log::info('User '.$user_id.' vote issue '.$issue_id.' with correct count: '.$correct_count);
    if($correct_count == 0){
      $over_takes = 0;
    }
    else{
      $over_takes = UserVote::where('issue_id', $issue_id)->where('correct_count', '<=', $correct_count)->count();
    }

    $summary = $this->get_issue_summary($issue_id);
    $summary->correct_count = $correct_count;
    $summary->over_takes = $over_takes;

    return response()->json($summary);
  }

  private function get_issue($issue_id){
    $key = 'how2read_issue_'.$issue_id;
    $issue =$this->redis->get($key);
    if(empty($issue)){
      $issue = Issue::with('questions')->where('status', 1)->findOrFail($issue_id);
      $next_question = Question::where('issue_id', $issue_id)->select('name')->first();
      if(!empty($next_question)){
        $issue['next_question'] = $next_question['name'];
      }
      else{
        $issue['next_question'] = '';
      }
      $issue = json_encode($issue);
      $this->redis->set($key, $issue);
    };
    return json_decode($issue);
  }

  private function get_issue_summary($issue_id){
    $key = 'how2read_issue_summary_'.$issue_id;    
    $summary =$this->redis->get($key);
    if(empty($summary)){
      $question_count = Question::where('issue_id', $issue_id)->count();
      $user_count = UserVote::where('issue_id', $issue_id)->count();
      $voted_count = QuestionVote::where('issue_id', $issue_id)->count();
      $correct_count = QuestionVote::where('issue_id', $issue_id)->where('is_correct', True)->count();
      $summary = json_encode(array('question_count'=>$question_count,'user_count'=>$user_count, 'voted_count'=>$voted_count, 'correct_count'=>$correct_count));
      $this->redis->set($key, $summary);
    };

    return json_decode($summary);
  }
  private function get_question($name){
    $key = 'how2read_question_'.strtolower($name);
    $question =$this->redis->get($key);
    if(empty($question)){
      $question = Question::with('choices')->where('name', $name)->firstOrFail();
      $next_question = Question::where('issue_id', $question->issue_id)->where('id', '>', $question->id)->select('name')->first();
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
