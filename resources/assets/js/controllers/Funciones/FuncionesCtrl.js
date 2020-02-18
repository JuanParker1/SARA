angular.module('FuncionesCtrl', [])
.controller('FuncionesCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('FuncionesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.FuncionSel = null;
		Ctrl.FuncionesNav = true;

		
	}
]);