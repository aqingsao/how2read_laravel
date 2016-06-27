(function (window, angular) {
  angular.module('how2read')
    .controller('QuestionCtrl', QuestionCtrl);

  function QuestionCtrl($rootScope, $http, $log, $location, Utils) {
    var vm = this;
    vm.playAudio = playAudio;
    activate();
    function activate(){
    }

    function playAudio(audio_url){
      if(Utils.isBlank(audio_url)){
        return;
      }
      var audio = new Audio(audio_url);
      audio.play();
    }
  }

})(window, window.angular);
