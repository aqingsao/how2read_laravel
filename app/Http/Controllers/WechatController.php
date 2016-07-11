<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Log;

class WechatController extends Controller
{
  public function show($term_name){
    return redirect()->route('question_show', [$term_name]);
  }
}
