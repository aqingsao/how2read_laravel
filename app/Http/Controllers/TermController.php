<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use Log;

class TermController extends Controller
{
  public function show($term_name){
    return redirect()->route('question_show', [$term_name]);
  }
}
