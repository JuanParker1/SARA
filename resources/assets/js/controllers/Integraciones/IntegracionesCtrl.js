angular.module('IntegracionesCtrl', [])
.controller('IntegracionesCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 
	function($scope, $rootScope, $http, $injector, $mdDialog) {

		console.info('IntegracionesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		Ctrl.IntegracionesCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Integraciones/crud',
			order_by: [ 'Integracion' ]
		});

		Ctrl.getIntegraciones = () => {
			Ctrl.IntegracionesCRUD.get().then(() => {
				
			});
		};

		Ctrl.getIntegraciones();

	}
]);