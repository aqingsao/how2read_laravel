@extends('layouts.wechat')
@section('title'){{$tag->name}}-程序员最容易读错的单词@stop
@section('description'){{$tag->name}}-程序员最容易读错的单词@stop
@section('keywords'){{$tag->name}}, 程序员, 读错, 单词@stop

@section('content')
<div class="container issue-container" ng-controller="IssueCtrl as vm">
  <div class="menu-top">
    <div class="menu-container bg-info">
      {{$tag->name}}
      <a class="left" href="#" ng-click="goBack('/tags');">
        <i class="iconfont icon-arrowleft"></i>
        返回
      </a>
    </div>
  </div>

  <div class="page questions-page has-menu-top">
    <ul class="items">
      @foreach ($tag->questions as $index=>$question)
      <a class="item question" href="/questions/{{$question->name}}">
        <strong class="name text-info">{{$index+1}}. {{$question->name}}
        </strong>
        <ul class="tags">
          @foreach ($question->tags as $index=>$tag)
          <li class="tag">{{$tag->name}}</li>
          @endforeach
        </ul>
        <div class="description">{{$question->description ?: '暂无简介'}}</div>
      </a>
      @endforeach
    </ul>
  </div>
</div>
@endsection