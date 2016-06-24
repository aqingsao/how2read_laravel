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
    vm.addChoice = addChoice;
    vm.removeChoice = removeChoice;
    vm.canSubmit = canSubmit;
    vm.submit = submit;
    vm.addQuestion = addQuestion;
    activate();
    function activate(){
      vm.duplicateQuestions = []; 
      vm.sourceTypes = [{id:0, label:'英语标准读音'}, {id:1, label:'官网'}, {id:2, label:'维基百科'}];
      vm.initQuestionPage();
    }
    function initQuestionPage(){
      vm.nameIsEmpty = true;
      vm.nameDuplicate = false;
      vm.question = {name: '', description: '', source_type: 0, source_url: '', remark:'', correctChoiceChecked:true, correctChoice: {name_ipa: '', name_alias:'', name_cn: '', audio_url:''}, choices: [{t: Date.now(), name_ipa: '', name_alias:'', name_cn: ''}]}; 
      vm.currentPage='add';
    }
    function addChoice(){
      vm.question.choices.push({t: Date.now(), name_ipa: '', name_alias:'', name_cn: ''});
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
      choice.nameHasError = Utils.isBlank(choice.name_ipa) && Utils.isBlank(choice.name_alias) && Utils.isBlank(choice.name_cn);
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
      $http.post('/api/questions', vm.question).then(function(response){
        vm.processing = false;
        vm.currentPage='result';
      }, function(response){
        vm.processing = false;
      });
    }

    function addQuestion(){
      vm.initQuestionPage();
    }

  }

})(window, window.angular);
