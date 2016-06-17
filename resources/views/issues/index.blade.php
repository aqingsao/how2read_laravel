@extends('layouts.wechat')

@section('content')
<div class="menu-top bg-info">
  <div class="fr margin-right-8">
    <a class="btn btn-transparent" href="/questions/add">提交单词</a>
  </div>
</div>

<div class="page issues-index-page has-menu-top">
  <div class="content">
    <ul class="operations">
      @foreach ($issues as $key=>$issue)
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        <a href="/issues/{{$issue->id}}">第{{$issue->description}}期</a>
      </li>
      @endforeach
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        第三期（敬请期待）
      </li>
    </ul>

    <div class="margin-bottom-8">有不认识的单词？欢迎添加</div>
    <ul class="operations">
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        <a href="/questions/add">添加不认识的单词</a>
      </li>
    </ul>

    <div class="wechat-code">
      长按二维码，有新单词我先知
      <img src="/images/qrcode.jpg" alt="程序员最容易读错的单词">
    </div>
  </div>
</div>
@endsection