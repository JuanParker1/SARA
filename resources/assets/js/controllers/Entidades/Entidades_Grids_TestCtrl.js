angular.module('Entidades_Grids_TestCtrl', [])
.controller('Entidades_Grids_TestCtrl', ['$scope', '$rootScope', '$mdDialog', 'grid_id', 
	function($scope, $rootScope, $mdDialog, grid_id) {

		console.info('Entidades_Grids_TestCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
			Ctrl.Grid = r.Grid;
		});
		
	}
]);