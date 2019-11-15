angular.module('Entidades_Campos_ListaConfigCtrl', [])
.controller('Entidades_Campos_ListaConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_ListaConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.C = C;

	}
]);