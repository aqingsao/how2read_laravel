<?php

namespace App\Http\Controllers\Api;
use App\Issue;
use App\Question;
use App\QuestionVote;
use App\UserVote;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;


class IssueController extends Controller
{
  public function questions($issue_id){
    try{
      $questions = Question::with(array('choices'=>function($query){
        $query->select(['id', 'question_id', 'name', 'name1', 'source_type', 'source_url']);
      }))->where('issue_id', $issue_id)->get();

      return response()->json($questions);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }

  public function summary($issue_id){
    try{
      $user_count = QuestionVote::where('issue_id', $issue_id)->count();
      $correct_count = QuestionVote::where('issue_id', $issue_id)->where('is_correct', True)->count();

      return response()->json(['user_count'=>$user_count, 'correct_count'=>$correct_count]);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }

  public function over_takes($issue_id, $rate){
    $over_takes = UserVote::where('issue_id', $issue_id)->where('rate', '>', $rate)->count();
    return response()->json(['over_takes'=>$over_takes]);
  }
}
