angular.module('BDD_ListasDiagCtrl', [])
.controller(   'BDD_ListasDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'Config',
	function ($scope, $rootScope, $mdDialog, Config) {

		console.info('BDD_ListasDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		var DefConfig = {
		};

		Ctrl.Config = angular.extend(DefConfig, Config);

		//Obtener las listas
		Rs.http('api/Bdds/get-listas', { bdd_id: Ctrl.Config.bdd_id }, Ctrl, 'Listas').then(() => {
			if(Ctrl.Listas.length > 0){
				Ctrl.Config.lista_id = Ctrl.Listas[0].id;
				Ctrl.getIndices();
			}
		});

		//Obtener los indices
		Ctrl.getIndices = () => {
			Ctrl.IndiceSel = null;

			Rs.http('api/Bdds/get-indices', { lista_id: Ctrl.Config.lista_id }).then(r => {
				Ctrl.Indices = r.Indices;
				//Ctrl.openLista(Ctrl.Listas[2]);
			});
		}
		

		Ctrl.openIndice = (I) => {
			Ctrl.IndiceSel = I;
			Ctrl.Detalles = null;
			Rs.http('api/Bdds/get-listadetalles', { lista_id: Ctrl.Config.lista_id, indice_cod: I.IndiceCod }, Ctrl, 'Detalles');
		};

		Ctrl.selectLista = () => {
			$mdDialog.cancel({ lista_id: Ctrl.Config.lista_id, indice_cod: Ctrl.IndiceSel.IndiceCod });
		};

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }
	}

]);