<?php

namespace App\Http\Services;
use App\Tag;
use App\Question;
use Redis;
use Log;

class TagService
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function get_tags(){
    $key = 'how2read_tags';
    $tags =$this->redis->get($key);
    if(empty($tags)){
      $tags = Tag::get();
      $tags = json_encode($tags);

      $this->redis->set($key, $tags);
    }
    return json_decode($tags);
  }

  public function get_tag($name){
    $key = 'how2read_tag_'.strtolower($name);
    $tag =$this->redis->get($key);
    if(empty($tag)){
      $tag = Tag::with('questions')->where('name', $name)->firstOrFail();
      $tag = json_encode($tag);

      $this->redis->set($key, $tag);
    }
    return json_decode($tag);
  }

  public function get_tags_start_with($name){
    $key = 'how2read_tags_starts_with_'.strtolower($name);
    $tags =$this->redis->get($key);
    if(empty($tags)){
      $tags = Tag::where('name', 'like', $name.'%')->get();
      Log::info('get tags with name '.$name.': '.json_encode($tags));
      $tags = json_encode($tags);

      $this->redis->set($key, $tags);
    }
    return json_decode($tags);
  }
}
