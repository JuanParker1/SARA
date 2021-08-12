angular.module('Indicadores_AddDiagCtrl', [])
.controller('Indicadores_AddDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$localStorage', 'tiposDatoInd', 'Procesos', 'proceso_id',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $localStorage, tiposDatoInd, Procesos, proceso_id) {

		console.info('Indicadores_AddDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.tiposDatoInd = tiposDatoInd;
		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda'];

		Ctrl.newInd = {
			TipoDato: "Porcentaje",
			Decimales: 1,
			Sentido: 'ASC',
			Formula: 'a / b',
			Meta: null
		};
		

		Ctrl.searchProceso = (searchText) => {
			if(!searchText || searchText.trim() == '') return Procesos;

			return $filter('filter')(Procesos, searchText);

		}

		if(proceso_id){
			Ctrl.newInd.proceso = Procesos.find(p => p.id == proceso_id);
		}


		Ctrl.submitInd = () => {
			if(!Ctrl.newInd.proceso) return Rs.showToast('Falta el proceso', 'Error', 1000);
			Ctrl.newInd.proceso_id = Ctrl.newInd.proceso.id;
			$mdDialog.hide(Ctrl.newInd);
		}

	}
]);
