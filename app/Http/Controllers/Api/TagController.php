<?php

namespace App\Http\Controllers\Api;
use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redis;
use Log;

class TagController extends Controller
{
  protected $redis;
  public function __construct()
  {
    $this->redis = Redis::connection();
  }

  public function query($name){
    $tags = $this->get_tags($name);
    if(empty($tags)){
      return response()->json([]);
    }
    return response()->json($tags);
  }

  public function create(Request $request){
    $user_id = Auth::id();
    $tag = Tag::where('name', '=', strtolower($request->name))->first();
    if($tag == null){
      $tag = new Tag;
      $tag->name = $request->name;
      $tag->save();
      Log::info('create a new tag: '.$tag->name.', id: '.$tag->id);
    }
    return response()->json($tag);
  }

  private function get_tags($name){
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
