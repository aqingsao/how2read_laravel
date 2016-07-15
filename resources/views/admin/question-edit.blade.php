@extends('layouts.admin')
@section('title', '修改单词-{{$question_name}}')
@section('description', '修改单词{{$question_name}}')
@section('keywords', '修改,单词,程序员,读错')

@section('content')
<div class="container question-container" ng-controller="AdminQuestionEditCtrl as vm">
  <div class="menu-top">
    <div class="menu-container bg-info">
      修改单词-{{$question_name}}
    </div>
  </div>

  <div class="page question-add-page has-menu-top has-menu-bottom" ng-show="vm.currentPage=='add'">
    <div class="content">
      <form class="form-horizontal" action="/questions" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
          <div class="label">名称</div>
          <div class="input-container" ng-class="{'has-error': vm.nameisEmpty || vm.nameDuplicate}">
            <input type="text" ng-model="vm.question.name" ng-change="vm.quickValidateName()" ng-blur="vm.fullValidateName()" placeholder="如Nginx">
          </div>
          <div class="has-error ng-hide" ng-show="vm.nameDuplicate">该单词已存在</div>
        </div>
        <div class="form-group">
          <div class="label">简单描述</div>
          <div class="input-container">
            <textarea ng-model="vm.question.description" placeholder="如Nginx是一个高性能的HTTP和反向代理服务器"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="label">标签</div>
            <tags-input ng-model="vm.tags" display-property="name" placeholder="添加标签" on-tag-added="vm.addTag($tag)">
              <auto-complete source="vm.queryTags($query)" max-results-to-show="10"></auto-complete>
            </tags-input>
        </div>
        <div class="form-group">
          <div class="label">
            <label for="correctChoice">正确读音<small>（下面至少填一项）</small></label>
          </div>
          <div ng-show="vm.question.correctChoiceChecked">
            <div class="multi-line-form">
              <div class="line clearfix">
                <select class="input full" ng-options="item.id as item.label for item in vm.sourceTypes" ng-model="vm.question.source_type"></select>
              </div>
              <div class="line clearfix">
                <label>权威地址</label>
                <input type="text" ng-model="vm.question.source_url" placeholder="如'http://wikipedia.org/wiki/Nginx'">
              </div>
              <div class="line clearfix">
                <label>读音备注</label>
                <input type="text" ng-model="vm.question.remark" placeholder="如'D不发音'">
              </div>
            </div>

            <div class="multi-line-form shrink-left" ng-class="{'has-error': vm.question.correctChoice.nameHasError}">
              <div class="line clearfix">
                  <label>国际音标</label>
                  <input type="text" ng-model="vm.question.correctChoice.name_ipa" placeholder="如'[ˈendʒɪnˌeks]'" ng-change="vm.validateChoiceName(vm.question.correctChoice)" ng-blur="vm.validateChoiceName(vm.question.correctChoice)">
              </div>
              <div class="line clearfix">
                  <label>缩略读法</label>
                  <input type="text" ng-model="vm.question.correctChoice.name_alias" placeholder="如'Engine-X'" ng-change="vm.validateChoiceName(vm.question.correctChoice)" ng-blur="vm.validateChoiceName(vm.question.correctChoice)">
              </div>
              <div class="line clearfix">
                  <label>中文读法</label>
                  <input type="text" ng-model="vm.question.correctChoice.name_cn" placeholder="如'恩真-埃克斯'" ng-change="vm.validateChoiceName(vm.question.correctChoice)" ng-blur="vm.validateChoiceName(vm.question.correctChoice)">
              </div>
              <div class="line clearfix">
                  <label>音频地址</label>
                  <input type="text" ng-model="vm.question.correctChoice.audio_url" placeholder="可选，如'http://dictionary.cambridge.org/nginx.mp3'">
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="label">常见的错误读音<small>（下面至少填一项）</small></div>
          <div class="shrink-container" ng-class="{'shrinking':vm.question.choices.length > 1}" ng-repeat="choice in vm.question.choices">
            <div class="multi-line-form shrink-left" ng-class="{'has-error': choice.nameHasError}">
              <div class="line clearfix">
                  <label>国际音标</label>
                  <input type="text" ng-model="choice.name_ipa" ng-change="vm.validateChoiceName(choice)" ng-blur="vm.validateChoiceName(choice)" placeholder="如'[ˈendʒɪnˌeks]'">
              </div>
              <div class="line clearfix">
                  <label>缩略读法</label>
                  <input type="text" ng-model="choice.name_alias" placeholder="如'Engine-X'" ng-change="vm.validateChoiceName(choice)" ng-blur="vm.validateChoiceName(choice)">
              </div>
              <div class="line clearfix">
                  <label>中文读法</label>
                  <input type="text" ng-model="choice.name_cn" placeholder="如'恩真-埃克斯'" ng-change="vm.validateChoiceName(choice)" ng-blur="vm.validateChoiceName(choice)">
              </div>
            </div>
            <a class="shrink-right lines-3" href="javascript:;" ng-click="vm.removeChoice(choice)">删除</a>
          </div>

          <a href="javascript:;" class="add-btn" ng-click="vm.addChoice()">
            <i class="icon iconfont">&#xe608;</i>
            添加错误读音
          </a>
        </div>
      </form>
    </div>
    <div class="menu-bottom">
      <div class="menu-container bg-info">
        <button class="btn btn-info full" ng-disabled="!vm.canSubmit()" ng-click="vm.submit()">提交</button>
      </div>
    </div>
  </div>
</div>

  <!-- TODO: Current Tasks -->
@endsection