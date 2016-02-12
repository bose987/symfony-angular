 /**
 * @name yourOrdersCtrl
 * @description This is Template your orders helps to handdle the events of User orders page
 */
 angular.module('PLTWApp')
  	.controller('yourOrdersCtrl', function($scope, usSpinnerService, usrService, toastr, $auth, $location, $rootScope, $window) {
  		var vm = this;
      vm.show = false;
      vm.myOrder = [];
      function getMyOrders () {
        var req = usrService.getMyOrders();  
        req.then(function(responce){
            vm.myOrder = responce;
            usSpinnerService.stop('spinner-1');
            vm.show = true;
        });
      }
      getMyOrders();
});
