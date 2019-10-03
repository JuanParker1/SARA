angular.module('Entidades_EditoresCtrl', [])
.controller('Entidades_EditoresCtrl', ['$scope', '$rootScope', '$timeout',
	function($scope, $rootScope, $timeout) {

		console.info('Entidades_EditoresCtrl');
		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		//Editores
		Ctrl.getEditores = () => {
			if(!Ctrl.EntidadSel) return;
			Ctrl.EditoresCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.EditoresCRUD.get().then(() => {
				if(Ctrl.EditoresCRUD.rows.length == 0) return;
				Ctrl.openEditor(Ctrl.EditoresCRUD.rows[0]);
			});
		};

		Ctrl.addEditor = () => {
			Ctrl.EditoresCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
				Titulo: 'General'
			}, {
				title: 'Crear Editor',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.EditoresCRUD.add(R);
			});
		};

		Ctrl.openEditor = (G) => {
			Ctrl.EditorSel = G;
			//Ctrl.getColumnas().then(() => { Ctrl.getFiltros(); });
		};

		Ctrl.updateEditor = () => {
			Ctrl.EditoresCRUD.update(Ctrl.EditorSel).then(() => {
				Rs.showToast('Editor Actualizado', 'Success');
			});
		};


		Ctrl.getEditores();
		
	}
]);