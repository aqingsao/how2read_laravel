<?php

namespace App\Http\Controllers\Api;
use App\Question;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

class QuestionController extends Controller
{
  public function vote($question_id, $choice_id){
    try{
      $question = Question::with(array('choices'=>function($query){
        $query->select(['id', 'question_id'])->where('choices.is_correct', True);
      }))->findOrFail($question_id);

      $is_correct = count($question->choices) > 0 && $question->choices[0]->id == $choice_id;;

      return response()->json(['result'=> $is_correct, 'correct_id'=> $question->choices[0]->id]);
    } catch(ModelNotFoundException $e) {
      return response()->json(['result'=> False]);
    }
  }
}
