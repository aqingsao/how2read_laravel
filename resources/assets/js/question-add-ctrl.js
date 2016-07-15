(function (window, angular) {
  angular.module('how2read')
    .controller('QuestionAddCtrl', QuestionAddCtrl);

  function QuestionAddCtrl($rootScope, $http, $log, $q, Utils) {
    var vm = this;
    vm.initQuestionPage = initQuestionPage;
    vm.validateAll = validateAll;
    vm.quickValidateName = quickValidateName;
    vm.fullValidateName = fullValidateName;
    vm.validateChoiceName = validateChoiceName;
    vm.correctChoices = correctChoices;
    vm.wrongChoices = wrongChoices;
    vm.addChoice = addChoice;
    vm.removeChoice = removeChoice;
    vm.validateCorrectChoices = validateCorrectChoices;
    vm.canSubmit = canSubmit;
    vm.submit = submit;
    vm.addQuestion = addQuestion;
    vm.queryTags = queryTags;
    vm.addTag = addTag;
    activate();
    function activate(){
      vm.duplicateQuestions = []; 
      vm.sourceTypes = [{id:0, label:'其他'}, {id:1, label:'官网(作者)'}, {id:2, label:'维基百科'}, {id:3, label:'英语标准读音'}];
      var params = document.location.search.match(/question=(.+)/);
      if(params){
        vm.initQuestionPage(params[1]);
      }      
      else{
        vm.initQuestionPage('');
      }
    }
    function initQuestionPage(name){
      vm.currentPage='add';
      vm.question = {name: name, description: '', source_type: 0, source_url: '', remark:'', choices: [{t: Date.now(), is_correct: true, name_ipa: '', name_alias:'', name_cn: ''}, {t: Date.now(), is_correct: false, name_ipa: '', name_alias:'', name_cn: ''}]};
      vm.correctChoiceChecked = true;
      vm.nameIsEmpty = true;
      vm.nameDuplicate = false;
      vm.tags = [];
    }
    function correctChoices(){
      return vm.question.choices.filter(function(c){return c.is_correct;});
    }
    function wrongChoices(){
      return vm.question.choices.filter(function(c){return !c.is_correct;});
    }
    function addChoice(is_correct){
      vm.question.choices.push({t: Date.now(), is_correct: is_correct, name_ipa: '', name_alias:'', name_cn: ''});
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

      $http.get('/api/questions/' + vm.question.name).then(function(response){
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
      if(choice.is_correct && !vm.correctChoiceChecked){
        choice.nameHasError = false;
      }
      else{
        choice.nameHasError = Utils.isBlank(choice.name_ipa) && Utils.isBlank(choice.name_alias) && Utils.isBlank(choice.name_cn);
      }
      return choice.nameHasError;
    }

    function validateCorrectChoices(){
      if(!vm.correctChoiceChecked){
        vm.question.choices.forEach(function(c){
          if(c.is_correct){
            vm.validateChoiceName(c);            
          }
        });
      }
    }
    function validateAll(){
      if(Utils.isBlank(vm.question)){
        return;
      }
      vm.quickValidateName();
      vm.question.choices.forEach(function(c){
        vm.validateChoiceName(c);
      });
      return vm.canSubmit();
    }

    function canSubmit(){
      if(vm.nameIsEmpty || vm.nameDuplicate || Utils.isBlank(vm.question)){
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
      vm.question.tags = vm.tags.map(function(t){return t.id});
      if(!vm.correctChoiceChecked){
        vm.question.choices = vm.question.choices.filter(function(c){
          return !c.is_correct;
        });
      }
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
    function queryTags(query){
      return $http.get('/api/tags/' + query);
    }

    function addTag(tag){
      $http.post('/api/tags', tag).then(function(response){
        var newTag = response.data;
        var t = vm.tags.find(function(t){return t.name == tag.name});
        t.name = newTag.name;
        t.id = newTag.id;
      }, function(response){
        $log.log('failed to create');
      });
    }
  }

})(window, window.angular);
