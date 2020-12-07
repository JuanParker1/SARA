angular.module('Scorecards_NodoSelectorCtrl', [])
.controller('Scorecards_NodoSelectorCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'NodosFS', 
	function($scope, $rootScope, $mdDialog, $filter, NodosFS) {

		console.info('Scorecards_NodoSelectorCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.NodosFS = NodosFS;
		Ctrl.FsOpenFolder = Rs.FsOpenFolder;

		Ctrl.selectNodo = (N) => { Ctrl.NodoSel = N; }

		Ctrl.submitNodo = () => {
			$mdDialog.hide(Ctrl.NodoSel)
		}

	}
]);
