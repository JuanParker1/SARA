angular.module('Entidades_Grids_TestCtrl', [])
.controller('Entidades_Grids_TestCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'grid_id', 
	function($scope, $rootScope, $mdDialog, $filter, grid_id) {

		console.info('Entidades_Grids_TestCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.inArray = Rs.inArray;

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Ctrl.Data = [];

		Ctrl.filterData = () => {
			Ctrl.Data = [];
			var d = angular.copy(Ctrl.Grid.data);
			angular.forEach(Ctrl.Grid.filtros, (F) => {
				if(d.length > 0 && Ctrl.inArray(F.Comparador, ['lista','radios'])){

					if(angular.isArray(F.val)){ if(F.val.length == 0) F.val = null }
					if(F.val !== null){
						d = $filter('filter')(d, function (item) {
							if(angular.isArray(F.val)){
								return F.val.includes(item[F.columna.header_index]);
							}
							return item[F.columna.header_index] === F.val;
						});
					}
				};
			});

			Ctrl.Data = d; delete d;
		};

		Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
			Ctrl.Grid = r.Grid;
			Ctrl.filterData();
		});

		Ctrl.getSelectedText = (Text) => {
			if(Text === null) return 'Seleccionar...';
			if(angular.isArray(Text)){
				var Len = Text.length;
				return ( Len == 1 ) ? Text[0] : (Len + ' Seleccionados');
			}
			return Text;
		};
		
	}
]);