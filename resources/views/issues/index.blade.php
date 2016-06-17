@extends('layouts.wechat')

@section('content')
<div class="page issues-index-page">
  <div class="header">
    <h2 class="title">程序员最容易读错的单词</h2>
  </div>

  <div class="content">
    <ul class="operations">
      @foreach ($issues as $key=>$issue)
      <li class="operation"><a href="/issues/{{$issue->id}}">第{{$issue->description}}期</a></li>
      @endforeach
      <li class="operation">第三期（敬请期待）</li>
    </ul>

    <div class="margin-bottom-8">有不认识的单词，欢迎添加</div>
    <ul class="operations">
      <li class="operation"><a href="/questions/add">添加不认识的单词</a></li>
    </ul>

    <div class="wechat-code">
      长按二维码，有新单词我先知
      <img src="/images/qrcode.jpg" alt="程序员最容易读错的单词">
    </div>
  </div>
</div>
@endsection