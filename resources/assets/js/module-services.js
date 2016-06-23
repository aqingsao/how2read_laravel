(function (window, angular) {
  angular.module('module.services', [])
    .service('Utils', Utils);

  function Utils($location, $log) {
    return {
      isBlank: isBlank,
      isMobileValid: isMobileValid,
      isNumber: isNumber,
      toPercent: toPercent, 
      merge: merge,
      shuffle: shuffle
    };
    function isMobileValid(mobile) {
      return /^1\d{10}$/.test(mobile);
    }

    function isBlank(str) {
      return (!str || /^\s*$/.test(str));
    }

    function isNumber(n) {
      return !isNaN(n);
    }

    function toPercent(value) {
      return Math.min(Math.round(value * 10000) / 100, 100);
    }
    function merge(obj1, obj2){
      for (var attrname in obj2) { 
        obj1[attrname] = obj2[attrname]; 
      }
    }
    function shuffle(array) {
      for (var i = array.length-1; i >=0; i--) {
        var r = Math.floor(Math.random()*(i+1)); 
        var temp = array[r]; 
        array[r] = array[i]; 
        array[i] = temp;
      }
      return array;
    }
  }

})(window, window.angular);