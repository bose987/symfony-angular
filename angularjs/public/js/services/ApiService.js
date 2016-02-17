/**
 * @name ApiService
 * @desc service that used to call api of products and categories
 */
angular
.module('PLTWApp')
.service('ApiService', function(common, $q, $filter, products, categories) {
       return{
       		getAllCategories: getAllCategories,
       		getAllProducts: getAllProducts,
       		getCategoryProduct: getCategoryProduct,
       		getProductDetail: getProductDetail
       };
		/**
		* @name getAllCategories
		* @desc Get all categories
		* @param url - Api url
		* @returns promise
		*/
       function getAllCategories (url) {
	       	var prodCategory =[]; 
            var defer = $q.defer();
            if(categories.details.length != 0) {
            	prodCategory = categories.details
	       		defer.resolve(prodCategory);
            }else {
	       		var reqPromise = common.get(url);
	       		reqPromise.then(function (response) {
	       			var cat=response;						
					var catincludes= [{"id" : null}];	
					angular.forEach(cat	, function(value, key) {
							catTmp = {
									   "id" : null,
									   "name" : null,
									   "description" : null,
									   "subMenu" : 0,
									   "subItem" : []
							};								
							catTmp.id = value.id;
							catTmp.name = value.name;
							catTmp.description = value.description;						
							var find = $filter('filter')(response, {category_id: value.id});
							if(find != undefined) {
								catTmp.subMenu = 1;
								angular.forEach(find, function(valueFind, key) {
									catincludes.id = valueFind.id;
									catTmp.subItem.push(valueFind);
								});
							}else {
								catTmp.subMenu = 0;
							}
							if(!value.category_id){
								prodCategory.push(catTmp);
							}						 
					});	
					categories.details = prodCategory;
	       			defer.resolve(prodCategory);
	       		});
	       	}
       		return defer.promise;
       }
		/**
		* @name getCategoryProduct
		* @desc Get product by category
		* @param url - Api url
		* @returns promise
		*/
       function getCategoryProduct (url) {
            var defer = $q.defer();
            var details = [];
       		var reqPromise = common.get(url);
       		reqPromise.then(function (response) {
       			if(response.length != 0){
					details = response;
				}else {
					details = response;
				}
       			defer.resolve(response);       			
       		});
       		return defer.promise;
        }
		/**
		* @name getProductDetail
		* @desc Get product by product id
		* @param url - Api url
		* @returns promise
		*/
       function getProductDetail (url) {
			var defer = $q.defer();
			var details = [];
			var reqPromise = common.get(url);
			reqPromise.then(function (response) {
				if(response.discount == undefined){
					response.discount = 0;
				}
				defer.resolve(response);       			
			});
       		return defer.promise;				
       }
		/**
		* @name getAllProducts
		* @desc Get all products
		* @param url - Api url
		* @returns promise
		*/     
       function getAllProducts (url) {
       		var defer = $q.defer();
       		if(products.details.length != 0) {
            	prodCategory = products.details
	       		defer.resolve(prodCategory);
            }else {
	       		var reqPromise = common.get(url);
	       		reqPromise.then(function (response) {       			
	       			defer.resolve(response);
	       			products.details = response;
	       		});
	       	}
       		return defer.promise;
       }
});