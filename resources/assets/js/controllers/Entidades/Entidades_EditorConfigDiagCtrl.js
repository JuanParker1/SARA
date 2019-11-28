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
				console.log(B);
				angular.forEach(Ctrl.Editor.campos, (C) => {
					
					if(typeof Ctrl.B[C.id] == 'undefined'){

						if(Ctrl.B.modo == 'Crear'){
							Ctrl.B.campos[C.id] = { tipo_valor: 'Por Defecto' };
						};

						if(Ctrl.B.modo == 'Editar'){
							var columnas   = $filter('filter')(GridColumnas, { campo_id: C.campo_id });
							var columna_id = (columnas.length > 0) ? columnas[0]['id'] : null;
							Ctrl.B.campos[C.id] = { tipo_valor: 'Columna', columna_id: columna_id };
						};
					};
				});
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.B);
		};

		Ctrl.getEditor();

	}
]);