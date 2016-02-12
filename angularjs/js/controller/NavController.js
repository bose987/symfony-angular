 /**
 * @name NavCtrl
 * @description This is Template Nav helps to handdle the events of Nav manu page derective
 */
angular.module('PLTWApp')
  	.controller('NavCtrl', function($scope, toastr, httpConfig, $auth, common, $location,$rootScope,$window) {
  		var vm = this;
  		vm.ctrlName = 'NavCtrl';
      vm.logout = logout;
      /**
      * @description check for login
      */
      common.loginCheck(httpConfig.login);
      /**
      * @description get count of cart user added
      */
      $rootScope.countCartdisp = 0;
      if(localStorage.getItem("countCart")) {
          countCart = JSON.parse(localStorage.getItem("countCart"));
          $rootScope.countCartdisp = countCart.length;                   
      }

      /**
      * @name logout
      * @description User logout
      */  
	   	function logout() {
  			if (!$auth.isAuthenticated()) { return; }
        $auth.logout()
          .then(function() {
            toastr.info('You have been logged out');
            $location.path('/');
            $window.location.reload();
        });
  		}
      /**
      * @name isActive
      * @description to show which tab is select
      */
      vm.isActive = function(route) {
          return route === $location.path();
      }
});
