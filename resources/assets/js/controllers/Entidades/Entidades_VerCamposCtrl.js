angular.module('Entidades_VerCamposCtrl', [])
.controller('Entidades_VerCamposCtrl', ['$scope', '$rootScope', '$injector', 'Entidad', 'Ruta', 'Llaves', 'ParentCtrl', '$mdDialog',
	function($scope, $rootScope, $injector, Entidad, Ruta, Llaves, ParentCtrl, $mdDialog) {

		console.info('Entidades_VerCamposCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Entidad = Entidad;
		Ctrl.TiposCampo = ParentCtrl.TiposCampo;

		Ctrl.CamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', order_by: ['Indice'] });

		Ctrl.CamposCRUD.setScope('entidad', Entidad.id);
		Ctrl.CamposCRUD.get();

		var DaRuta = angular.copy(Ruta);
		DaRuta.push(Entidad.id);

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Ctrl.addColumna = (C) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(C.id);
			ParentCtrl.addColumna(C, DaRuta, DaLlaves).then(() => {

			});
		};

		Ctrl.verCamposDiag = (entidad_id, campo_id) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(campo_id);
			$mdDialog.hide([entidad_id, DaRuta, DaLlaves]);
		};
	}
]);