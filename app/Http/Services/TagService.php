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
        $query->select(['name']);
      }))->with(array('questions.tags'=>function($query){
        $query->select(['name']);
      }))->where('name', $name)->firstOrFail();
      $tag = json_encode($tag);

      $this->redis->set($key, $tag);
    }
    return json_decode($tag);
  }

  public function get_tags_start_with($name){
    $name = str_replace(' ', '-', $name);
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

  public function create($name){
    $tag = new Tag;
    $tag->name = $name;
    $tag->save();
    Log::info('create a new tag: '.$tag->name.', id: '.$tag->id);
    $this->on_tag_created($tag);
    return $tag;
  }

  private function sort_tags($a, $b){
    if ($a == $b) {
        return 0;
    }
    return (count($a->questions) < count($b->questions)) ? 1 : -1;
  }

  private function on_tag_created($tag){
    $key = 'how2read_tags';
    $this->redis->del($key);

    $name = '';
    $chars = str_split($tag->name);
    foreach ($chars as $char) {
      $name = $name.$char;
      $name = str_replace(' ', '-', $name);
      $key = 'how2read_tags_starts_with_'.strtolower($name);
      $this->redis->del($key);
    }
  }
}
