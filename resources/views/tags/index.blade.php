@extends('layouts.wechat')
@section('title', '标签-程序员最容易读错的单词')
@section('description', '程序员最容易读错的单词标签列表')
@section('keywords', '标签, 列表, 程序员最容易读错的单词')

@section('content')
<div class="container">
  <div class="menu-top">
    <div class="menu-container bg-info">
      标签
      <a class="left" href="#" ng-click="goBack();">
        <i class="iconfont icon-arrowleft"></i>
        返回
      </a>
    </div>
  </div>

  <div class="page issues-index-page has-menu-top">
    <ul class="items">
      @foreach ($tags as $key=>$tag)
      <li class="item text-info">
        <a class="tag" href="/tags/{{$tag->name}}">{{$tag->name}}</a>
        <span class="fr text-gray">x{{count($tag->questions)}}</span>
      </li>
      @endforeach
    </ul>
  </div>
</div>
@endsection