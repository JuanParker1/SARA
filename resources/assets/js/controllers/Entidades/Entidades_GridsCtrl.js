angular.module('Entidades_GridsCtrl', [])
.controller('Entidades_GridsCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog',
	function($scope, $rootScope, $injector, $mdDialog) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		//Grids
		Ctrl.getGrids = () => {
			if(!Ctrl.EntidadSel) return;
			Ctrl.GridsCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.GridsCRUD.get().then(() => {
				if(Ctrl.GridsCRUD.rows.length == 0) return;
				Ctrl.openGrid(Ctrl.GridsCRUD.rows[0]);
			});
		};

		Ctrl.addGrid = () => {
			Ctrl.GridsCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
			}, {
				title: 'Crear Grid',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.GridsCRUD.add(R);
			});
		};

		Ctrl.openGrid = (G) => {
			Ctrl.GridSel = G;
			Ctrl.getColumnas().then(() => { Ctrl.getFiltros(); });
		};

		//Columnas
		Ctrl.getColumnas = () => {
			Ctrl.GridColumnasCRUD.setScope('grid', Ctrl.GridSel.id);
			return Ctrl.GridColumnasCRUD.get();
		};

		Ctrl.addColumna = (C, Ruta, Llaves) => {
			var Indice = Ctrl.GridColumnasCRUD.rows.length;
			return Ctrl.GridColumnasCRUD.add({
				grid_id: Ctrl.GridSel.id,
				Tipo: 'Campo', Ruta: Ruta, Llaves: Llaves, campo_id: C.id,
				Indice: Indice,
			}).then(() => {
				Rs.showToast('Columna Añadida', 'Success');
			});
		};

		Ctrl.addAllColumnas = (Cols, Ruta, Llaves) => {
			var Indice = Ctrl.GridColumnasCRUD.rows.length;
			var Rows = [];
			angular.forEach(Cols, (C) => {
				Rows.push({
					grid_id: Ctrl.GridSel.id,
					Tipo: 'Campo', Ruta: Ruta, Llaves: Llaves, campo_id: C.id,
					Indice: Indice,
				});
				Indice++;
			});

			return Ctrl.GridColumnasCRUD.addMultiple(Rows).then(() => {
				Rs.showToast('Columnas Añadidas', 'Success');
			});
		};

		Ctrl.editColumna = (C) => {
			Ctrl.GridColumnasCRUD.dialog(C, { title: 'Editar Columna', only:['Cabecera'], with_delete: false }).then((r) => {
				var Index = Rs.getIndex(Ctrl.GridColumnasCRUD.rows, r.id);
				if(r.Cabecera == '') r.Cabecera = null;
				r.changed = true;
				Ctrl.GridColumnasCRUD.rows[Index] = r;
				
			});
		};

		Ctrl.removeColumna = (C) => {
			Ctrl.GridColumnasCRUD.delete(C);
		};

		Ctrl.removerColumnas = () => {
			Ctrl.GridColumnasCRUD.deleteMultiple().then(() => {
				Ctrl.getFiltros();
			});
		};

		Ctrl.dragListener2 = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.GridColumnasCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};


		Ctrl.verCamposDiag = (entidad_id, Ruta, Llaves) => {
			if(!entidad_id) return;

			var Entidad = Ctrl.getEntidad(entidad_id);

			$mdDialog.show({
				controller: 'Entidades_VerCamposCtrl',
				templateUrl: 'Frag/Entidades.Entidades_VerCampos',
				clickOutsideToClose: false,
				fullscreen: false,
				multiple: true,
				locals: { ParentCtrl: Ctrl, Entidad: Entidad, Ruta: Ruta, Llaves: Llaves }
			}).then((r) => {
				Ctrl.verCamposDiag(r[0],r[1],r[2]);
			});
		};

		//Filtros
		Ctrl.getFiltros = () => {
			Ctrl.GridFiltrosCRUD.setScope('grid', Ctrl.GridSel.id);
			return Ctrl.GridFiltrosCRUD.get();
		};

		Ctrl.addFiltro = (Co) => {
			var Indice = Ctrl.GridFiltrosCRUD.rows.length;
			return Ctrl.GridFiltrosCRUD.add({
				grid_id: Ctrl.GridSel.id,
				columna_id: Co.id,
				Indice: Indice,
			}).then(() => {
				Ctrl.prepFiltros();
				Rs.showToast('Filtro Añadido', 'Success');
			});
		};

		Ctrl.dragListener3 = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.GridFiltrosCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};
		Ctrl.selectedRows = [];

		//Grid
		Ctrl.updateGrid = () => {
			Ctrl.GridsCRUD.update(Ctrl.GridSel).then(() => {

				//Actualizar las columnas
				Rs.http('/api/Entidades/grids-columnas-update', { Columnas: Ctrl.GridColumnasCRUD.rows }).then(() => {
					angular.forEach(Ctrl.GridColumnasCRUD.rows, (C,index) => {
						C.changed = false;
					});
					
					//Actualizar los filtros
					Rs.http('/api/Entidades/grids-filtros-update', { Filtros: Ctrl.GridFiltrosCRUD.rows }).then(() => {
						angular.forEach(Ctrl.GridFiltrosCRUD.rows, (C,index) => {
							C.changed = false;
						});
						Rs.showToast('Grid Actualizada', 'Success');
					});
				});
				
			});
		};

		Ctrl.testGrid = (grid_id) => {
			$mdDialog.show({
				controller: 'Entidades_Grids_TestCtrl',
				templateUrl: 'Frag/Entidades.Entidades_Grids_Test',
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
				locals: { grid_id: grid_id }
			})
		};

		Ctrl.getGrids();

	}
]);