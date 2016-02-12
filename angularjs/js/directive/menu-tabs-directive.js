angular.module('PLTWApp').directive('menuTabs', function () {
  return {
    restrict: 'EA',
    replace: true,
    transclude: true,
    templateUrl: 'templete/menuTab.html'
  };
});

angular.module('PLTWApp').directive('footer', function () {
  return {
    restrict: 'EA',
    replace: true,
    transclude: true,
    templateUrl: 'templete/footer.html'
  };
});

angular.module('PLTWApp').directive('modalDetail', function () {
  return {
    restrict: 'EA',
    replace: true,
    transclude: true,
    templateUrl: 'templete/modal.html'
  };
});

angular.module('PLTWApp').directive('formTemplate', function () {
  return {
    restrict: 'EA',
    replace: true,
    transclude: true,
    templateUrl: 'templete/form.html'
  };
});