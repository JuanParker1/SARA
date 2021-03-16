angular.module('Procesos_MapaNodosDiagCtrl', [])
.controller('Procesos_MapaNodosDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$injector', '$filter', 'ProcesosFS',
	function($scope, $rootScope, $mdDialog, $injector, $filter, ProcesosFS) {

		console.info('Procesos_MapaNodosDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.FsOpenFolder = Rs.FsOpenFolder;
		Ctrl.ProcesosFS = ProcesosFS;
		Ctrl.Cancel = $mdDialog.cancel;

		Ctrl.openProceso = (P) => {
			$mdDialog.hide(P);
		}
	}
]);