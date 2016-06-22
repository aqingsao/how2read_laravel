@extends('layouts.wechat')
@section('title', '第一期-程序员最容易读错的单词')
@section('description', '我在挑战第一期，程序员最容易读错的单词，你也来试试吧')

@section('content')
<div class="issue-container" ng-controller="IssueCtrl as vm">
  <div class="page kickoff-page" ng-show="vm.currentPage=='kickoff'">
    <div class="header">
        <div class="sub-title" ng-bind="'第'+vm.issue.description+'期'">第一期</div>
        <h2 class="title">程序员最容易读错的单词</h2>
    </div>
    <div class="content">
      <div class="summary">
        <p><span ng-bind="vm.issue.questions.length">0</span>道题目</p>
        <p><span ng-bind="vm.summary.user_count">0</span>人参与</p>
        <p><span ng-bind="vm.getIssueCorrectRate()">0</span>%正确率</p>
      </div>
      <div class="kickoff-btn-container">
        <div class="kickoff-btn" ng-click="vm.showQuestionPage()">
          <span>
            开始         
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="page question-page ng-hide" ng-show="vm.currentPage=='question'">
    <div class="header">
        <div class="sub-title"><span ng-bind="vm.questionIndex+1">1</span>/<span ng-bind="vm.issue.questions.length">1</span></div>
        <h2 class="title" ng-bind="vm.question.name"></h2>
    </div>
    <div class="content has-menu-bottom">
      <div ng-bind="vm.question.description"></div>
      <div class="choices">
        <div class="choice" ng-repeat="choice in vm.question.choices">
          <div class="btn" ng-class="{'btn-default': !vm.question.is_voted || (!choice.is_correct && !choice.is_voted), 'btn-info':vm.question.is_voted && choice.is_correct, 'btn-danger':vm.question.is_voted && choice.is_voted && !choice.is_correct}" ng-click="vm.vote(vm.question, choice)">
            <i class="icon iconfont fl" ng-show="choice.is_correct && choice.audio_url != ''">&#xe623;</i>
            <span ng-bind="vm.getChoiceName(choice)"></span>
          </div>
        </div>
        <div ng-if="vm.question.is_voted" ng-bind="vm.getDescription()">
        </div>
      </div>
    </div>
    <div class="menu-bottom">
      <button class="btn btn-info full" ng-disabled="!vm.question.is_voted" ng-bind="vm.nextQuestionText()" ng-click="vm.nextQuestion()">下一题</button>
    </div>
  </div>

  <div class="page result-page ng-hide" ng-show="vm.currentPage=='result'" ng-init="vm.showShareLayer=true;">
    <div class="header">
      <div class="sub-title">第一期</div>
      <h2 class="title">程序员最容易读错的单词</h2>
    </div>
    <div class="content">
      <div class="margin-bottom-8">不过瘾？您可以</div>
      <ul class="operations">
        <li class="operation">
          <i class="icon iconfont">&#xe60d;</i>
          <a href="/issues">查看往期单词</a>
        </li>
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
    <div class="layer-share" ng-show="vm.showShareLayer">
      <div class="margin-bottom-8">您答对了<span ng-bind="vm.issue.questions.length">0</span>个中的<span ng-bind="vm.getCorrectCount()">0</span>个，战胜了<span ng-bind="vm.summary.over_takes_rate">0</span>%的好友</div>
      <div class="share-tips">点击右上角“…”，分享给好友</div>
    </div>
    <div class="layer-background" ng-show="vm.showShareLayer" ng-click="vm.showShareLayer=false;"></div>
  </div>
</div>
@endsection