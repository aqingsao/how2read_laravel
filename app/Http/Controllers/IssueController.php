<?php

namespace App\Http\Controllers;
use App\Question;
use App\Issue;
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
      return view('issues.show', [
        'issue' => $issue, 'issues' => $issues
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
    if(!empty($issues)){
      return json_decode($issues);
    }
    $issues = Issue::where('status', 1)->get();
    $this->redis->set('how2read_issues', $issues);
    return $issues;
  }

  private function get_issue($issue_id){
    $key = 'how2read_issue_'.$issue_id;
    $issue =$this->redis->get($key);
    if(!empty($issue)){
      return json_decode($issue);
    };
    $issue = Issue::with('questions')->where('status', 1)->findOrFail($issue_id);
    $this->redis->set($key, $issue);
    return $issue;
  }
}
