<?php

namespace App\Http\Controllers;
use App\Question;
use App\Issue;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;


class IssueController extends Controller
{
  public function __construct()
  {
  }

  public function index(){
    $issues = Issue::where('status', 1)->get();
    return view('issues.index', ['issues'=>$issues]);
  }

  public function show($issue_id){
    try{
      $count = Issue::where('status', 1)->count();
      $issue = Issue::where('status', 1)->findOrFail($issue_id);
      return view('issues.show', [
        'issue' => $issue, 'count' => $count
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist: '.$issue_id);
      return redirect()->action('IssueController@index');
    }
  }

  public function questions($issue_id){
    try{
      $issue = Issue::with('questions')->where('status', 1)->findOrFail($issue_id);
      return view('issues.questions', [
        'issue' => $issue
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('issue does not exist');
      return redirect()->action('IssueController@index');
    }
  }
}
