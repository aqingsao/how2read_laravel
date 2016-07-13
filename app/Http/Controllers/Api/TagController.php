<?php

namespace App\Http\Controllers\Api;
use App\Tag;
use App\Http\Controllers\Controller;
use App\Http\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redis;
use Log;

class TagController extends Controller
{
  protected $tagService;
  public function __construct()
  {
    $this->tagService = new TagService;
  }

  public function query($name){
    $tags = $this->tagService->get_tags_start_with($name);
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
}
