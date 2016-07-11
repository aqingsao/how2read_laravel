(function (window, angular) {
  angular.module('how2read')
    .controller('IssueCtrl', IssueCtrl);

  function IssueCtrl($rootScope, $http, $log, $location, Utils) {
    var vm = this;
    vm.nextQuestion = nextQuestion;
    vm.nextQuestionText = nextQuestionText;
    vm.shuffleQuestion = shuffleQuestion;
    vm.getChoiceName = getChoiceName;
    vm.vote = vote;
    vm.onVoteFinished = onVoteFinished;
    vm.showResultPage = showResultPage;
    vm.clickPage = clickPage;
    vm.getOverTakesRate = getOverTakesRate;
    vm.getSourceType = getSourceType;
    vm.playAudio = playAudio;
    activate();
    function activate(){
      vm.currentPage='kickoff';
      var path = document.location.pathname.split("/");
      vm.issueId = path[2];
      vm.questionIndex = 0;
    }

    function nextQuestion(question_name){
      if(Utils.isBlank(question_name)){
        vm.onVoteFinished();
        return;
      }
      if(vm.currentPage != 'question'){
        vm.currentPage = 'question';
      }

      $http.get('/api/questions/' + question_name).then(function(response){
        vm.question = vm.shuffleQuestion(response.data);
        vm.questionIndex++;
      }, function(response){
        vm.question = {};
        vm.questionIndex++;
      });
    }

    function nextQuestionText(){
      if(Utils.isBlank(vm.question) || Utils.isBlank(vm.question.next)){
        return '查看成绩';
      }
      return '下一题';
    }

    function getChoiceName(choice){
      return [choice.name_ipa, choice.name_alias, choice.name_cn].filter(function(e){return e != ''}).join(', ');
    }

    function vote(question, choice){
      if(!Utils.isBlank(vm.voting)){
        return;
      }

      if(question.is_voted){
        vm.playAudio(choice);
        return;
      }

      vm.voting = choice;
      $http.post('/api/questions/' + question.name + '/' + choice.id + '/vote').then(function(response){
        vm.voting = {};
          var correctChoices = response.data.correct_choices;
        question.is_voted = true;
        question.is_correct = correctChoices.some(function(c){return c == choice.id});
        choice.is_voted = true;
        question.choices.forEach(function(choice){
          choice.is_correct = correctChoices.some(function(c){return c == choice.id});
        });
      }, function(response){
        if(response.status == 500){
          vm.serverError = true;
        }
        vm.voting = {};
      });
    }

    function onVoteFinished(){
      $http.post('/api/issues/' + vm.issueId + '/vote_finish').then(function(response){
        vm.summary = response.data;
        $log.log(vm.summary);
        vm.summary.over_takes_rate = vm.getOverTakesRate(vm.summary);
        document.title = '您答对了'+vm.summary.question_count+'个中的'+vm.summary.correct_count+'个，战胜了'+vm.summary.over_takes_rate+'%的好友-程序员最容易读错的单词';
        vm.showResultPage();
      }, function(response){
      });
    }

    function getOverTakesRate(summary){
      if(summary.over_takes == 0){
        return 0;
      }
      if(summary.user_count == 0){
        return 100;
      }
      return Math.min(summary.over_takes / summary.user_count * 100, 100).toFixed(2);
    }

    function showResultPage(){
      vm.currentPage = 'result';
    }

    function clickPage(){
      
    }

    function getSourceType(){
      var desc = '';
      if(vm.question.source_type == 1){
        desc = '官网';
      }
      else if(vm.question.source_type == 2){
        desc = '维基百科';
      }
      else{
        desc = '标准发音';
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

    function shuffleQuestion(question){
      question.choices = Utils.shuffle(question.choices);
      return question;
    }
  }

})(window, window.angular);
