<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.1, user-scalable=no, width=device-width">
    <meta name="description" content="@yield('description')"/>
    <meta name="keywords" content="@yield('keywords')"/>

    <title>@yield('title')</title>
    <link href="/static/fonts/iconfont.woff?v0.1.0" rel="stylesheet">
    <link href="/static/css/issue.min.css?v0.1.0" rel="stylesheet">
  </head>
  <body>
    @yield('content')

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="http://cdn.bootcss.com/ng-tags-input/3.1.1/ng-tags-input.min.js"></script>
    <script src="/static/js/issue.min.js?v0.1.0"></script>
  </body>
</html>