<?php

namespace App\Http\Controllers\Api;
use App\Question;
use App\Choice;
use App\QuestionVote;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Redirect;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Validator;
use Log;

class QuestionController extends Controller
{
  public function create(Request $request){
    $user_id = Auth::id();
    $question = $request->all();
    Log::info('User tries to create a question.');
    Log::info($question);

    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:questions|max:255',
      'correctChoice' => 'required_if:correctChoiceChecked,true',
      'choices' => 'required|array|'
    ]);

    if ($validator->fails()) {
      Log::error('validation failed:'.json_encode($validator->messages()));
      return response()->json(['result'=> False]);
    }

    $question = new Question;
    $question->name = $request->name;
    $question->description = $request->description;
    $question->user_id = $user_id;
    $choices = [];
    foreach($request->choices as $c){
      $choice = new Choice;
      $choice->name_ipa = $c['name_ipa'];
      $choice->name_alias = $c['name_alias'];
      $choice->name_cn = $c['name_cn'];
      $choice->is_correct = False;
      $choices[]=$choice;
    }
    if($request->correctChoiceChecked){
      $choice = new Choice;
      $choice->name_ipa = $request->correctChoice['name_ipa'];
      $choice->name_alias = $request->correctChoice['name_alias'];
      $choice->name_cn = $request->correctChoice['name_cn'];
      $choice->is_correct = True;
      $choices[]=$choice;
    }
    $question->save();
    $question->choices()->saveMany($choices);

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
