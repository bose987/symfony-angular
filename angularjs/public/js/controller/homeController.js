 /**
 * @name HomeCtrl
 * @desc This is Template home helps to handdle the events of home page
 */
 angular.module('PLTWApp')
  	.controller('HomeCtrl', function($scope,ApiService, $location, $uibModal, httpConfig, toastr, $filter, $window, $rootScope, usSpinnerService) {
  		var vm = this;
  		vm.ctrlName = 'HomeCtrl';
	    vm.details =[];
	   	vm.rating =[];	   	 	
	   	vm.addToCart = addToCart;
	   	vm.buyNow =buyNow;
	   	vm.detail =detail;
	   	vm.productDetail =[];
	   	vm.view = false;
	   	var countCart = [];	   	
	   	vm.category =[]; 		
	   	vm.isEmpty = false; 	
	   	vm.closeModel = closeModel;	
  		vm.showCatProd = showCatProd;
		vm.getAllProducts = getAllProducts;
  		var catTmp= [];
		var modalInstance=null;
  		/**
		* @name closeModel
		* @desc add selected product to cart and redirect to cart
		* @param id - product id
		* @returns product detail of selected product
		*/
		function closeModel() {
			modalInstance.dismiss('cancel');
		}

		/**
		* @name getAllCategories
		* @desc Get all categories
		* @returns categories
		*/
  		function getAllCategories () {
        	usSpinnerService.spin('spinner-1');
			var req = ApiService.getAllCategories(httpConfig.url + "api/category");
			req.then(function(response) {
				vm.category = response;
			});
		}
		/**
		* @name getAllProducts
		* @desc Get all products
		* @returns products
		*/
		function getAllProducts () {
			vm.isEmpty = false; 	
			var req = ApiService.getAllProducts(httpConfig.url + "api/products");
			req.then(function(response) {
				vm.details = response;
				usSpinnerService.stop('spinner-1');
			});
		}
		/**
		* @name init
		* @desc Initial function and activity to be perform 
		*/	
		function init () {
			getAllCategories();
			getAllProducts();
		}
		init();
		/**
		* @name showCatProd
		* @desc search as per category
		* @param Catid - category id that used to fetch the products details by Catid
		* @returns products details
		*/	
        function showCatProd (Catid) {	  
        	var req = ApiService.getCategoryProduct(httpConfig.url + "api/category/"+Catid+"/products");       	
        	vm.isEmpty = false; 	
        	usSpinnerService.spin('spinner-1');
        	vm.details =[];			
        	req.then(function(response) {
				vm.details = response;
        		if(response.length == 0){
					vm.isEmpty = true; 	
				}
				usSpinnerService.stop('spinner-1');
			});
  		}

		/**
		* @name addToCart
		* @desc add product to cart
		* @param id - product id
		* @param quantity_available - available quantity of products 
		* @param list_price - price of products 
		* @param discount - discount on product
		* @param link - image URL of product
		* @param name - name name
		*/	
		function addToCart (id,quantity_available,list_price,discount,link,name) {
			var totalcost = 1;
			if(discount !=  undefined){
			 	totalcost = list_price - ((discount/100) * list_price);
			}else {
				totalcost = list_price;
			}
			if(!localStorage.getItem("countCart")) {
				countCart = [{
		                        "id":id,
		                        "qnty" : 1,                        
		                        "quantity_available":quantity_available,
		                        "totalcost":totalcost,
		                        "name":name,
		                        "img":link,
		                        "cost":list_price,
		                        "discount" : discount
		                      }];
      			localStorage.setItem("countCart", JSON.stringify(countCart));
				toastr.success( name + ' has been successfully added.');
      		}else {
      			countCart = JSON.parse(localStorage.getItem("countCart"));
      			var find = $filter('filter')(countCart, {id: id})[0];
      			if(find == undefined) {
      				var countCartTmp = {
				                        "id":id,
				                        "qnty" : 1,                        
				                        "quantity_available":quantity_available,
				                        "totalcost":totalcost,
				                        "name":name,
		                        		"img":link,
				                        "cost":list_price,
				                        "discount" : discount
				                      };
                    countCart.push(countCartTmp);
					localStorage.setItem("countCart", JSON.stringify(countCart));	
					toastr.success( name + ' has been successfully added.');
      			} else {
      				for(var i=0; i< countCart.length; i++ ) {
      					if(countCart[i].id == id){
      						var qnty_available = parseInt(countCart[i].quantity_available ,10);
      						if(qnty_available !== 0  ) {
	      						countCart[i].qnty +=1;
	      						countCart[i].quantity_available -=1;
	      						totalcost *=countCart[i].qnty;
	      						countCart[i].totalcost = totalcost; 
	      						localStorage.setItem("countCart", JSON.stringify(countCart));	
      						}else {
      							toastr.error("You can buy only "+ quantity_available + " only");
      						}
      						break; 
      					}
      				}
      				
      			}
      		}
      		  $rootScope.countCartdisp = 0;
		      if(localStorage.getItem("countCart")) {
		          countCart = JSON.parse(localStorage.getItem("countCart"));
		          $rootScope.countCartdisp =countCart.length; 
		      }
			  
		}

		/**
		* @name detail
		* @desc give product detail of selected product
		* @param id - product id
		* @returns product detail
		*/
		function detail (id) {
			vm.view =false;
			usSpinnerService.spin('spinner-2');
			var req = ApiService.getProductDetail(httpConfig.url + "api/product/" + id);
        	req.then(function(response) {
        		vm.productDetail=response;
        		modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'modal.html',	
					size: 'lg',
					scope:$scope
				});
				
				// vm.productDetail = response;
				usSpinnerService.stop('spinner-2');
				vm.view =true;					
			});
		}

		/**
		* @name buyNow
		* @desc add selected product to cart and redirect to cart
		* @param id - product id
		* @returns product detail of selected product
		*/
		function buyNow (id,quantity_available,list_price,discount,link,name) {
			closeModel ();
			vm.addToCart (id,quantity_available,list_price,discount,link,name);
			$location.path('/cart');
		}		

});



