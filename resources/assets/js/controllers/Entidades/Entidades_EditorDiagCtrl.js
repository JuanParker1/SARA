angular.module('Entidades_EditorDiagCtrl', [])
.controller('Entidades_EditorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, $timeout) {

		console.info('Entidades_EditorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray = Rs.inArray;
		Ctrl.submitForm = Rs.submitForm;
		Ctrl.loading = true;

		var DefConfig = {
			color: '#e2e2e2', textcolor: 'black'
		};

		Ctrl.getEditor = (editor_id, Obj, Config) => {
			Ctrl.Config = angular.extend(DefConfig, Config);
			Ctrl.Config.llaveprim_val = (Ctrl.Config.modo == 'Crear') ? null : Obj.id;
			Rs.http('api/Entidades/editor-get', { editor_id: editor_id, Obj: Obj, Config: Config }).then((Editor) => {
				Ctrl.prepEditor(Editor);
				Ctrl.loading = false;
			});
		};

		Ctrl.prepEditor = (Editor) => {
			angular.forEach(Editor.campos, (C) => {
				if(Rs.inArray(C.campo.Tipo, ['Fecha','Hora','FechaHora'])){
					C.dateval = (C.val == null) ? null : moment(C.val).toDate();
				};
			});

			Ctrl.Editor = Editor;
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

		Ctrl.enviarDatos = (ev) => {
			//return console.log(ev);

			Ctrl.loading = true;
			Rs.http('api/Entidades/editor-save', { Editor: Ctrl.Editor, Config: Ctrl.Config }).then(() => {
				Ctrl.loading = false;
				$mdDialog.hide(true);
			});
		};


		//Fields Changed
		Ctrl.changedField = (C) => {
			if(C.campo.Tipo == 'FechaHora'){
				C.val = moment(C.dateval).format('YYYY-MM-DD HH:mm');
			};

			console.log(C.val);
		};


	}
]);