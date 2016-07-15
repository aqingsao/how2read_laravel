<?php

namespace App\Http\Controllers\Admin;
use App\Question;
use App\Http\Controllers\Controller;
use Log;

class AdminController extends Controller
{
  public function question_add(){
    return view('admin.question-add');
  }

  public function question_edit($question_name){
    return view('admin.question-add', array('question_name'=>$question_name));
  }
}
