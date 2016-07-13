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
      $tags = Tag::with(array('questions'=>function($query){
        $query->select(['name']);
      }))->select('id', 'name')->get();
      $tags = json_decode(json_encode($tags));
      usort($tags, array($this, 'sort_tags'));
      $tags = json_encode($tags);

      Log::info('tags: '.json_encode($tags));
      $this->redis->set($key, $tags);
    }
    return json_decode($tags);
  }

  public function get_tag($name){
    $key = 'how2read_tag_'.strtolower($name);
    $tag =$this->redis->get($key);
    if(empty($tag)){
      $tag = Tag::with(array('questions'=>function($query){
        $query->select(['id', 'name']);
      }))->where('name', $name)->firstOrFail();
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

  public function get_tag_questions($tag){
    $key = 'how2read_tag_questions_'.strtolower($tag->name);
    $tag_questions =$this->redis->get($key);
    if(empty($tag_questions)){
      $tag = Tag::with('questions')->where('name', $name)->firstOrFail();
      $tag_questions = $tag->questions;
      Log::info('get tag questions with name '.$tag->name.': '.json_encode($tag_questions));
      $tag_questions = json_encode($tag_questions);

      $this->redis->set($key, $tag_questions);
    }
    return json_decode($tag_questions);
  }

  private function sort_tags($a, $b){
    if ($a == $b) {
        return 0;
    }
    return (count($a->questions) < count($b->questions)) ? 1 : -1;
  }
}
