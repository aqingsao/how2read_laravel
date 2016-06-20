(function (window, angular) {
  angular.module('how2read')
    .controller('QuestionAddCtrl', QuestionAddCtrl);

  function QuestionAddCtrl($rootScope, $http, $log, Utils) {
    var vm = this;
    vm.initQuestionPage = initQuestionPage;
    vm.validateAll = validateAll;
    vm.quickValidateName = quickValidateName;
    vm.fullValidateName = fullValidateName;
    vm.validateChoiceName = validateChoiceName;
    vm.getNameDuplicateDesc = getNameDuplicateDesc;
    vm.addChoice = addChoice;
    vm.removeChoice = removeChoice;
    vm.canSubmit = canSubmit;
    vm.submit = submit;
    activate();
    function activate(){
      vm.duplicateQuestions = [];
      vm.nameIsEmpty = true;
      vm.nameDuplicate = false;
      vm.initQuestionPage();
    }
    function initQuestionPage(){
      vm.question = {name: '', description: '', correctChoiceChecked:true, correctChoice: {name: '', name1:''}, choices: [{t: Date.now(), name: '', name1: ''}]}; 
      vm.currentPage='add';
    }
    function addChoice(){
      vm.question.choices.push({t: Date.now(), name: '', name1: ''});
    }
    function removeChoice(choice){
      vm.question.choices = vm.question.choices.filter(function(c){return c.t != choice.t});
    }

    function quickValidateName(){
      vm.nameIsEmpty = Utils.isBlank(vm.question.name);
      if(!vm.nameIsEmpty){
        vm.nameDuplicate = vm.duplicateQuestions.some(function(question){
          return question.name.toLowerCase() == vm.question.name.toLowerCase();
        });
      }
      return vm.nameIsEmpty || vm.nameDuplicate;
    }
    function fullValidateName(){
      if(vm.quickValidateName()){
        return;
      }

      $http.get('/api/questions/find_by_name/' + vm.question.name).then(function(response){
        if(!Utils.isBlank(response.data)){
          vm.duplicateQuestions.push(response.data);
          vm.nameDuplicate = true;
        }
        else{
          vm.nameDuplicate = false;
        }
      }, function(response){
      });
    }

    function validateChoiceName(choice){
      choice.nameHasError = Utils.isBlank(choice.name);
      return choice.nameHasError;
    }
    function validateAll(){
      vm.quickValidateName();
      if(vm.question.correctChoiceChecked){
        vm.validateChoiceName(vm.question.correctChoice);
      }
      vm.question.choices.forEach(function(c){
        vm.validateChoiceName(c);
      });
      return vm.canSubmit();
    }

    function getNameDuplicateDesc(){
      var question = vm.duplicateQuestions.find(function(question){
        return question.name.toLowerCase() == vm.question.name.toLowerCase();
      });
      if(Utils.isBlank(question)){
        return '';
      }
      if(question.issue_id <= 0){
        return question.name + '，已经存在，尚未发布';
      }
      else{
        return question.name + '，收录于第' + question.issue_id + '期';
      }
    }
    function canSubmit(){
      if(vm.nameIsEmpty || vm.nameDuplicate){
        return false;
      }
      if(vm.question.correctChoiceChecked && vm.question.correctChoice.nameHasError){
        return false;
      }
      if(vm.question.choices.some(function(c){
        return c.nameHasError;
      })){
        return false;
      }
      return true;
    }

    function submit(){
      if(vm.processing){
        return;
      }
      if(!vm.validateAll()){
        return;
      }
      vm.processing = true;
      $http.post('/questions', vm.questions).then(function(response){
        vm.processing = false;
        vm.currentPage='result';
      }, function(response){
        vm.processing = false;
      });
    }
  }

})(window, window.angular);
