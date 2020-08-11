angular.module('MiProcesoCtrl', [])
.controller('MiProcesoCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('MiProcesoCtrl');
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

		
		
		/*Ctrl.getIndicadores = () => {
			Ctrl.Loading = true;
			Ctrl.hasEdited = false;
			Rs.http('api/Indicadores/get-usuario', { Usuario: Rs.Usuario, Anio: Ctrl.Anio }).then((r) => {
				Indicadores = r;
				Ctrl.filterIndicadores();
			});
		};

		Ctrl.getIndicadores();*/


		
	}
]);