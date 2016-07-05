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
      <a class="left" href="/issues/">
        <i class="icon iconfont">&#xe65a;</i>
        返回
      </a>
    </div>
  </div>

  <div class="page questions-page has-menu-top has-menu-bottom">
    <div class="content">
      本期共{{count($issue->questions)}}个单词
      <ul class="questions">
        @foreach ($issue->questions as $index=>$question)
        <a class="question" href="/questions/{{$question->name}}">
          <strong class="name text-info">{{$index+1}}. {{$question->name}}
          </strong>
          <span class="description">{{$question->description}}</span>
        </a>
        @endforeach
      </ul>
    </div>

    <div class="menu-bottom">
      <div class="menu-container bg-info">
        <a class="btn btn-info full" href="/issues/{{$issue->id}}">去挑战</a>
      </div>
    </div>
  </div>
</div>
@endsection