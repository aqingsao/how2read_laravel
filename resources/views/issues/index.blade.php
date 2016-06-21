@extends('layouts.wechat')
@section('title', '程序员最容易读错的单词')
@section('description', '我在挑战程序员最容易读错的单词，你也来试试吧')

@section('content')
<div class="menu-top bg-info">
  程序员最容易读错的单词
</div>

<div class="page issues-index-page has-menu-top">
  <div class="content">
    <ul class="operations">
      @foreach ($issues as $key=>$issue)
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        <a href="/issues/{{$issue->id}}">挑战第{{$issue->description}}期</a>
        <small class="text-gray">{{date('Y-m-d', strtotime($issue->created_at))}}</small>
      </li>
      @endforeach
    </ul>

    <p>下一期敬请期待，您也可以：</p>
    <ul class="operations">
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        <a href="/questions/add">添加不认识的单词</a>
      </li>
    </ul>

    <div class="wechat-code">
      长按下图三两秒，有新单词我知早
      <img src="/static/images/qrcode.jpg" alt="程序员最容易读错的单词">
    </div>
  </div>
</div>
@endsection