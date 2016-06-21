(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope, $http, $log) {
    var vm = this;
    vm.nextQuestion = nextQuestion;
    vm.nextQuestionText = nextQuestionText;
    vm.getChoiceName = getChoiceName;
    vm.vote = vote;
    vm.onVoteFinished = onVoteFinished;
    vm.showQuestionPage = showQuestionPage;
    vm.showResultPage = showResultPage;
    vm.clickPage = clickPage;
    vm.getCorrectCount = getCorrectCount;
    vm.getOverTakesRate = getOverTakesRate;
    activate();
    function activate(){
      vm.currentPage='kickoff';
      vm.issueId = 1;
      vm.userId=1;
      vm.questionIndex = -1;
      vm.questions = [];
      vm.summary = {user_count: 0, correct_rate: 0};
      $http.get('/api/issues/' + vm.issueId + '/questions').then(function(response){
        vm.questions = response.data;
        $log.log(vm.questions);
      }, function(response){
      });
      $http.get('/api/issues/1/summary').then(function(response){
        vm.summary = response.data;
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
        question.is_correct = choice.id == response.data.correct_id;
        choice.is_voted = true;
        question.choices.forEach(function(choice){
          choice.is_correct = choice.id == response.data.correct_id;
        });

        if(vm.questionIndex >= vm.questions.length - 1){
          vm.onVoteFinished();
        }
      }, function(response){
        vm.voting = false;
      });
    }

    function onVoteFinished(){
      var rate = vm.questions.length > 0 ? vm.getCorrectCount() / vm.questions.length * 100: 100;

      $http.get('/api/issues/' + vm.issueId + '/over_takes/' + rate).then(function(response){
        vm.summary.over_takes_rate = vm.getOverTakesRate(response.over_takes);
        document.title = document.title+', 您答对了'+vm.questions.length+'个中的'+vm.getCorrectCount()+'个，战胜了'+vm.summary.over_takes_rate+'%的好友';
      }, function(response){
      });
    }

    function getOverTakesRate(over_takes){
      if(over_takes == 0){
        return 0;
      }
      if(vm.summary.user_count == 0){
        return 100;
      }
      if(over_takes >= vm.summary.user_count){
        return 100;
      }
      return over_takes / vm.summary.user_count * 100;
    }
    function getCorrectCount(){
      return vm.questions.filter(function(q){
        return q.is_correct;
      }).length;
    }
    function showResultPage(){
      vm.currentPage = 'result';
    }

    function clickPage(){
      
    }
  }

})(window, window.angular);
