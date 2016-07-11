<?php

namespace App\Http\Controllers;
use App\Question;
use App\Issue;
use App\UserVote;
use App\QuestionVote;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Redis;
use Log;

class IssueController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function index(){
    $issues = $this->get_issues();
    return view('issues.index', ['issues'=>$issues]);
  }

  public function show($issue_id){
    try{
      $issues = $this->get_issues();
      $issue = $this->get_issue($issue_id);
      $summary = $this->get_issue_summary($issue_id);
      return view('issues.show', [
        'issue' => $issue, 'issues' => $issues, 'summary' =>$summary
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist: '.$issue_id);
      return redirect()->action('IssueController@index');
    }
  }

  public function questions($issue_id){
    try{
      $issue = $this->get_issue($issue_id);
      return view('issues.questions', [
        'issue' => $issue
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist');
      return redirect()->action('IssueController@index');
    }
  }
  public function new_questions(){
    try{
      $issue = new Issue;
      $issue->id = 0;
      $issue->questions = Question::where('issue_id', NULL)->get();
      return view('issues.questions', [
        'issue' => $issue
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist');
      return redirect()->action('IssueController@index');
    }
  }

  private function get_issues(){
    $issues = $this->redis->get('how2read_issues');
    if(empty($issues)){
      $issues = Issue::where('status', 1)->get();
      $issues = json_encode($issues);
      $this->redis->set('how2read_issues', $issues);
    }
    return json_decode($issues);
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
}
