<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.1, user-scalable=no, width=device-width">

    <title>@yield('title')</title>
    <link href="/static/fonts/iconfont.woff?v0.0.5" rel="stylesheet">
    <link href="/static/css/issue.min.css?v0.0.5" rel="stylesheet">
  </head>
  <body>
    @yield('content')

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/static/js/issue.min.js?v0.0.5"></script>
  </body>
</html>