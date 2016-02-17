 angular.module('PLTWApp')
    .controller('StoreCtrl', function($scope, $filter,$location, $sce) {
    
    $scope.AddToCart = "Add to Cart";
    $scope.productjson =[	{"name":"Micro-Mesh Piped Polo",
							"price":"$26.00",
							"image":"tshirt1.jpg"
							},
							{"name":"Silk Touch™ Tipped Polo",
							"price":"$24.50",
							"image":"tshirt2.jpg"
							},
							{"name":"Side Blocked Micropique Sport-Wick",
							"price":"$23.00",
							"image":"tshirt3.jpg"
							},
							{"name":"Dri-FIT Classic Polo",
							"price":"$39.00",
							"image":"tshirt4.jpg"
							},
							{"name":"Long Sleeve Silk Touch™",
							"price":"$27.00",
							"image":"tshirt5.jpg"
							},
							{"name":"Silk Touch™ Sport Shirt with Pocket",
							"price":"$19.00",
							"image":"tshirt6.jpg"
							},
							{"name":" Silk Touch™ Sport Shirt",
							"price":"$17.50",
							"image":"tshirt7.jpg"
							},
							{"name":"Dri-FIT Pebble Texture Polo",
							"price":"$31.00",
							"image":"tshirt8.jpg"
							},
							];

});