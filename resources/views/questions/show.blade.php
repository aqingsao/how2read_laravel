@extends('layouts.wechat')

@section('title'){{$question->name}}怎么读，如何正确发音@stop
@section('description') 如何读{{$question->name}}, pronounciation of {{$question->name}}, how to pronounce)@stop
@section('keywords') 怎么读, 正确读音, how to pronounce, how to read, pronunciation of {{$question->name}} @stop

@section('content')
<div class="container question-container" ng-controller="QuestionCtrl as vm">
  <div class="page question-show-page">
    <div class="header">
      @if ($question->issue_id > 0)
        <a class="sub-title text-info" href="/issues/{{$question->issue_id}}">第{{$question->issue_id}}期</a>
      @endif
      <h2 class="title">{{$question->name}}</h2>
    </div>
    
    <div class="content has-menu-bottom">
      <div class="choices">
        @foreach ($question->choices as $choice)
        <div class="choice">
          <div class="btn {{$choice->is_correct ? 'btn-info' : 'btn-default'}}" @if ($choice->is_correct && !empty($choice->audio_url)) ng-click="vm.playAudio('{{$choice->audio_url}}')" @endif >
            @if ($choice->is_correct && !empty($choice->audio_url))
              <i class="icon iconfont fl">&#xe623;</i>
            @endif
            <span>{{join(", ", array_filter([$choice->name_ipa, $choice->name_alias, $choice->name_cn]))}}</span>
          </div>
        </div>
        @endforeach
      </div>
      @if (count($question->tags) > 0)
      <ul class="tags pb6">
        @foreach ($question->tags as $tag)
          <li class="tag"><a href="/tags/{{$tag->name}}">{{$tag->name}}</a></li>
        @endforeach
      </ul>
      @endif
      <p class="text-gray">
        <strong>简介：</strong><span class="text-gray">{{$question->description}}</span>
      </p>
      <p class="text-gray">
        <strong>来源：</strong>
        @if ($question->source_url != '')
          <a class="text-info" target="_blank" href="{{$question->source_url}}">
            @if ($question->source_type == 1)
              官方(作者)
            @elseif ($question->source_type == 2)
              维基百科
            @elseif ($question->source_type == 3)
              标准读音
            @else
              其他
            @endif
          </a>
        @else
         @if ($question->source_type == 1)
            官方
          @elseif ($question->source_type == 2)
            维基百科
          @else
            标准读音
          @endif
        @endif
      </p>
      @if (!empty($question->remark))
      <p class="text-gray">
        <strong>备注：</strong><span>{{$question->remark}}</span>
      </p>
      @endif
    </div>
    <div class="menu-bottom">
      <div class="menu-container bg-info">
        @if ($question->issue_id > 0)
          @if ($question->next != '')
            <a href="/questions/{{urlencode($question->next)}}"><button class="btn btn-info full">下一单词</button></a>
          @else
            <a href="/issues/{{$question->issue_id}}/"><button class="btn btn-info full">返回第{{$question->issue_id}}期</button></a>
          @endif
        @else
          <a href="/issues"><button class="btn btn-info full">返回首页</button></a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection