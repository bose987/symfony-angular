 /**
 * @name CartCtrl
 * @description This is Template Cart helps to handdle the events of Cart page
 */
 angular.module('PLTWApp')
  	.controller('CartCtrl', function($scope, toastr, $http, $state, $window) {
  		var vm =this;
  		vm.cartAdd = cartAdd;
  		vm.qunty_inpt = [];
  		vm.cartRemove = cartRemove;
  		vm.removeCart = removeCart;
  		vm.placeOrder = placeOrder;
  		vm.cardAddText = cardAddText;
  		vm.grandTotal = 0;
  		vm.isCartEmpty = false;
  		vm.countCart = JSON.parse(localStorage.getItem("countCart"));
  		/**
		* @desc calculate grandtotal
		*/
  		if(localStorage.getItem("countCart")) {
			for(var i=0; i< vm.countCart.length; i++ ) {
				vm.grandTotal +=  vm.countCart[i].totalcost;
				vm.qunty_inpt[vm.countCart[i].id] = vm.countCart[i].qnty;
			}
			vm.isCartEmpty  = true;
		}else {
			vm.isCartEmpty  = false;
		}

		/**
		* @name cartAdd
		* @desc increase quantity of product
		* @param id - cart item id that used to increase quantity
		* @returns grandTotal
		*/	
  		function cartAdd (id) {
  			vm.grandTotal = 0;
  			for(var i=0; i< vm.countCart.length; i++ ) {  				
  				var qnty_available = parseInt(vm.countCart[i].quantity_available ,10);
				if(vm.countCart[i].id == id){					
					if(qnty_available != 0){
						if(vm.countCart[i].qnty >= 1  ) {
							vm.countCart[i].qnty +=1;
							vm.countCart[i].quantity_available -=1;
							var totalcost = 1;
							if(vm.countCart[i].discount !=  undefined){
							 	totalcost = vm.countCart[i].cost - ((vm.countCart[i].discount/100) * vm.countCart[i].cost);
							}else {
								totalcost = vm.countCart[i].cost;
							}
							vm.countCart[i].totalcost = totalcost * vm.countCart[i].qnty;
							vm.qunty_inpt[vm.countCart[i].id] = vm.countCart[i].qnty;				
							localStorage.setItem("countCart", JSON.stringify(vm.countCart));	
						}
					}else {
						toastr.error("You can buy only "+ vm.countCart[i].qnty + " only");
					}					
				}
				vm.grandTotal +=  vm.countCart[i].totalcost;
			}		
  		}
  		/**
		* @name cartRemove
		* @desc decrease quantity of product
		* @param id - cart item id that used to decrease quantity
		* @returns grandTotal
		*/	
  		function cartRemove (id) {
  			vm.grandTotal = 0;
  			for(var i=0; i< vm.countCart.length; i++ ) {  				
				if(vm.countCart[i].id == id){
					var qnty_available = parseInt(vm.countCart[i].quantity_available ,10);
					if(vm.countCart[i].qnty >= 2  ) {
						vm.countCart[i].qnty -=1;
						vm.countCart[i].quantity_available +=1;
						var totalcost = 1;
						if(vm.countCart[i].discount !=  undefined) {
						 	totalcost = vm.countCart[i].cost - ((vm.countCart[i].discount/100) * vm.countCart[i].cost);
						}else {
							totalcost = vm.countCart[i].cost;
						}
						vm.countCart[i].totalcost = totalcost * vm.countCart[i].qnty;
						vm.qunty_inpt[vm.countCart[i].id] = vm.countCart[i].qnty;
						localStorage.setItem("countCart", JSON.stringify(vm.countCart));	
					}					
				}
				vm.grandTotal +=  vm.countCart[i].totalcost;
			}		
  		}
  		/**
		* @name removeCart
		* @desc remove product form cart
		* @param id - id is that product remove from cart
		* @returns grandTotal
		*/
  		function removeCart (id) {
  			var removeCartObj =[];
  			var k  = 0;
  			for(var i=0; i< vm.countCart.length; i++ ) {  				
				if(vm.countCart[i].id != id){
					removeCartObj[k++] = vm.countCart[i];
				}
			}
			vm.countCart = removeCartObj;
			localStorage.setItem("countCart", JSON.stringify(vm.countCart));
			if(vm.countCart.length == 0){				
  				localStorage.removeItem('countCart');
			}
			$window.location.reload();	
  		}
  		/**
		* @name placeOrder
		* @desc redirect to confirmorder page
		*/		
		function placeOrder () {
			$state.go("confirmorder");
		}
		/**
		* @name cardAddText
		* @desc change quantity of product from Text box
		* @param id - id is that product quantity to be change
		* @returns grandTotal
		*/
		function cardAddText (id) {
			vm.countCart = JSON.parse(localStorage.getItem("countCart"));
			var INTEGER_REGEXP = /^\-?\d+$/;
			if(INTEGER_REGEXP.test( vm.qunty_inpt[id] )){
				vm.qunty_inpt[id] = parseInt(vm.qunty_inpt[id]);				
					if(vm.qunty_inpt[id] > 0){		
						vm.grandTotal = 0;
						angular.forEach(vm.countCart, function(value, i) {  
							if(value.id === id){	
								var totalQnty = parseInt(vm.countCart[i].quantity_available) + parseInt(vm.countCart[i].qnty);
								if(totalQnty >= vm.qunty_inpt[id]){
									vm.countCart[i].qnty = vm.qunty_inpt[id];
									vm.countCart[i].quantity_available = totalQnty - vm.qunty_inpt[id];
									var totalcost = 1;
									if(vm.countCart[i].discount !=  undefined) {
									 	totalcost = vm.countCart[i].cost - ((vm.countCart[i].discount/100) * vm.countCart[i].cost);
									}else {
										totalcost = vm.countCart[i].cost;
									}
									vm.countCart[i].totalcost = totalcost * vm.countCart[i].qnty;
								}else {
					 				for(var j=0; j< vm.countCart.length; j++ ) {  				
										if(vm.countCart[j].id == id){
											vm.qunty_inpt[id] = vm.countCart[j].qnty;
										}
									}
				 				}
							}
							vm.grandTotal +=  vm.countCart[i].totalcost;
						});
					}else {
		 				for(var i=0; i< vm.countCart.length; i++ ) {  				
							if(vm.countCart[i].id == id){
								vm.qunty_inpt[id] = vm.countCart[i].qnty;
							}
							vm.grandTotal +=  vm.countCart[i].totalcost;
						}
	 				}
			}else {
					for(var i=0; i< vm.countCart.length; i++ ) {  				
					if(vm.countCart[i].id == id){
						vm.qunty_inpt[id] = vm.countCart[i].qnty;
					}
					vm.grandTotal +=  vm.countCart[i].totalcost;
				}
			}	
			localStorage.setItem("countCart", JSON.stringify(vm.countCart));	
		}  		
  });
