/**
* @name PLTWApp
* @requires $urlRouterProvider, $stateProvider, $authProvider
* @description This is PLTWApp where we injected Various modules
*/
angular.module('PLTWApp', [
    'ui.bootstrap',
    'ngResource', 
    'ngMessages',
    'ngAnimate', 
    'toastr', 
    'ui.router',
    'ngPayments',
    'ngRoute',
    'LocalStorageModule',
    'angularSpinner',
    'satellizer',
    'validation.match'
  ])
/**
* @name httpConfig
* @description httpConfig.url is constant for Api url
*/
.constant("httpConfig", {
        "url": "https://angular-symfony-api.herokuapp.com/app_dev.php/",
        "login": "https://angular-symfony-api.herokuapp.com/app_dev.php/secure/user"
  })
/**
* @name setDecimal
* @requires $filter
* @desc set decimal place
* @param input, places
* @returns no of "places" of decimal
*/  
.filter('setDecimal', function ($filter) {
    return function (input, places) {
        if (isNaN(input)) return input;
        var factor = "1" + Array(+(places > 0 && places + 1)).join("0");
        return Math.round(input * factor) / factor;
    };
  })
/**
* @name products
* @desc to store the products details
*/  
.factory('products', function() {
  return {
      details : []
  };
})
/**
* @name categories
* @desc to store the categories details
*/ 
.factory('categories', function() {
  return {
      details : []
  };
})
/**
* @name config
* @requires $stateProvider, $urlRouterProvider, $authProvider
* @desc configure of App model
* @returns true or false
*/
.config(function($stateProvider, $urlRouterProvider, $authProvider, $httpProvider) {
	$httpProvider.defaults.withCredentials = true;
    $stateProvider
      .state('home', {
        url: '/',
        controller: 'HomeCtrl as home',
        templateUrl: 'templete/home.html',
        resolve: {
          isLoggedIn: isLoggedIn
        }
       })
      .state('login', {
        url: '/login',
        controller: 'LoginCtrl as login',
        templateUrl: 'templete/login.html',
        resolve: {
          skipIfLoggedIn: skipIfLoggedIn
        }
      })
      .state('cart', {
        url: '/cart',
        controller: 'CartCtrl as cart',
        templateUrl: 'templete/cart.html',
        resolve: {
          isLoggedIn: isLoggedIn
        }      
      })
      .state('confirmorder', {
        url: '/billing',
        controller: 'ConfirmOrderCtrl as confirmorder',
        templateUrl: 'templete/billing_address.html',
        resolve: {
          loginRequired: loginRequired
        }
      })  
      .state('your-orders', {
        url: '/your-orders',
        controller: 'yourOrdersCtrl as orders',
        templateUrl: 'templete/your-orders.html',
        resolve: {
          loginRequired: loginRequired
        }
      })    
      .state('profile', {
        url: '/profile',
        controller: 'profileCtrl as profile',
        templateUrl: 'templete/profile.html',
        resolve: {
          loginRequired: loginRequired
        }
      })  
      .state('orderdetails', {
        url: '/orderdetails',
        controller: 'OrderDeatilsCtrl as orderdetails',
        templateUrl: 'templete/orderdetails.html',
        resolve: {
          loginRequired: loginRequired
        }     
      })
      .state('signup', {
        url: '/signup',
        controller: 'signupCtrl as signup',
        templateUrl: 'templete/signup.html',
        resolve: {
          loggedIn: loggedIn
        }
       })     
      $urlRouterProvider.otherwise('/');

      $authProvider.facebook({
        clientId: '1579713388968087'
      });
      
      $authProvider.google({
        clientId: '347208389240-mcrkt21pgothj0i33n6bbn2ver4jgq0f.apps.googleusercontent.com'
      });
      /**
      * @name skipIfLoggedIn
      * @desc skip if logged in
      * @param $q, $auth
      * @returns deferred.promise
      */
      function skipIfLoggedIn($q, $auth) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
          deferred.reject();
        } else {
          deferred.resolve();
        }
        return deferred.promise;
      }
      /**
      * @name loginRequired
      * @desc login is Required
      * @param $q, $location, $auth
      * @returns deferred.promise
      */
      function loginRequired($q, $location, $auth) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
          deferred.resolve();
        } else {
          $location.path('/login');
        }
        return deferred.promise;
      }
      /**
      * @name isLoggedIn
      * @desc check for logged in
      * @param $q, $auth, $rootScope
      * @returns deferred.promise
      */
      function isLoggedIn($q, $auth, $rootScope) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
          $rootScope.isLoginFlag = true;
          deferred.resolve();
        } else {
          $rootScope.isLoginFlag = false;
          deferred.resolve();
        }
        return deferred.promise;
      }
      /**
      * @name loggedIn
      * @desc check for logged in and redirect to home
      * @param $q, $auth, $rootScope, $location
      * @returns deferred.promise
      */
      function loggedIn($q, $auth, $rootScope, $location) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
          $rootScope.isLoginFlag = true;
          deferred.resolve();
          $location.path('/home');
        } else {
          $rootScope.isLoginFlag = false;
          deferred.resolve();
        }
        return deferred.promise;
      }
});
