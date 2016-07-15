<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use App\Http\Services\QuestionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

class QuestionController extends Controller
{
  protected $questionService;
  public function __construct()
  {
    $this->questionService = new QuestionService();
  }

  public function show($question_name){
    try{
      $question = $this->questionService->get_question($question_name);
      return view('questions.show', [
          'question' => $question
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('question does not exist: '.$question_name);
      return redirect()->action('IssueController@index');
    }
  }

  public function add(){
    Log::info('User tries to add a new question');
    return view('questions.add');
  }

  public function tags(){
    Log::info('User tries to add a new question');
    return view('questions.tags');
  }
}
