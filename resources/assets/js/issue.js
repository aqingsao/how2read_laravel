(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope, $http, $log) {
    var vm = this;
    vm.nextQuestion = nextQuestion;
    vm.kickoff = kickoff;
    activate();
    function activate(){
      vm.currentPage='kickoff';
      vm.questionIndex = 0;
      $http.get('/api/issues/1/questions').then(function(response){
        vm.questions = response;
      }, function(response){

      });
    }

    function kickoff(){
      vm.currentPage = 'question';
    }

    function nextQuestion(){
      vm.question = vm.questions[vm.questionIndex++];
    }
  }

})(window, window.angular);