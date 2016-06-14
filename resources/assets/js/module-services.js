(function (window, angular) {
  angular.module('module.services', [])
    .service('Utils', Utils);

  function Utils($location, $log) {
    return {
      isBlank: isBlank,
      isMobileValid: isMobileValid,
      isNumber: isNumber,
      toPercent: toPercent
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
  }

})(window, window.angular);