angular.module('Scorecards_RecalcCacheDiagCtrl', [])
.controller('Scorecards_RecalcCacheDiagCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 'ScoSel',
	function($scope, $rootScope, $http, $injector, $mdDialog, ScoSel) {

		console.info('Scorecards_RecalcCacheDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.ScoSel = ScoSel;

		Ctrl.Anio = angular.copy(Rs.AnioActual);
		Ctrl.Anios = [Ctrl.Anio-3,Ctrl.Anio-2,Ctrl.Anio-1,Ctrl.Anio,Ctrl.Anio+1];

		Ctrl.selectedRow = [];
		Ctrl.Status = 'Iddle';

		Rs.http('api/Scorecards/get-elements', { id: ScoSel.id, Tipo: 'Indicador' }, Ctrl, 'Nodos').then(() => {
			angular.forEach(Ctrl.Nodos, (N) => {
				let ruta_arr = N.ruta.split('\\');
				ruta_arr.shift();
				ruta_arr.pop();
				N.ruta_fixed = ruta_arr.join('\\');
			});
		});

		Ctrl.startRefresh = () => {
			if(Ctrl.selectedRow.length == 0){
				Ctrl.CurrIndex = 0;
				angular.forEach(Ctrl.Nodos, (N) => {
					N.done = false;
				});
			}else{
				Ctrl.CurrIndex = Rs.getIndex(Ctrl.Nodos, Ctrl.selectedRow[0].id);
			}
			
			Ctrl.Status = 'Running';
			Ctrl.StepRefresh();
		};

		Ctrl.StepRefresh = () => {
			let Indicador = Ctrl.Nodos[Ctrl.CurrIndex];
			if(!Indicador || Ctrl.Status !== 'Running'){
				return Ctrl.Status = 'Iddle';
			}

			Ctrl.selectedRow = [Indicador];

			Rs.http('api/Indicadores/get', { Anio: Ctrl.Anio, id: Indicador.elemento.id, modoComparativo: false }).then(() => {
				Indicador.done = true;
				Ctrl.CurrIndex++;
				Ctrl.StepRefresh();
			});
		};

		Ctrl.stopRefresh = () => {
			Ctrl.Status = 'Iddle';
		}

	}
]);