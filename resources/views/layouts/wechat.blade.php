<!DOCTYPE html>
<html lang="en" ng-app='how2read'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1.1, user-scalable=no, width=device-width">
    <meta name="description" content="@yield('description')"/>
    <meta name="keywords" content="@yield('keywords')"/>

    <title>@yield('title')</title>
    <link href="/static/fonts/iconfont.woff?v0.1.6" rel="stylesheet">
    <link href="/static/css/issue.min.css?v0.1.6" rel="stylesheet">
  </head>
  <body>
  <div style='margin:0 auto;width:0px;height:0px;overflow:hidden;'>
    <img src="/static/images/how2readme-logo.jpeg" width='300' height="300">
  </div>
    @yield('content')

    <script src="http://cdn.bootcss.com/angular.js/1.5.6/angular.min.js"></script>
    <script src="/static/js/issue.min.js?v0.1.6"></script>
    <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?ab6d7338542fba4444d3eaebdcefdaf1";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
  </body>
</html>