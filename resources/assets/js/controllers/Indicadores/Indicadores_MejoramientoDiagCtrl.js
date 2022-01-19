angular.module('Indicadores_MejoramientoDiagCtrl', [])
.controller('Indicadores_MejoramientoDiagCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 'Periodo', 'Comentarios', 'seeExternal',
	function($scope, $rootScope, $http, $injector, $mdDialog, Periodo, Comentarios, seeExternal) {

		console.info('Indicadores_MejoramientoDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		Ctrl.Cancel  = $mdDialog.cancel;
		Ctrl.Periodo = Periodo;
		Ctrl.Comentarios = Comentarios;
		Ctrl.seeExternal = seeExternal;

	}
]);