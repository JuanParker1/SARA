angular.module('Entidades_EntidadSearchDiagCtrl', [])
.controller('Entidades_EntidadSearchDiagCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 'C',
	function($scope, $rootScope, $http, $injector, $mdDialog, C) {

		console.info('Entidades_EntidadSearchDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		Ctrl.Cancel = $mdDialog.cancel;
		Ctrl.C      = C;
		Ctrl.searching = false;

		console.log(C);

		Rs.http('api/Entidades/search-table', { entidad_id: C.campo.Op1 }, Ctrl, 'SearchTable')
			.then(() => { Ctrl.searchRows() });


		Ctrl.searchRows = () => {
			Ctrl.searching = true;
			Rs.http('api/Entidades/search-table-rows', { SearchTable: Ctrl.SearchTable }, Ctrl, 'Rows').then(() => {
				Ctrl.searching = false;
			});
		}

		Ctrl.selectItem = (Item) => {
			$mdDialog.hide(Item);
		}

	}
]);