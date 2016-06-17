<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.1, user-scalable=no, width=device-width">

    <title>@yield('title')</title>
    <link href="/fonts/iconfont.woff" rel="stylesheet">
    <link href="/css/issue.min.css" rel="stylesheet">
  </head>
  <body>
    @yield('content')

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/js/issue.min.js"></script>
  </body>
</html>