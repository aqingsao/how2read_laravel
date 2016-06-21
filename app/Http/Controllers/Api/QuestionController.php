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
  public function vote($question_id, $choice_id){
    try{
      $user_id = Auth::id();
      $question = Question::with(array('choices'=>function($query){
        $query->select(['id', 'question_id'])->where('choices.is_correct', True);
      }))->findOrFail($question_id);

      $is_correct = count($question->choices) > 0 && $question->choices[0]->id == $choice_id;

      $question_vote = QuestionVote::where('user_id', $user_id)->where('issue_id', $question->issue_id)->where('question_id', $question_id)->first();
      if(empty($question_vote)){
        $question_vote = new QuestionVote;
        $question_vote->user_id = $user_id;
        $question_vote->issue_id = $question->issue_id;
        $question_vote->question_id = $question_id;
      }
      $question_vote->choice_id = $choice_id;
      $question_vote->is_correct = $is_correct;
      $question_vote->save();

      return response()->json(['result'=> $is_correct, 'correct_id'=> $question->choices[0]->id]);
    } catch(ModelNotFoundException $e) {
      return response()->json(['result'=> False]);
    }
  }

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
      $choice->name = $c['name'];
      $choice->name1 = $c['name1'];
      $choice->is_correct = False;
      $choices[]=$choice;
    }
    if($request->correctChoiceChecked){
      $choice = new Choice;
      $choice->name = $request->correctChoice['name'];
      $choice->name1 = $request->correctChoice['name1'];
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
