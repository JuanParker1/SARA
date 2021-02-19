angular.module('Entidades_GridsCtrl', [])
.controller('Entidades_GridsCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog',
	function($scope, $rootScope, $injector, $mdDialog) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		var DefGridConfig = {
			main_buttons: [],
			row_buttons: []
		};

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
			console.log(Ctrl.GridsCRUD);

			Ctrl.GridsCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
			}, {
				title: 'Crear Grid',
				only: ['Titulo']
			}).then((R) => {
				R.Config = angular.copy(DefGridConfig);
				if(!R) return; Ctrl.GridsCRUD.add(R);
			});
		};

		Ctrl.openGrid = (G) => {
			G.Config = angular.extend({},DefGridConfig,G.Config);
			Ctrl.GridSel = G;
			Ctrl.getColumnas().then(() => { 
				Ctrl.getFiltros();
				//Ctrl.testGrid(G.id);
				//Ctrl.configEditor(G.Config.main_buttons[0], Ctrl.GridColumnasCRUD.rows); //FIX
			});
		};

		//Columnas
		Ctrl.getColumnas = () => {
			Ctrl.GridColumnasCRUD.setScope('grid', Ctrl.GridSel.id);
			return Ctrl.GridColumnasCRUD.get();
		};

		Ctrl.addColumna = (C, Ruta, Llaves) => {
			
			if(Llaves.length == 0){
				Indice = Ctrl.GridColumnasCRUD.rows.length;
			}else{
				var Indice = Rs.getIndex( Ctrl.GridColumnasCRUD.rows, Llaves[1], 'campo_id' );
			};

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
				Indice: Indice
			}).then(() => {
				//Ctrl.prepFiltros();
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
				controller: 'Entidades_GridDiagCtrl',
				templateUrl: 'Frag/Entidades.Entidades_GridDiag',
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
				//locals: { grid_id: grid_id },
				onComplete: (scope) => {
					scope.getGrid(grid_id);
				}
			});
		};


		//Botones
		var DefaultButton = { icono: '', texto: '', accion: 'Editor', modo: 'Crear', accion_element: '', accion_element_id: null, campos: {} };
		Ctrl.addButton = (bag, button) => {
			var button = angular.extend({}, DefaultButton, button);
			Ctrl.GridSel.Config[bag].push(button);
		};

		Ctrl.queryElm = Rs.queryElm;

		Ctrl.selectElm = (item, B) => {
			B.accion_element_id = item.id;
			B.accion_element    = item.display;
		};

		Ctrl.removeButton = (bag, i) => {
			Ctrl.GridSel.Config[bag].splice(i,1);
		};

		Ctrl.configEditor = (B, GridColumnas) => {

			var BConf = angular.extend({}, DefaultButton, B);
			$mdDialog.show({
				controller: 'Entidades_EditorConfigDiagCtrl',
				templateUrl: 'Frag/Entidades.Entidades_EditorConfigDiag',
				clickOutsideToClose: true, fullscreen: true, multiple: true,
				locals: { B: BConf, TiposCampo: Ctrl.TiposCampo, GridColumnas: GridColumnas },
				onComplete: (scope) => {
					//scope.getGrid(grid_id);
				}
			}).then((nB) => {
				if(!nB) return;
				B = angular.extend(B, nB);
				Ctrl.GridsCRUD.update(Ctrl.GridSel);
			});
		};

		Ctrl.getGrids();

	}
]);