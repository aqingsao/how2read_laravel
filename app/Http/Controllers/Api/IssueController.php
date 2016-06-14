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
      $issue = Issue::findOrFail($issue_id);
      Log::info(json_encode($issue->questions));
      return response()->json($issue->questions);
    } catch(ModelNotFoundException $e) {
      return [];
    }
  }
}
