@extends('layouts.wechat')

@section('content')
<div class="question-container">
  <div class="page question-show-page has-menu-top">
    <div class="header">
      <a class="sub-title text-info" href="/issues/{{$question->issue_id}}/questions">第{{$question->issue_id}}期</a>
      <h2 class="title">{{$question->name}}</h2>
    </div>
    <div class="content has-menu-bottom">
      <div>{{$question->description}}</div>
      <div class="choices">
        @foreach ($question->choices as $choice)
        <div class="choice">
          <div class="btn {{$choice->is_correct ? 'btn-info' : 'btn-default' }}" >
            @if ($choice->is_correct && !empty($choice->audio_url))
              <i class="icon iconfont fl">&#xe623;</i>
            @endif
            <span>{{join(", ", array_filter([$choice->name_ipa, $choice->name_alias, $choice->name_cn]))}}</span>
          </div>
        </div>
        @endforeach

        <div>
          <strong>来源：</strong>
          @if ($question->source_type == 1)
            官方
          @elseif ($question->source_type == 2)
            维基百科
          @else
            标准读音
          @endif
        </div>
        @if (!empty($question->remark))
          <strong>备注：</strong>{{$question->remark}}
        @endif
      </div>
    </div>
    <div class="menu-bottom">
        @if ($next_question_id > 0)
          <a href="/questions/{{$next_question_id}}"><button class="btn btn-info full">下一单词</button></a>
        @else
          <a href="/issues/{{$question->issue_id}}/questions/"><button class="btn btn-info full">返回</button></a>
        @endif
    </div>
  </div>
</div>
@endsection