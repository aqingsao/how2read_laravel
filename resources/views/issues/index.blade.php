@extends('layouts.wechat')

@section('content')
<div class="menu-top bg-info">
  程序员最容易读错的单词
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
        <small class="text-gray">{{date('Y-m-d', strtotime($issue->created_at))}}</small>
      </li>
      @endforeach
      <li class="operation">
        <i class="icon iconfont">&#xe60d;</i>
        下一期
        <small class="text-gray">敬请期待</small>
      </li>
    </ul>

    <div class="margin-bottom-8">本列表会定期更新，未收录的单词欢迎添加</div>

    <div class="wechat-code">
      长按二维码，有新单词我先知
      <img src="/static/images/qrcode.jpg" alt="程序员最容易读错的单词">
    </div>
  </div>
</div>
@endsection