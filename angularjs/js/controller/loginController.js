 /**
 * @name LoginCtrl
 * @description This is Template Login helps to handdle the events of Login page
 */
 angular.module('PLTWApp')
  	.controller('LoginCtrl', function($scope, common, $location, usSpinnerService, $auth, httpConfig, toastr) {
  		var vm = this;
  		vm.uname="";
  		vm.pwd="";
  		vm.ctrlName = 'LoginCtrl';
  		vm.login = login;
  		vm.sociallogin = sociallogin;
      /**
      * @name login
      * @desc user login
      */        
  		function login() {
        usSpinnerService.spin('spinner-1');
        var user =  {email: vm.uname, password: vm.pwd}
  			$auth.login(user)
          .then(function(response) {
            if(response.data.error){
              toastr.success('Username or password is incorrect');
              usSpinnerService.stop('spinner-1');
                return;   
            }
            common.loginCheck(httpConfig.login,"Please login");
            toastr.success('You have successfully signed in');
            $location.path('/');
        })
        .catch(function(response) {
          toastr.error("Error while login");
          usSpinnerService.stop('spinner-1');
        });
  		}
      /**
      * @name sociallogin
      * @desc search as per category
      * @param provider - provider from you like to login
      */  
  		function sociallogin(provider) {  			
  			$auth.authenticate(provider)
          .then(function() {
            toastr.success('You have successfully signed in');            
            $location.path('/');
          })
          .catch(function(response) {
            console.log(response);
          });
      }
  });