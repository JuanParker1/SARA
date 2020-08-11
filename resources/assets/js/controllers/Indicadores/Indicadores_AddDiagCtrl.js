angular.module('Indicadores_AddDiagCtrl', [])
.controller('Indicadores_AddDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$localStorage', 'tiposDatoInd', 'Procesos', 'proceso_id',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $localStorage, tiposDatoInd, Procesos, proceso_id) {

		console.info('Indicadores_AddDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.tiposDatoInd = tiposDatoInd;
		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda'];
		Ctrl.newVariable = '';

		Ctrl.newInd = {
			TipoDato: "Porcentaje",
			Decimales: 1,
			Sentido: 'ASC',
			Formula: 'a / b',
			Meta: null,
			variables: [
				{ Variable: '', TipoDato: 'Numero', Decimales: 0 },
				{ Variable: '', TipoDato: 'Numero', Decimales: 0 }
			]
		};
		

		Ctrl.searchProceso = (searchText) => {
			if(!searchText || searchText.trim() == '') return Procesos;

			return $filter('filter')(Procesos, searchText);

		}

		Ctrl.getLetra = (k) => { return String.fromCharCode(97 + k); }

		Ctrl.removeVar = (k) => { Ctrl.newInd.variables.splice(k,1); }

		Ctrl.addVariable = () => {
			if(Ctrl.newVariable.trim() == '') return;
			
			Ctrl.newInd.variables.push({
				Variable: Ctrl.newVariable, TipoDato: 'Numero', Decimales: 0 
			});

			Ctrl.newVariable = '';

		}

		if(proceso_id){
			Ctrl.newVariable.proceso = $filter('filter')(Procesos, { id: proceso_id });
		}


		Ctrl.submitInd = () => {
			if(!Ctrl.newInd.proceso) return Rs.showToast('Falta el proceso', 'Error', 1000);
			Ctrl.newInd.proceso_id = Ctrl.newInd.proceso.id;
			$mdDialog.hide(Ctrl.newInd);
		}

	}
]);
