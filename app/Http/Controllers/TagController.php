<?php

namespace App\Http\Controllers;
use App\Tag;
use App\Http\Controllers\Controller;
use App\Http\Services\TagService;
use Log;

class TagController extends Controller
{
  protected $tagService;
  public function __construct()
  {
    $this->tagService = new TagService;
  }

  public function show($name){
    try{
      $tag = $this->tagService->get_tag($name);
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
    $tags = $this->tagService->get_tags();
    return view('tags.index', ['tags'=>$tags]);
  }
}
