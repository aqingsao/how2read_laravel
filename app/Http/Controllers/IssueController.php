<?php

namespace App\Http\Controllers;
use App\Question;
use App\Issue;
use App\UserVote;
use App\QuestionVote;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use App\Http\Services\IssueService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

class IssueController extends Controller
{
  protected $issueService;
  public function __construct()
  {
    $this->issueService = new IssueService;
  }

  public function index(){
    $issues = $this->issueService->get_issues();
    return view('issues.index', ['issues'=>$issues]);
  }

  public function show($issue_id){
    try{
      $issues = $this->issueService->get_issues();
      $issue = $this->issueService->get_issue($issue_id);
      $summary = $this->issueService->get_issue_summary($issue_id);
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
      $issue = $this->issueService->get_issue($issue_id);
      return view('issues.questions', [
        'issue' => $issue
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist');
      return redirect()->action('IssueController@index');
    }
  }
}
