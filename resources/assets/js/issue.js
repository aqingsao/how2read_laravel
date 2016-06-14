(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope) {
    var vm = this;
    vm.nextQuestion = nextQuestion;
    activate();
    function activate(){
      vm.currentPage='kickoff';
    }

    function nextQuestion(){

    }
    
  }

})(window, window.angular);