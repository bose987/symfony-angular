 /**
 * @name profileCtrl
 * @description This is Template profile helps to handdle the events of profile page
 */
 angular.module('PLTWApp')
    .controller('profileCtrl', function($scope, toastr, usrService, httpConfig, usSpinnerService, $auth,$location, common, $rootScope, $window) {
      var vm = this;
      vm.name = "";
      vm.mobile = "";
      vm.editdetail = true;
      vm.savedetail = false;
      vm.editShippingDetail = true;
      vm.saveShippingDetail = false;      
      vm.editBillingDetail = true;
      vm.saveBillingDetail = false;
      
      vm.billingAddress = [];
      vm.EshippingAddress = [];
      vm.shippingAddress = [];
      vm.userdetails = [];

      vm.edit = edit;
      vm.submit = submit;
      vm.cancel  = cancel;
      vm.submitAddr = submitAddr;
      vm.editShippingAddress = editShippingAddress;
      vm.editBillingAddress = editBillingAddress;
      vm.delateBillingAddress = delateBillingAddress;
      vm.delateShippingAddress = delateShippingAddress;
      vm.addShippingAddress = addShippingAddress;
      vm.addBillingAddress = addBillingAddress;

      /**
      * @name addShippingAddress
      * @desc add new shipping address
      */
      function addShippingAddress () {
        vm.editShippingDetail = false;
        vm.saveShippingDetail = true;
        vm.EshippingAddress = [];
        vm.EshippingAddress.type = "as";
      }

      /**
      * @name addBillingAddress
      * @desc add new billing address
      */
      function addBillingAddress () {
        vm.editBillingDetail = false;
        vm.saveBillingDetail = true;
        vm.EshippingAddress = [];
        vm.EshippingAddress.type = "ab";
      }

      /**
      * @name editBillingAddress
      * @desc edit shipping address
      */   
      function editBillingAddress(id) {
        angular.forEach(vm.billingAddress, function(value, key) {
            if(value.id == id){
              vm.EbillingAddress = value;
            }
        });
        vm.EshippingAddress.type = "b";        
        vm.editBillingDetail = false;
        vm.saveBillingDetail = true;
      }

      /**
      * @name editShippingAddress
      * @desc edit shipping address
      */   
      function editShippingAddress(id) {
        angular.forEach(vm.shippingAddress, function(value, key) {
            if(value.id == id){
              vm.EshippingAddress = value;
            }
        });
        vm.EshippingAddress.type = "s";        
        vm.editShippingDetail = false;
        vm.saveShippingDetail = true;
      }

      /**
      * @name delateBillingAddress
      * @desc delate billing address
      */
      function delateBillingAddress () {
        if(window.confirm("Would you like delate address")){
            getShippingAddress();
        }
      }

      /**
      * @name delateShippingAddress
      * @desc delate shipping address
      */ 
      function delateShippingAddress(id) {
        if(window.confirm("Would you like delate address")){
            getShippingAddress();
        }
      }
          
      /**
      * @name edit
      * @desc edit personal detail
      */
      function edit () {
        vm.editdetail = false;
        vm.savedetail = true;
      }

      /**
      * @name submit
      * @desc submit personal detail
      */
      function submit () {
        usSpinnerService.spin('spinner-1');
          var data = {
            'name' : vm.name,
            'phone' : vm.mobile
          };
          var req = common.put(httpConfig.login, data);
          req.then(function(responce){
            vm.editdetail = true;
            vm.savedetail = false;
            getAllUserDetails();
            usSpinnerService.stop('spinner-1');
          });
      }

      /**
      * @name cancel
      * @desc cancel address add or update
      */ 
      function cancel () {
        vm.editShippingDetail = true;
        vm.saveShippingDetail = false;
        vm.editBillingDetail = true;
        vm.saveBillingDetail = false;
        vm.EshippingAddress = [];        
      }
      
      /**
      * @name submitAddr
      * @desc update or add new address of user
      */ 
      function submitAddr () {
        var req = usrService.updateAddress(vm.EshippingAddress);
        cancel();
      }

      /**
      * @name getAllUserDetails
      * @desc get user details
      */ 
      function getAllUserDetails () {
          var req = usrService.getAllUserDetails();
          req.then(function(responce){
            vm.userdetails = responce;
            vm.name = vm.userdetails.first_name + " " + vm.userdetails.last_name;
            vm.mobile = vm.userdetails.phone;
            usSpinnerService.stop('spinner-1');
          });
      }
      
      /**
      * @name getShippingAddress
      * @desc get shipping address
      */ 
      function getShippingAddress () {
        var url = httpConfig.url + "secure/user/billing";
        var req = usrService.getShippingAddress(url);
        req.then(function(responce){
           vm.shippingAddress = responce;
        });
      }

      /**
      * @name init
      * @desc initial funtional call 
      */ 
      function init () {
        getAllUserDetails ();
        getShippingAddress ();
      }
      
      init();

});
