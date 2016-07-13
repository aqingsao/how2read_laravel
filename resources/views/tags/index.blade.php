@extends('layouts.wechat')
@section('title', '标签-程序员最容易读错的单词')
@section('description', '我在挑战程序员最容易读错的单词，你也来试试吧')
@section('keywords', '单词, 怎么读, 怎么念，怎么发音, how to pronounce, how to read, pronunciation')

@section('content')
<div class="container">
  <div class="menu-top">
    <div class="menu-container bg-info">
      程序员最容易读错的单词
      <a class="left" href="/issues/">
        <i class="icon iconfont"></i>
        返回
      </a>
    </div>
  </div>

  <div class="page issues-index-page has-menu-top">
    <ul class="items">
      @foreach ($tags as $key=>$tag)
      <li class="item text-info">
        <a class="tag" href="/tags/{{$tag->name}}">{{$tag->name}}</a>
        <span class="fr text-gray">x100</span>
      </li>
      @endforeach
    </ul>
  </div>
</div>
@endsection