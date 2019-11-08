angular.module('Entidades_CargadoresCtrl', [])
.controller('Entidades_CargadoresCtrl', ['$scope', '$rootScope', '$timeout', '$filter',
	function($scope, $rootScope, $timeout, $filter) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		$scope.CargadoresSidenav = false;
		$scope.CargadoresCamposSel = [];

		$scope.TiposArchivo = {
			csv:   [ 'Archivo .CSV',  			'fa-file-csv' ],
			excel: [ 'Archivo Excel .XLSX', 	'fa-file-excel' ],
		};

		$scope.TiposValor = ['Columna','Fijo','Variable de Sistema','Sin Valor'];

		var DefConfig = {
			tipo_archivo: 'csv',
			delimiter: ',',
			with_headers: true,
			campos: {}
		};

		//Cargadores
		Ctrl.getCargadores = () => {
			if(!Ctrl.EntidadSel) return;
			Ctrl.CargadoresCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.CargadoresCRUD.get().then(() => {
				if(Ctrl.CargadoresCRUD.rows.length > 0){
					Ctrl.openCargador(Ctrl.CargadoresCRUD.rows[0]);
				}else{
					$scope.CargadoresSidenav = true;
				};
			});
		};

		Ctrl.addCargador = () => {
			Ctrl.CargadoresCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
				Titulo: 'General', Secciones: []
			}, {
				title: 'Crear Cargador',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.CargadoresCRUD.add(R);
			});
		};

		Ctrl.openCargador = (G) => {
			G.Config = angular.extend({}, DefConfig, G.Config);
			Ctrl.CargadorSel = G;

			angular.forEach(Ctrl.CamposCRUD.rows, (C) => {
				if(!Ctrl.CargadorSel.Config.campos.hasOwnProperty(C.id)){
					Ctrl.CargadorSel.Config.campos[C.id] = {
						campo_id: C.id,
						tipo_valor: 'Sin Valor',
						Defecto: null
					};
				};
			});

			Rs.viewCargadorDiag(Ctrl.CargadorSel.id);

		};

		Ctrl.updateCargador = () => {
			Ctrl.CargadoresCRUD.update(Ctrl.CargadorSel).then(() => {
				Rs.showToast('Cargador Actualizado', 'Success');
			});
		};

		Ctrl.getCargadores();

	}
]);