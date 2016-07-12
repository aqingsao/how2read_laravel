<?php

namespace App\Http\Controllers;
use App\Question;
use App\Http\Controllers\Controller;
use Log;

class TagController extends Controller
{
  public function query($name){
    $tags = $this->get_tags($name);
    if(empty($tags)){
      return response()->json([]);
    }
    return response()->json($tags);
  }

  private function get_tags($name){
    $key = 'how2read_tags_'.strtolower($name);
    $tags =$this->redis->get($key);
    if(empty($tags)){
      $tags = Tag::where('name', 'like', $name.'%')->get();
      Log::info('Tags: '.json_encode($tags));
      $tags = json_encode($tags);

      $this->redis->set($key, $tags);
    }
    return json_decode($tags);
  }
}
