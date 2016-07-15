(function (window, angular) {
  angular.module('how2read')
    .controller('AdminQuestionEditCtrl', AdminQuestionEditCtrl);

  function AdminQuestionEditCtrl($rootScope, $http, $log, $q, $location, $window, Utils) {
    var vm = this;
    vm.validateAll = validateAll;
    vm.quickValidateName = quickValidateName;
    vm.fullValidateName = fullValidateName;
    vm.validateChoiceName = validateChoiceName;
    vm.addChoice = addChoice;
    vm.removeChoice = removeChoice;
    vm.correctChoices = correctChoices;
    vm.wrongChoices = wrongChoices;
    vm.canSubmit = canSubmit;
    vm.submit = submit;
    vm.queryTags = queryTags;
    vm.addTag = addTag;
    activate();

    function activate(){
      vm.duplicateQuestions = []; 
      vm.question = {choices:[]};
      vm.sourceTypes = [{id:0, label:'其他'}, {id:1, label:'官网(作者)'}, {id:2, label:'维基百科'}, {id:3, label:'英语标准读音'}];
      var path = document.location.pathname.split("/");
      var name = path[3];
      $http.get('/api/questions/' + name).then(function(response){
        vm.question = response.data;
        vm.question.source_type = parseInt(vm.question.source_type);
        vm.question.choices.each(function(c){
          c.is_correct = c.is_correct == '1';
        })
        vm.tags = response.data.tags;
        vm.nameIsEmpty = false;
        vm.nameDuplicate = false;
      }, function(response){
      });
    }
    function correctChoices(){
      return vm.question.choices.filter(function(c){return c.is_correct});
    }
    function wrongChoices(){
      return vm.question.choices.filter(function(c){return !c.is_correct;});
    }
    function addChoice(){
      vm.question.choices.push({t: Date.now(), name_ipa: '', name_alias:'', name_cn: ''});
    }
    function removeChoice(choice){
      if(typeof(choice.id) != 'undefined'){
        vm.question.choices = vm.question.choices.filter(function(c){return c.id != choice.id});
      }
      else{
        vm.question.choices = vm.question.choices.filter(function(c){return c.t != choice.t});
      }
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
        if(!Utils.isBlank(response.data) && response.data.id != vm.question.id){
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
      if(Utils.isBlank(vm.question)){
        return;
      }
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
      if(vm.nameIsEmpty || vm.nameDuplicate || Utils.isBlank(vm.question)){
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
      vm.question.tags = vm.tags.map(function(t){return t.id});
      $http.put('/api/questions', vm.question).then(function(response){
        vm.processing = false;
        $window.location.href = '/questions/' + vm.question.name;
      }, function(response){
        vm.processing = false;
      });
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
