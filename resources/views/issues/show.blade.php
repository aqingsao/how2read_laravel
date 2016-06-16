<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>第一期-程序员最容易读错的单词</title>
    <link href="/css/issue.min.css" rel="stylesheet">
</head>
<body>
    <div class="issue-container" ng-controller="IssueCtrl as vm">
      <div class="page kickoff-page" ng-show="vm.currentPage=='kickoff'">
        <div class="header">
            <div class="sub-title">第一期</div>
            <h2 class="title">程序员最容易读错的单词</h2>
        </div>
        <div class="content">
          <div class="summary">
            <p><span>24</span>道题目</p>
            <p><span>1322</span>人参与</p>
            <p><span>50%</span>正确率</p>
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
            <div class="sub-title"><span ng-bind="vm.questionIndex+1">1</span>/<span ng-bind="vm.questions.length">1</span></div>
            <h2 class="title" ng-bind="vm.question.name"></h2>
        </div>
        <div class="content">
          <div ng-bind="vm.question.description"></div>
          <div class="choices">
            <div class="choice" ng-repeat="choice in vm.question.choices">
              <div class="btn" ng-class="{'btn-default': !vm.question.is_voted || (!choice.is_correct && !choice.is_voted), 'btn-success':vm.question.is_voted && choice.is_correct, 'btn-danger':vm.question.is_voted && choice.is_voted && !choice.is_correct}" ng-click="vm.vote(vm.question, choice)" ng-bind="vm.getChoiceName(choice)">
              </div>
            </div>
          </div>
          <div class="operation">
            <button class="btn btn-info fr" ng-disabled="!vm.question.is_voted" ng-bind="vm.nextQuestionText()" ng-click="vm.nextQuestion()">下一题</button>
          </div>
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
            <li class="operation"><a href="/issues/1">再玩一次</a></li>
            <li class="operation"><a href="/issues">查看往期单词</a></li>
            <li class="operation"><a href="/questions/add">添加不认识的单词</a></li>
          </ul>

          <div class="wechat-code">
            长按二维码，新单词上线我先知
            <img src="/images/qrcode.jpg" alt="程序员最容易读错的单词">
          </div>
        </div>
        <div class="layer-share" ng-show="vm.showShareLayer">
          <div class="margin-bottom-8">您答对了18个中的17个，战胜了85%的好友</div>
          <div class="share-tips">点击右上角“…”，分享给好友</div>
        </div>
        <div class="layer-background" ng-show="vm.showShareLayer" ng-click="vm.showShareLayer=false;"></div>
      </div>
    </div>

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/js/issue.min.js"></script>
</body>
</html>