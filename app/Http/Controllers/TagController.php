<?php

namespace App\Http\Controllers;
use App\Tag;
use App\Http\Controllers\Controller;
use Redis;
use Log;

class TagController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }
  public function show($name){
    try{
      $tag = $this->get_tag($name);
      return view('tags.show', [
          'tag' => $tag
      ]);
    } catch(ModelNotFoundException $e) {
      Log::info('tag does not exist: '.$name);
      return redirect()->action('TagController@index');
    }
    return response()->json($tag);
  }

  public function index(){
    $tags = $this->get_tags();
    return view('tags.index', ['tags'=>$tags]);
  }

  private function get_tag($name){
    $key = 'how2read_tag_'.strtolower($name);
    $tag =$this->redis->get($key);
    if(empty($tag)){
      $tag = Tag::where('name', strtolower($name))->firstOrFail();
      $tag = json_encode($tag);

      $this->redis->set($key, $tag);
    }
    return json_decode($tag);
  }
  private function get_tags(){
    $key = 'how2read_tags';
    $tags =$this->redis->get($key);
    if(empty($tags)){
      $tags = Tag::get();
      $tags = json_encode($tags);

      $this->redis->set($key, $tags);
    }
    return json_decode($tags);
  }
}
