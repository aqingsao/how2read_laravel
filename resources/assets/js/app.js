(function (window, angular) {
  var app = angular.module('how2read', ['module.services', 'ngTagsInput'])
    .config(configCompileProvider)
    .config(configHttpProvider)
    .config(configureLocationProvider)
    .config(extendLog)
    .run(initApp);

  /* @ngInject */
  function configCompileProvider($compileProvider) {
    $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https|file|blob|cdvfile|http|chrome-extension|blob:chrome-extension):|data:image\//);
  }

  /* @ngInject */
  function configHttpProvider($httpProvider) {
    $httpProvider.defaults.headers.common['Content-Type'] = 'application/json';
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/json';
  }

  function configureLocationProvider($locationProvider){
    $locationProvider.html5Mode({enabled: false,requireBase: false});
  }

  /* @ngInject */
  function extendLog($provide) {
    $provide.decorator('$log', function ($delegate, $injector) {
      var _log = $delegate.log;
      $delegate.log = function (msg, forceLog) {
        _log(msg);
        return this;
      };
      return $delegate;
    });
  }

  function initApp($rootScope, $http, $window) {
    $rootScope.loadingPage = true;
    $rootScope.clickPage = function(){
      $rootScope.$broadcast('page_clicked', {});
    }
    $rootScope.goBack = function(url){
      if (document.referrer == "") {
        $window.location.href = url;
      }
      else{
        $window.history.back();
      }
    }
  }
})(window, window.angular);
