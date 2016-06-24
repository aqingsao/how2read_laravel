@extends('layouts.wechat')
@section('title', '程序员最容易读错的单词')
@section('description', '我在挑战程序员最容易读错的单词，你也来试试吧')
@section('keywords', '单词, 怎么读, 怎么念，怎么发音, how to pronounce, how to read, pronunciation')

@section('content')
<div class="menu-top bg-info">
  程序员最容易读错的单词
</div>

<div class="page issues-index-page has-menu-top">
  <div class="content">
    <p>目前共{{count($issues)}}期：</p>
    <ul class="operations">
      @foreach ($issues as $key=>$issue)
      <li class="operation text-info">
        <i class="icon iconfont">&#xe60d;</i>
        <a class="text-info" href="/issues/{{$issue->id}}">第{{$issue->id}}期 去挑战</a>
        <small class="fr text-gray">
          <a href="/issues/{{$issue->id}}/questions" class="text-info">
            查看
          </a>
        </small>
      </li>
      @endforeach
    </ul>

    <p>您也可以：</p>
    <ul class="operations">
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        <a class="text-info" href="/questions/add">添加不认识的单词</a>
      </li>
    </ul>

    <div class="text-center">
      长按下图三两秒，有新单词我知早
      <img src="/static/images/qrcode.jpg" alt="程序员最容易读错的单词">
    </div>
  </div>
</div>
@endsection