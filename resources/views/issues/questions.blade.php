@extends('layouts.wechat')
@section('title')我在挑战第{{$issue->id}}期，程序员最容易读错的单词，你也来试试吧@stop
@section('description')我在挑战第{{$issue->id}}期，程序员最容易读错的单词，你也来试试吧@stop
@section('keywords')第{{$issue->id}}期, 程序员最容易读错的单词@stop

@section('content')
<div class="container issue-container">
  <div class="menu-top">
    <div class="menu-container bg-info">
      @if ($issue->id == 0)
        未发布
      @else
        第{{$issue->id}}期
      @endif
      <a class="left" href="/issues">
        <i class="icon iconfont">&#xe65a;</i>
        返回
      </a>
    </div>
  </div>

  <div class="page questions-page has-menu-top has-menu-bottom">
    <ul class="items">
      @foreach ($issue->questions as $index=>$question)
      <li class="item question">
        <a href="/questions/{{$question->name}}">
          <strong class="name text-info">{{$index+1}}. {{$question->name}}</strong>
        </a>
        <ul class="tags">
          @foreach ($question->tags as $index=>$tag)
          <li class="tag">
            <a href="/tags/{{$tag->name}}">{{$tag->name}}</a>
          </li>
          @endforeach
        </ul>
        <div class="description">{{$question->description ?: '暂无简介'}}</div>
      </li>
      @endforeach
    </ul>

    <div class="menu-bottom">
      <div class="menu-container bg-info">
        <a class="btn btn-info full" href="/issues/{{$issue->id}}">去挑战</a>
      </div>
    </div>
  </div>
</div>
@endsection