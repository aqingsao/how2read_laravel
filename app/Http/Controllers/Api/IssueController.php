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
use Log;


class IssueController extends Controller
{
  public function detail($issue_id){
    try{
      $issue = Issue::with('questions')->with(array('questions.choices'=>function($query){
        $query->select(['id', 'question_id', 'name_ipa', 'name_alias', 'name_cn']);
      }))->where('id', $issue_id)->where('status', 1)->firstOrFail();
      return response()->json($issue);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }
  public function vote($issue_id, $question_id, $choice_id){
    try{
      $user_id = Auth::id();
      $choices = Choice::select(['id', 'question_id', 'type', 'url', 'audio_url', 'description'])->where('question_id', $question_id)->where('choices.is_correct', True)->get();

      $is_correct = false;
      foreach ($choices as $choice) {
        if($choice->id == $choice_id){
          $is_correct = True;
        }
      }

      $question_vote = QuestionVote::where('user_id', $user_id)->where('issue_id', $issue_id)->where('question_id', $question_id)->first();
      if(empty($question_vote)){
        $question_vote = new QuestionVote;
        $question_vote->user_id = $user_id;
        $question_vote->issue_id = $issue_id;
        $question_vote->question_id = $question_id;
      }
      $question_vote->choice_id = $choice_id;
      $question_vote->is_correct = $is_correct;
      $question_vote->save();

      return response()->json($choices);
    } catch(ModelNotFoundException $e) {
      return response()->json(['result'=> False]);
    }
  }

  public function summary($issue_id){
    try{
      $user_count = UserVote::where('issue_id', $issue_id)->count();
      $voted_count = QuestionVote::where('issue_id', $issue_id)->count();
      $correct_count = QuestionVote::where('issue_id', $issue_id)->where('is_correct', True)->count();

      return response()->json(['user_count'=>$user_count, 'voted_count'=>$voted_count, 'correct_count'=>$correct_count]);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }

  public function finish($issue_id){
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
    return response()->json(['over_takes'=>$over_takes]);
  }
}
