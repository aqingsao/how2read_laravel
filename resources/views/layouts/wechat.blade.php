<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.1, user-scalable=no, width=device-width">
    <meta name="description" content="我在挑战程序员最容易读错的单词，你也来试试吧"/>

    <title>@yield('title')</title>
    <link href="/static/fonts/iconfont.woff?v0.0.7" rel="stylesheet">
    <link href="/static/css/issue.min.css?v0.0.7" rel="stylesheet">
  </head>
  <body>
  <div style='margin:0 auto;width:0px;height:0px;overflow:hidden;'>
    <img src="http://7xq7uo.com1.z0.glb.clouddn.com/%40%2Fhow2readhow2readme-logo.jpeg" width='400' height="400">
  </div>
    @yield('content')

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/static/js/issue.min.js?v0.0.7"></script>
  </body>
</html>