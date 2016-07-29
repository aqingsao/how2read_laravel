@extends('layouts.wechat')

@section('title'){{$question->name}}怎么读，如何正确发音@stop
@section('description') 如何读{{$question->name}}, pronounciation of {{$question->name}}, how to pronounce)@stop
@section('keywords') 怎么读, 正确读音, how to pronounce, how to read, pronunciation of {{$question->name}} @stop
<link href="/static/css/style.css" rel="stylesheet">

<div class="cockpit">
  <div class="radar">
    <ul class="objects">
      <li class="object html">html</li>
      <li class="object css">css</li>
      <li class="object javascript">javascript</li>
      <li class="object ruby">ruby</li>
      <li class="object java">java</li>
      <li class="object mysql">mysql</li>
      <li class="object backbone">backbone.js</li>
      <li class="object rails">rails</li>
      <li class="object ec2">ec2</li>
      <li class="object cucumber">cucumber</li>
    </ul>
  </div>
</div>