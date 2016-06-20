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

  public function create(){
    Log::info('User tries to create question');
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:posts|max:255',
      'body' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json(['result'=> False]);
    }

    $request->user()->questions()->create([
      'name' => $request->name,
    ]);

    return response()->json(['result'=> True]);
  }

  public function find_by_name($name){
    $question = Question::where('name', $name)->first();
    if(empty($question)){
      return response()->json([]);
    }
    return response()->json($question);
  }
}
