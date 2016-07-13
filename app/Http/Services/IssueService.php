<?php

namespace App\Http\Services;
use App\Issue;
use App\Question;
use App\UserVote;
use App\QuestionVote;
use Redis;
use Log;

class IssueService
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function get_issues(){
    $issues = $this->redis->get('how2read_issues');
    if(empty($issues)){
      $issues = Issue::where('status', 1)->get();
      $issues = json_encode($issues);
      $this->redis->set('how2read_issues', $issues);
    }
    return json_decode($issues);
  }

  public function get_issue($issue_id){
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

  public function get_issue_summary($issue_id){
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
