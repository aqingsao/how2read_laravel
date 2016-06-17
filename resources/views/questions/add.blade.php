@extends('layouts.wechat')
@section('title', '添加单词-程序员最容易读错的单词')
@section('content')
<div class="question-container" ng-controller="QuestionAddCtrl as vm">
  <div class="menu-top bg-info">
    添加单词
    <a class="left" href="/issues/">
      <i class="icon iconfont">&#xe65a;</i>
      返回
    </a>
  </div>
  <div class="page question-add-page has-menu-top has-menu-bottom" ng-show="vm.currentPage=='add'">
    <div class="content">
      @include('common.errors')
      <form class="form-horizontal" action="/questions" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
          <div class="label">名称</div>
          <div class="input-container" ng-class="{'has-error': vm.nameHasError}">
            <input type="text" ng-model="vm.question.name" ng-change="vm.validateName()" ng-blur="vm.validateName()" placeholder="如Nginx">
          </div>
        </div>
        <div class="form-group">
          <div class="label">简单描述</div>
          <div class="input-container">
            <textarea ng-model="vm.question.description" placeholder="可选，如Nginx是一个高性能的HTTP和反向代理服务器"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="label">
            <input id="correctChoice" ng-model="vm.question.correctChoiceChecked" type="checkbox" class="margin-right-8"></input>
            <label for="correctChoice">我知道正确读音</label>
          </div>
          <div class="shrink-container" ng-show="vm.question.correctChoiceChecked">
            <div class="multi-line-form shrink-left">
              <div class="line clearfix" ng-class="{'has-error': vm.question.correctChoice.nameHasError}">
                  <label>读音</label>
                  <input type="text" ng-model="vm.question.correctChoice.name" placeholder="如'Engine-X'" ng-change="vm.validateChoiceName(vm.question.correctChoice)" ng-blur="vm.validateChoiceName(vm.question.correctChoice)">
              </div>
              <div class="line clearfix">
                  <label>别名</label>
                  <input type="text" ng-model="vm.question.correctChoice.name1" placeholder="可选，如'恩真-埃克斯'">
              </div>
            </div>
            <a class="shrink-right" href="javascript:;" ng-click="vm.toggleProduct(product, false)">删除</a>
          </div>

        </div>
        <div class="form-group">
          <div class="label">常见的错误读音</div>
          <div class="shrink-container" ng-class="{'shrinking':vm.question.choices.length > 1}" ng-repeat="choice in vm.question.choices">
            <div class="multi-line-form shrink-left">
              <div class="line clearfix" ng-class="{'has-error': choice.nameHasError}">
                  <label>读音</label>
                  <input type="text" ng-model="choice.name" ng-change="vm.validateChoiceName(choice)" ng-blur="vm.validateChoiceName(choice)" placeholder="如'Engine-X'">
              </div>
              <div class="line clearfix">
                  <label>别名</label>
                  <input type="text" ng-model="choice.name1" placeholder="可选，如'恩真-埃克斯'">
              </div>
            </div>
            <a class="shrink-right" href="javascript:;" ng-click="vm.removeChoice(choice)">删除</a>
          </div>

          <a href="javascript:;" class="add-btn" ng-click="vm.addChoice()">
            <i class="icon iconfont">&#xe608;</i>
            添加错误读音
          </a>
        </div>
      </form>
    </div>
    <div class="menu-bottom">
      <button class="btn btn-info full" ng-disabled="!vm.canSubmit()" ng-click="vm.submit()">提交</button>
    </div>
  </div>

  <div class="page question-result-page has-menu-top" ng-show="vm.currentPage=='result'">
    <div class="content">
      <div class="margin-bottom-8">提交成功，谢谢您的参与！您可以</div>
      <ul class="operations">
        <li class="operation">
          <i class="icon iconfont">&#xe60d;</i>
          <a href="/issues">查看往期单词</a>
        </li>
        <li class="operation">
          <i class="icon iconfont">&#xe60d;</i>
          <a href="/questions/add" ng-click="vm.initQuestionPage()">
            继续添加
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>

  <!-- TODO: Current Tasks -->
@endsection