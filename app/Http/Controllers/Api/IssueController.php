<?php

namespace App\Http\Controllers\Api;
use App\Question;
use App\Issue;
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
}
