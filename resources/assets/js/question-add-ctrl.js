(function (window, angular) {
  angular.module('how2read')
    .controller('QuestionAddCtrl', QuestionAddCtrl);

  function QuestionAddCtrl($rootScope, $http, $log, Utils) {
    var vm = this;
    vm.initQuestionPage = initQuestionPage;
    vm.validateAll = validateAll;
    vm.validateNameEmpty = validateNameEmpty;
    vm.validateNameEmptyAndUniq = validateNameEmptyAndUniq;
    vm.validateChoiceName = validateChoiceName;
    vm.addChoice = addChoice;
    vm.removeChoice = removeChoice;
    vm.canSubmit = canSubmit;
    vm.submit = submit;
    activate();
    function activate(){
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

    function validateNameEmpty(){
      vm.nameHasError = Utils.isBlank(vm.question.name);
      return vm.nameHasError;
    }
    function validateNameEmptyAndUniq(){
      if(vm.validateNameEmpty()){
        return;
      }
      $http.get('/api/questions/')
      vm.nameHasError = Utils.isBlank(vm.question.name);
    }

    function validateChoiceName(choice){
      choice.nameHasError = Utils.isBlank(choice.name);
      return choice.nameHasError;
    }
    function validateAll(){
      vm.validateNameEmpty();
      if(vm.question.correctChoiceChecked){
        vm.validateChoiceName(vm.question.correctChoice);
      }
      vm.question.choices.forEach(function(c){
        vm.validateChoiceName(c);
      });
      return vm.canSubmit();
    }
    function canSubmit(){
      if(vm.nameHasError){
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
