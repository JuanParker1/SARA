angular.module('MisIndicadoresCtrl', [])
.controller('MisIndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('MisIndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Black';

		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterIndicadoresText = '';
		Ctrl.Loading = true;

		var Indicadores = [];
		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getIndicadores(); };

		Ctrl.getIndicadores = () => {
			Ctrl.Loading = true;
			Ctrl.hasEdited = false;
			Rs.http('api/Indicadores/get-usuario', { Usuario: Rs.Usuario, Anio: Ctrl.Anio }).then((r) => {
				Indicadores = r;
				Ctrl.filterIndicadores();
			});
		};

		Ctrl.getIndicadores();

		Ctrl.filteredIndicadores = [];
		Ctrl.filterIndicadores = () => {
			var Vars = angular.copy(Indicadores);
			
			if(Ctrl.ProcesoSel){ 
				Vars = $filter('filter')(Vars, { proceso_id: Ctrl.ProcesoSel }, true);
			}

			if(Ctrl.filterIndicadoresText.trim() !== ''){
				Vars = $filter('filter')(Vars, Ctrl.filterIndicadoresText);
			}

			Ctrl.filteredIndicadores = Vars;
			Ctrl.Loading = false;
		}


		
	}
]);