/**
 * @name ApiService
 * @desc service that used to call api of products and categories
 */
angular
.module('PLTWApp')
.service('usrService', function(common, $q, httpConfig, $filter) {
       return{
       		getAllUserDetails: getAllUserDetails,
       		getShippingAddress: getShippingAddress,
       		updateAddress: updateAddress,
       		getMyOrders: getMyOrders
       };

       function getMyOrders () {
       		var defer = $q.defer();
			var url =  httpConfig.url + "secure/order/customer";
			var reqPromise = common.get(url);
			reqPromise.then(function (response) {
				defer.resolve(response);       			
			});
			return defer.promise;
       }

		/**
		* @name getAllUserDetails
		* @desc Get all user details
		* @returns promise
		*/
        function getAllUserDetails () {
			var defer = $q.defer();
			var url =  httpConfig.login;
			var reqPromise = common.get(url);
			reqPromise.then(function (response) {
				defer.resolve(response);       			
			});
			return defer.promise;
		}

		/**
		* @name getAllUserDetails
		* @desc Get all user details
		* @returns promise
		*/
        function getShippingAddress (url) {
			var defer = $q.defer();
			var reqPromise = common.get(url);
			reqPromise.then(function (response) {
				defer.resolve(response);       			
			});
			return defer.promise;
		}

		/**
		* @name getAllUserDetails
		* @desc Get all user details
		* @returns promise
		*/
        function updateAddress (addr) {
			if(addr.type == "s") { // Shipping address

			}else 
			if(addr.type == "b") { // Billing address

			}else if(addr.type == "as") { // New Shipping address

			}else if(addr.type == "ab") { //New Billing address

			}
		}
});