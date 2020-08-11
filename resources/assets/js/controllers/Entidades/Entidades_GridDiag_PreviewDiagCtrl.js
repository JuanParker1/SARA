angular.module('Entidades_GridDiag_PreviewDiagCtrl', [])
.controller('Entidades_GridDiag_PreviewDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'C', 'val',
	function($scope, $rootScope, $mdDialog, C, val) {

		console.info('Entidades_GridDiag_PreviewDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.C = C;
		Ctrl.val = val;

	}
]);