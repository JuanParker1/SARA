angular.module('Entidades_EditorDiagCtrl', [])
.controller('Entidades_EditorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, $timeout) {

		console.info('Entidades_EditorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.opacity = 0;

		Ctrl.Cancel = () => { $mdDialog.cancel(); };

		var DefConfig = {
			modo: 'Crear', color: '#e2e2e2', textcolor: 'black'
		};

		Ctrl.getEditor = (editor_id, Obj, Config) => {
			Ctrl.Obj = Obj;
			Ctrl.Config = angular.extend(DefConfig, Config);
			Rs.http('api/Entidades/editor-get', { editor_id: editor_id }, Ctrl, 'Editor').then(() => {
				Ctrl.opacity = 1;
			});
		};

		Ctrl.searchEntidad = (C) => {
			if(C.val !== null) return false;
			var search_elms = C.campo.entidadext.config.search_elms;
			return Rs.http('api/Entidades/search', { entidad_id: C.campo.Op1, searchText: C.searchText, search_elms: search_elms });
		};

		Ctrl.selectedItem = (item, C) => {
			if(!item) return;
			C.val = item.C0;
		};

		Ctrl.clearCampo = (C) => {
			C.val = null; C.searchText = null; C.selectedItem = null;
		};

		Ctrl.enviarDatos = () => {

		};

	}
]);