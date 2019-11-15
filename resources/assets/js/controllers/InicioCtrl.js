angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', 
	function($scope, $rootScope) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.makeFavorite = (A,make) => {
			A.favorito = make;
			Rs.http('api/App/favorito', { usuario_id: Rs.Usuario.id, app_id: A.id, favorito: make });
		};


	}
]);