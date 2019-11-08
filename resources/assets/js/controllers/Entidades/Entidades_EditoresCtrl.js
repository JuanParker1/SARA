angular.module('Entidades_EditoresCtrl', [])
.controller('Entidades_EditoresCtrl', ['$scope', '$rootScope', '$timeout', '$filter',
	function($scope, $rootScope, $timeout, $filter) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		$scope.EditoresSidenav = false;
		$scope.showEditorCampos = true;
		$scope.anchosCampo = [10,15,20,25,30,33,35,40,45,50,55,60,65,66,70,75,80,85,90,95,100];
		$scope.EditoresCamposSel = [];

		//Editores
		Ctrl.getEditores = () => {
			if(!Ctrl.EntidadSel) return;
			Ctrl.EditoresCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.EditoresCRUD.get().then(() => {
				if(Ctrl.EditoresCRUD.rows.length > 0){
					Ctrl.openEditor(Ctrl.EditoresCRUD.rows[0]);
				}else{
					$scope.EditoresSidenav = true;
				};
			});
		};

		Ctrl.addEditor = () => {
			Ctrl.EditoresCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
				Titulo: 'General', Secciones: []
			}, {
				title: 'Crear Editor',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.EditoresCRUD.add(R);
			});
		};

		Ctrl.openEditor = (G) => {
			Ctrl.EditorSel = G;
			Ctrl.getEditorCampos().then(() => {  });
		};

		Ctrl.updateEditor = () => {
			Ctrl.EditoresCRUD.update(Ctrl.EditorSel).then(() => {
				Rs.showToast('Editor Actualizado', 'Success');
				Ctrl.saveEditorCampos();
			});
		};

		//Campos
		Ctrl.getEditorCampos = () => {
			if(!Ctrl.EditorSel) return;
			Ctrl.EditoresCamposCRUD.setScope('editor', Ctrl.EditorSel.id);
			return Ctrl.EditoresCamposCRUD.get();
		};

		Ctrl.autogetEditorCampos = () => {
			var Inseerts = [];
			var Indice = Ctrl.EditoresCamposCRUD.rows.length;
			var ids = Ctrl.EditoresCamposCRUD.rows.map(c => c.campo_id);

			angular.forEach(Ctrl.CamposCRUD.rows, C => {
				if(!ids.includes(C.id)){
					Indice++;
					Inseerts.push({ editor_id: Ctrl.EditorSel.id, Indice: Indice, campo_id: C.id, Ancho: 100 });
				};
			});

			if(Inseerts.length > 0){
				Ctrl.EditoresCamposCRUD.addMultiple(Inseerts);
			};
		};

		Ctrl.saveEditorCampos = () => {
			var Updatees = $filter('filter')(Ctrl.EditoresCamposCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.EditoresCamposCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.EditoresCamposCRUD.rows, C => {C.changed = false;});
		};

		Ctrl.removeEditorCampos = () => {
			if($scope.EditoresCamposSel.length == 0) return;
			Ctrl.EditoresCamposCRUD.ops.selected = $scope.EditoresCamposSel;
			Ctrl.EditoresCamposCRUD.deleteMultiple().then(() => {
				 $scope.EditoresCamposSel = [];
			});
		};

		Ctrl.getEditores();

	}
]);