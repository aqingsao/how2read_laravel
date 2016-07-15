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
      return redirect()->route('question_add', ['question='.$question_name])->with('message', '抱歉！该单词不存在，您可以帮忙添加');
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
