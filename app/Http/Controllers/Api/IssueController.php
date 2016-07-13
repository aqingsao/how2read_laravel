<?php

namespace App\Http\Controllers\Api;
use App\Issue;
use App\Question;
use App\QuestionVote;
use App\UserVote;
use App\Choice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use App\Http\Services\IssueService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Log;

class IssueController extends Controller
{
  protected $issueService;
  public function __construct()
  {
    $this->issueService = new IssueService;
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
      $summary = $this->issueService->get_issue_summary($issue_id);
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

    $summary = $this->issueService->get_issue_summary($issue_id);
    $summary->correct_count = $correct_count;
    $summary->over_takes = $over_takes;

    return response()->json($summary);
  }
}
