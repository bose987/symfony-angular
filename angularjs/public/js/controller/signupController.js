 /**
 * @name signupCtrl
 * @description This is Template signup helps to handdle the events of signup page
 */
angular.module('PLTWApp')
  	.controller('signupCtrl', function($scope, toastr, $auth,$http, usSpinnerService, $location, httpConfig, $rootScope, common,$window) {
  		var vm = this;
  		vm.ctrlName = 'signupCtrl';
      vm.name = "";
      vm.email = "";
      vm.pwd = "";
      vm.conformpwd = "";
      vm.pwdeq = "false";
      vm.register = register;
      
       /**
      * @name register
      * @desc register new user
      */
      function register () {
        if(vm.name && vm.email && vm.pwd && vm.conformpwd){
          var user = {
                'name' : vm.name,
                'email' : vm.email,
                'password' : vm.pwd,
          };
          if(vm.pwd == vm.conformpwd){
            usSpinnerService.spin('spinner-1');            
            vm.pwdeq = "true";
              $http({
                  method: 'post',
                  url: httpConfig.url + "signup",
                  data : user
                })
                .success(function(response) {
                    $auth.setToken(response.token);
                    usSpinnerService.stop('spinner-1');
                    $location.path("/home");
                })
                .error(function(response) {
                    usSpinnerService.stop('spinner-1');
                    console.log(response);           
                    return false;
                });
          }else {
            toastr.error("password and conform password not equal");
            return false;
          }  
        }else{
          usSpinnerService.stop('spinner-1');
          toastr.error("Please enter all details");
        }
      }
});
