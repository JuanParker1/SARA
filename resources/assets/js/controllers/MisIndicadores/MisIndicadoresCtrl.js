angular.module('MisIndicadoresCtrl', [])
.controller('MisIndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('MisIndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.FuncionSel = null;
		Ctrl.FuncionesNav = true;
		Rs.mainTheme = 'Black';
		
	}
]);