angular.module('Entidades_EditorConfigDiagCtrl', [])
.controller('Entidades_EditorConfigDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'B', 'TiposCampo', 'GridColumnas',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, B, TiposCampo, GridColumnas) {

		console.info('Entidades_EditorConfigDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.queryElm = Rs.queryElm;
		Ctrl.inArray  = Rs.inArray;
		Ctrl.TiposCampo = TiposCampo;
		Ctrl.B = B;
		Ctrl.GridColumnas = GridColumnas;
		Ctrl.TiposValor = ['Por Defecto','Columna','Fijo','Sin Valor'];


		Ctrl.getEditor = () => {
			if(!B) return;
			Rs.http('api/Entidades/editor-get', { editor_id: B.accion_element_id }, Ctrl, 'Editor').then(() => {
				
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.B);
		};

		Ctrl.getEditor();

	}
]);