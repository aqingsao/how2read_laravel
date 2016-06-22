@extends('layouts.wechat')
@section('title')
  第{{$issue->description}}期-程序员最容易读错的单词
@stop
@section('description')
  我在挑战第{{$issue->description}}期，程序员最容易读错的单词，你也来试试吧
@stop

@section('content')
<div class="issue-container" ng-controller="QuestionsCtrl as vm">
  <div class="menu-top bg-info">
    第{{$issue->description}}期
    <a class="left" href="/issues/">
      <i class="icon iconfont">&#xe65a;</i>
      返回
    </a>
  </div>

  <div class="page questions-page has-menu-top" ng-show="vm.issue.questions.length > 0">
    <div class="content">
      <ul class="questions">
        <a class="question" href="/questions/@{{question.id}}" ng-repeat="question in vm.issue.questions">
          <strong class="name text-info" ng-bind="$index+1 + '. ' + question.name">
          </strong>
          <span class="description" ng-bind="question.description"></span>
        </a>
      </ul>
    </div>
  </div>
</div>
@endsection