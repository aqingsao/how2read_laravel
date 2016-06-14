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
      <div class="kickoff-page" ng-show="vm.currentPage=='kickoff'">
        <div class="page-header">
            <div class="sub-title">第一期</div>
            <h2 class="title">程序员最容易读错的单词</h2>
        </div>
        <div class="summary">
          <p><span>24</span>道题目</p>
          <p><span>1322</span>人参与</p>
          <p><span>50%</span>正确率</p>
        </div>
        <div class="kickoff-btn-container">
          <div class="kickoff-btn" ng-click="vm.kickoff()">
            <span>
              开始         
            </span>
          </div>
        </div>
        <footer>
          微信：@aqingsao916，微博：@aqingsao
        </footer> 
      </div>
      <div class="question-page" ng-show="vm.currentPage=='question'">
        <div class="question">
          <div class="choices">
            <div class="choice" ng-click="vm.choose(question.id, choice.id)" ng-repeat="choice in question.choices">
            </div>
          </div>
        </div>
        <div class="button fr">下一题</div>
      </div>

      <div class="result-page" ng-show="vm.currentPage=='result'">
        <div class="share-dialog" ng-if="vm.showShareDialog">
          <div style="width: 90%; margin: 0 auto; font-size: 1.3em;">点击右上角“…”，分享给好友或朋友圈。</div>
        </div>  
      </div>
    </div>

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/js/issue.min.js"></script>
</body>
</html>