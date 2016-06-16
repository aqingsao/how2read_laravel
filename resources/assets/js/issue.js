(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope, $http, $log) {
    var vm = this;
    vm.nextQuestion = nextQuestion;
    vm.nextQuestionText = nextQuestionText;
    vm.getChoiceName = getChoiceName;
    vm.vote = vote;
    vm.showQuestionPage = showQuestionPage;
    vm.showResultPage = showResultPage;
    vm.clickPage = clickPage;
    activate();
    function activate(){
      vm.currentPage='kickoff';
      vm.questionIndex = -1;
      vm.questions = [];
      $http.get('/api/issues/1/questions').then(function(response){
        vm.questions = response.data;
        $log.log(vm.questions);
      }, function(response){

      });
    }

    function showQuestionPage(){
      vm.currentPage = 'question';
      vm.nextQuestion();
    }

    function nextQuestion(){
      if(vm.questions.length > 0 && vm.questionIndex >= (vm.questions.length - 1)){
        vm.showResultPage();
        return;
      }

      vm.questionIndex++;
      vm.question = vm.questions[vm.questionIndex];
    }

    function nextQuestionText(){
      if(vm.questions.length && vm.questionIndex >= (vm.questions.length - 1)){
        return '查看成绩';
      }
      return '下一题';
    }

    function getChoiceName(choice){
      return [choice.name, choice.name1].join(', ');
    }

    function vote(question, choice){
      if(question.is_voted || vm.voting){
        return;
      }

      vm.voting = true;
      $http.post('/api/questions/' + question.id + '/vote/' + choice.id).then(function(response){
        vm.voting = false;
        question.is_voted = true;
        choice.is_voted = true;
        question.choices.forEach(function(choice){
          choice.is_correct = choice.id == response.data.correct_id;
        });
      }, function(response){
        vm.voting = false;
      });
    }
    function showResultPage(){
      vm.currentPage = 'result';
    }

    function clickPage(){
      
    }
  }

})(window, window.angular);
