angular.module('Entidades_EditorConfigDiagCtrl', [])
.controller('Entidades_EditorConfigDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'B', 'TiposCampo',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, B, TiposCampo) {

		console.info('Entidades_EditorConfigDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.queryElm = Rs.queryElm;
		Ctrl.TiposCampo = TiposCampo;
		Ctrl.B = B;
		Ctrl.TiposValor = ['Sin Valor','Fijo','Columna'];


		Ctrl.getEditor = () => {
			Rs.http('api/Entidades/editor-get', { editor_id: B.accion_element_id }, Ctrl, 'Editor').then(() => {
				
			});
		};

		Ctrl.getEditor();

	}
]);