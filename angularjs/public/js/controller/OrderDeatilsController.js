 angular.module('PLTWApp')
  	.controller('OrderDeatilsCtrl', function($scope, $state, httpConfig, $http, usSpinnerService, toastr, $rootScope, ApiService, $location) {
     var vm = this;
     vm.ship_address = "";
	 vm.total_amount = "";
	 vm.order_item = "";
	 vm.order_id = "";
	 vm.show = false;

	/*iffy does loading order details from web server*/     
	(function(){
		usSpinnerService.spin('orderdetailsSpinner');
	  	var payment = JSON.parse(localStorage.getItem("payment"));
	  	var payment_id = "";
	  	var order_id = "";

    		angular.forEach(payment, function(value, key) {
  				payment_id = value.payment_id ;
  				order_id = value.order_id;
			});

			if (order_id){
				$http.get(httpConfig.url + "secure/order/"+order_id)
				.success(function(response) {
					vm.ship_address = response.shipping_details;
					vm.total_amount = response.amount;
					vm.order_item = response.order_items;	
					vm.order_id = response.id;	
					angular.forEach(vm.order_item, function(value, key) {
			  			var req = ApiService.getProductDetail(httpConfig.url + "api/product/" + value.product_id);
			   			req.then(function(data) {
							value.name = data.name;
							value.link = data.link;
							usSpinnerService.stop('spinner-2');
						});
					});
					vm.show = true;
  					localStorage.removeItem("payment");
  					localStorage.removeItem("countCart");
          			$rootScope.countCartdisp = 0;                   
					usSpinnerService.stop('orderdetailsSpinner');
				}).error(function(response) {
	           		toastr.error("Unable to fetch order details");
	        	});
			}else{
           		$location.path('/your-orders');
			}

	})();


});
