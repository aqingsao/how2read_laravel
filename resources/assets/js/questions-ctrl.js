(function (window, angular) {
  angular.module('how2read')
    .controller('QuestionsCtrl', QuestionsCtrl);

  function QuestionsCtrl($rootScope, $http, $log, $location, Utils) {
    var vm = this;
    activate();
    function activate(){
      var path = $location.path().split("/");
      vm.issueId = path[path.length-2];
      vm.issue = {questions: []};
      $http.get('/api/issues/' + vm.issueId).then(function(response){
        vm.issue = response.data;
      }, function(response){
      });
    }
  }

})(window, window.angular);
