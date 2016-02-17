angular.module('PLTWApp').directive('topNavbar', function () {
  return {
    restrict: 'EA',
    replace: true,
    transclude: true,
    controller: 'NavCtrl as topnav',
    templateUrl: 'templete/nav.html'
  };
});