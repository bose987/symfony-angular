angular
.module('PLTWApp')
.service('common', function($http, $location, $rootScope, $q, $state ,toastr, httpConfig, $auth) {
       var service = {
              post: post,
              get: get,
              loginCheck:loginCheck,
              loginCheckReq:loginCheckReq,
              loginSignupSkip: loginSignupSkip,
              put : put,
              Delete : Delete
       };
       return service;

       /**
       * @name get
       * @desc api call 
       * @param url - url of request to send
       * @returns promise
       */
       function get(url) {
              var d = $q.defer();
              $http({
                     url: url,
                     method: 'GET',
              })
              .success(function(response) {
                     d.resolve(response);
              })
              .error(function (result, status, headers, config) {
                     toastr.error('Error in Fetching Data');
                     d.reject('Error in Fetching Data');
              });
              return d.promise;
       }

       /**
       * @name post
       * @desc api call and post data
       * @param url - url of request to send
       * @param postData - postData is that data to be send
       * @returns promise
       */
       function post(url, postData) {
              var d = $q.defer();
              $http({
                     url: url,
                     method: "post",
                     data: postData
              })
              .success(function(response) {
                     d.resolve(response);
              })
              .error(function (result, status, headers, config) {
                     toastr.error('Error to post data');
                     d.reject('Error to post data');
              });
              return d.promise;
       }

       /**
       * @name loginCheck
       * @desc api call for check logged in
       * @param url - url of request to send
       * @returns promise
       */
       function loginCheck (url,msg) {
              var d = $q.defer();
              $http({
                  method: 'get',
                  url: url
              })
              .success(function(response) {
                     if(response.code != 401){
                            $rootScope.isLoginFlag = true;
                            $rootScope.userName = response.first_name + " " + response.last_name;
                            d.resolve(response);
                     }else{
                            $rootScope.isLoginFlag = false;
                            $auth.removeToken();
                            if(msg){
                                toastr.error('Please login');
                            }                            
                            d.reject('Please login');
                     }  
              })
              .error(function (result, status, headers, config) {
                     $auth.removeToken();
                     toastr.error('Please login');
                     d.reject('Please login');
              });
              return d.promise;
       }

       /**
       * @name loginCheckReq
       * @desc api call for check logged in
       * @param url - url of request to send
       * @returns promise
       */
       function loginCheckReq (url) {
              var d = $q.defer();
              $http({
                  method: 'get',
                  url: url
              })
              .success(function(response) {
                     if(response.code != 401){
                            $rootScope.isLoginFlag = true;
                            d.resolve(response);
                     }else{
                            $rootScope.isLoginFlag = false;
                            $auth.removeToken();
                            $location.path('/login');
                            toastr.error('Please login');
                            d.reject('Please login');
                     }  
              })
              .error(function (result, status, headers, config) {
                     $auth.removeToken();
                     $location.path('/login');
                     toastr.error('Please login');
                     d.reject('Please login');
              });
              return d.promise;
       }
       function loginSignupSkip (url) {
              var d = $q.defer();
              $http({
                  method: 'get',
                  url: url
              })
              .success(function(response) {
                     if(response.code != 401){
                            $rootScope.isLoginFlag = true;
                            $location.path('/home');
                            d.resolve(response);
                     }
              })
              .error(function (result, status, headers, config) {
                     $auth.removeToken();
                     toastr.error('Please login');
                     d.reject('Please login');
              });
              return d.promise;
       }
       /**
       * @name put
       * @desc api call and post data
       * @param url - url of request to send
       * @param postData - postData is that data to be send
       * @returns promise
       */
       function put(url, postData) {
              var d = $q.defer();
              $http({
                     url: url,
                     method: "PUT",
                     params: postData
              })
              .success(function(response) {
                     d.resolve(response);
              })
              .error(function (result, status, headers, config) {
                     toastr.error('Error to post data');
                     d.reject('Error to post data');
              });
              return d.promise;
       }
       /**
       * @name Delete
       * @desc api call and post data
       * @param url - url of request to send
       * @param postData - postData is that data to be send
       * @returns promise
       */
       function Delete(url, postData) {
              var d = $q.defer();
              $http({
                     url: url,
                     method: "delete",
                     data: postData
              })
              .success(function(response) {
                     d.resolve(response);
              })
              .error(function (result, status, headers, config) {
                     toastr.error('Error to post data');
                     d.reject('Error to post data');
              });
              return d.promise;
       }
});

