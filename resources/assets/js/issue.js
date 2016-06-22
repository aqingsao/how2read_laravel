(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope, $http, $log, Utils) {
    var vm = this;
    vm.getIssueCorrectRate = getIssueCorrectRate;
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
    vm.getDescription = getDescription;
    vm.playAudio = playAudio;
    activate();
    function activate(){
      vm.currentPage='kickoff';
      vm.issueId = 1;
      vm.questionIndex = -1;
      vm.issue = {questions: []};
      vm.summary = {user_count: 0, correct_rate: 0};
      $http.get('/api/issues/' + vm.issueId).then(function(response){
        vm.issue = response.data;
        $log.log(vm.issue);
      }, function(response){
      });
      $http.get('/api/issues/' + vm.issueId + '/summary').then(function(response){
        vm.summary = response.data;
        $log.log(vm.issue.questions);
      }, function(response){
      });
    }

    function getIssueCorrectRate(){
      if(vm.summary.user_count <= 0){
        return 0;
      }
      return Math.min(vm.summary.correct_count / vm.summary.voted_count * 100, 100)
    }

    function showQuestionPage(){
      vm.currentPage = 'question';
      vm.nextQuestion();
    }

    function nextQuestion(){
      if(vm.issue.questions.length > 0 && vm.questionIndex >= (vm.issue.questions.length - 1)){
        vm.onVoteFinished();
        return;
      }

      vm.questionIndex++;
      vm.question = vm.issue.questions[vm.questionIndex];
    }

    function nextQuestionText(){
      if(vm.issue.questions.length > 0 && vm.questionIndex >= (vm.issue.questions.length - 1)){
        return '查看成绩';
      }
      return '下一题';
    }

    function getChoiceName(choice){
      return [choice.name_ipa, choice.name_alias, choice.name_cn].filter(function(e){return !Utils.isBlank(e);}).join(', ');
    }

    function vote(question, choice){
      if(vm.voting){
        return;
      }

      if(question.is_voted){
        vm.playAudio(choice);
        return;
      }

      vm.voting = true;
      $http.post('/api/issues/' + vm.issueId + '/' + question.id + '/' + choice.id + '/vote').then(function(response){
        var correctChoices = response.data;
        vm.voting = false;
        question.is_voted = true;
        question.is_correct = correctChoices.some(function(c){return c.id == choice.id});;
        choice.is_voted = true;
        question.choices.forEach(function(choice){
          correctChoice = correctChoices.find(function(c){return c.id == choice.id});
          if(!Utils.isBlank(correctChoice)){
            choice.is_correct = true;
            Utils.merge(choice, correctChoice);
            vm.question.correctChoice = correctChoice;
            $log.log(choice);
          }
        });
      }, function(response){
        if(response.status == 500){
          vm.serverError = true;
        }
        vm.voting = false;
      });
    }

    function onVoteFinished(){
      $http.post('/api/issues/' + vm.issueId + '/finish').then(function(response){
        vm.summary.over_takes_rate = vm.getOverTakesRate(response.data.over_takes);
        document.title = '您答对了'+vm.issue.questions.length+'个中的'+vm.getCorrectCount()+'个，战胜了'+vm.summary.over_takes_rate+'%的好友-程序员最容易读错的单词';
        vm.showResultPage();
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
      return Math.min(over_takes / vm.summary.user_count * 100, 100);
    }
    function getCorrectCount(){
      return vm.issue.questions.filter(function(q){
        return q.is_correct;
      }).length;
    }
    function showResultPage(){
      vm.currentPage = 'result';
    }

    function clickPage(){
      
    }

    function getDescription(){
      if(Utils.isBlank(vm.question.correctChoice)){
        return '';
      }
      var desc = '';
      if(vm.question.correctChoice.type == 1){
        desc = '权威来源：官网';
      }
      else if(vm.question.correctChoice.type == 2){
        desc = '权威来源：维基百科';
      }
      else{
        desc = '权威来源：标准发音';
      }

      if(!Utils.isBlank(vm.question.correctChoice.description)){
        desc = desc + '，备注：' + vm.question.correctChoice.description;
      }
      return desc;
    }

    function playAudio(choice){
      if(Utils.isBlank(choice) || Utils.isBlank(choice.audio_url)){
        return;
      }
      var audio = new Audio(choice.audio_url);
      audio.play();
    }
  }

})(window, window.angular);
