 angular.module('PLTWApp')
  	.controller('ConfirmOrderCtrl', function($scope , $state , httpConfig, $payments,$http, toastr,usSpinnerService) {

  		var vm = this;
  		vm.billing = billing;
  		vm.shipping = shipping;
  		vm.order_payment = order_payment;
  		vm.billing_info ="";
  		vm.shipping_info ="";
  		vm.card_info = "";
  		vm.order_data = "";
      vm.order_id = "";
  		vm.order_sent = 0;
      vm.payment_id = "";
      vm.nopayment = true;
      vm.backtohome = backtohome;

      function backtohome () {
        $state.go('home');
      }
		function billing(billing_data){
			
			vm.billing_info = {
			 "name": billing_data.fullname,
			 "email" : billing_data.email,	
			 "address" : billing_data.address,
			 "phone" : billing_data.phone,
			 "city" : billing_data.city,
			 "state" : billing_data.state,
			 "zip" : billing_data.zip,
			 "country" : billing_data.country
			};
		}

		function shipping(shipping_data){
      usSpinnerService.spin('spinner-1');
			vm.shipping_info = {
			 "name": shipping_data.fullname,
			 "email" : shipping_data.email,	
			 "address" : shipping_data.address,
			 "phone" : shipping_data.phone,
			 "city" : shipping_data.city,
			 "state" : shipping_data.state,
			 "zip" : shipping_data.zip,
			 "country" : shipping_data.country
			};

		var countCart = JSON.parse(localStorage.getItem("countCart"));
		var cart_info = [];
    	angular.forEach(countCart, function(products, key) {
  				cart_info[key]={"id":products.id,"quantity":products.qnty};
		});
    		if(!vm.order_sent){
    			vm.order_sent =1;
    			$http({
                  method: 'post',
                  url: httpConfig.url + "secure/order/place",
                  headers: {'Content-Type': 'application/x-www-form-urlencoded;json'},
                  data: {"billing_address":vm.billing_info,"shipping_address":vm.shipping_info,
    					           "products":cart_info}
                }).success(function(response) {
                  vm.paymentForm=true;
                  usSpinnerService.stop('spinner-1');
                  vm.order_id = response.Order.id;                
                }).error(function(response) {
                    console.log(response);                                            
                });
    		}		              				
		}
    
    $scope.verified = function () {
    	return ($payments.verified() && vm.nopayment);
    }

    function order_payment(creditCard){
      vm.nopayment = false;
      usSpinnerService.spin('paymentSpin-1');
      $http({
          method: 'post',
          url: httpConfig.url + "secure/payment",
          headers: {'Content-Type': 'application/x-www-form-urlencoded;json'},
          data: { "order_id":vm.order_id,
                  "card_number":creditCard.number,
                  "cvv":creditCard.cvc,
                  "expiry_month":creditCard.month,
                  "expiry_year":creditCard.year
                }
          }).success(function(response) {
            usSpinnerService.stop('paymentSpin-1');
            if(response.Payment.id && response.Payment.order_id){
              var paymentinfo = [{
                            "payment_id":response.Payment.id,
                            "order_id" : response.Payment.order_id
                            }];
              localStorage.setItem("payment", JSON.stringify(paymentinfo));
              $state.go('orderdetails');
            }
                           
          }).error(function(response) {
            toastr.error("Api not responding");
            usSpinnerService.stop('paymentSpin-1');                                           
          });    
    }

});
