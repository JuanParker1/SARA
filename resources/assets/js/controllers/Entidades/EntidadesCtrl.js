angular.module('EntidadesCtrl', [])
.controller('EntidadesCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', '$filter',
	function($scope, $rootScope, $injector, $mdDialog, $filter) {

		console.info('EntidadesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.EntidadSidenav = true;

		Ctrl.getBdds = () => {
			Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds').then(() => {
				if(Ctrl.Bdds.length > 0){
					Ctrl.BddSel = Ctrl.Bdds[0];
					//Ctrl.testGrid(1); //QUITAR
					Ctrl.getEntidades();
				}
			});
		};

		Ctrl.EntidadesCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades', order_by: ['Nombre'] });

		Ctrl.getEntidades = () => {
			Ctrl.EntidadesCRUD.get().then(() => {
				Ctrl.openEntidad(Ctrl.EntidadesCRUD.rows[1]); //QUITAR
			});
		};

		Ctrl.getEntidad = (id) => {
			return $filter('filter')(Ctrl.EntidadesCRUD.rows, { id: id }, true)[0];
		};

		Ctrl.openEntidad = (E) => {
			//return Ctrl.verCamposDiag(1,[5]); //QUITAR
			if(!E) return;
			if(Ctrl.EntidadSel){ if(Ctrl.EntidadSel.id == E.id) return; }
			Ctrl.EntidadSel = E;
			
			Ctrl.getCampos();
			Ctrl.getRestricciones();
			Ctrl.getGrids();

			Ctrl.GridColumnasCRUD.rows = [];
			Ctrl.GridSel = null;
		}

		Ctrl.addEntidad = () => {
			Ctrl.EntidadesCRUD.dialog({
				bdd_id: Ctrl.BddSel.id,
				Tipo: 'Tabla',
			}, {
				title: 'Crear Entidad',
				only: ['Nombre']
			}).then((R) => {
				if(!R) return;
				Ctrl.EntidadesCRUD.add(R);
			});
		};

		Ctrl.updateEntidad = () => {
			Ctrl.EntidadesCRUD.update(Ctrl.EntidadSel).then(() => {

				//Actualizar los campos
				Rs.http('/api/Entidades/campos-update', { Campos: Ctrl.CamposCRUD.rows }).then(() => {
					angular.forEach(Ctrl.CamposCRUD.rows, (C,index) => { C.changed = false; });

					//Actualizar las restricciones
					Rs.http('/api/Entidades/restricciones-update', { Restricciones: Ctrl.RestricCRUD.rows }).then(() => {
						angular.forEach(Ctrl.RestricCRUD.rows, (R,index) => { R.changed = false; });
						Rs.showToast('Entidad Actualizada', 'Success');
					});
				});

				
			});
		};


		//Campos
		Ctrl.CamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', order_by: ['Indice'] });

		Ctrl.getCampos = () => {
	
			Ctrl.CamposCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.CamposCRUD.get();

			Ctrl.newCampo = angular.copy(newCampoDef);
			Ctrl.setTipoDefaults(Ctrl.newCampo);
		};

		Ctrl.camposSel = [];
		Ctrl.setTipoDefaults = (C) => {
			C.Defecto = null;
			var Defaults = Ctrl.TiposCampo[C.Tipo]['Defaults'];
			C = angular.extend(C,Defaults);
			C.changed = true;
		};

		Ctrl.markChanged = (C) => {
			C.changed = true;
		};

		var newCampoDef = {
			Columna: '',
			Alias: null,
			Requerido: false,
			Visible: true,
			Editable: true,
			Buscable: false,
			Tipo: 'Texto'
		};
		

		Ctrl.addCampo = () => {
			Ctrl.newCampo.Columna = Ctrl.newCampo.Columna.trim();

			if(Ctrl.newCampo.Columna == '') return Rs.showToast('Falta Columna', 'Error');
			if(Rs.found(Ctrl.newCampo.Columna, Ctrl.CamposCRUD.rows, 'Columna')) return;

			Ctrl.newCampo.entidad_id = Ctrl.EntidadSel.id;
			Ctrl.newCampo.Indice = Ctrl.CamposCRUD.rows.length;

			Ctrl.CamposCRUD.add(Ctrl.newCampo).then(() => {
				Ctrl.newCampo = angular.copy(newCampoDef);
				Ctrl.setTipoDefaults(Ctrl.newCampo);

				setTimeout(function(){ $("#newCampo").focus(); }, 500);
			});
		};

		Ctrl.removeCampos = () => {
			Rs.confirmDelete({
				Title: '¿Borrar '+Ctrl.camposSel.length+' campos?',
			}).then((del) => {
				console.log(del);
				if(!del) return;
				Rs.http('/api/Entidades/campos-delete', { ids: Ctrl.camposSel }).then((msg) => {
					if(msg == 'OK'){
						Ctrl.getCampos();
						Rs.showToast(Ctrl.camposSel.length+' campos eliminados');
						Ctrl.camposSel = [];
					}else{
						Rs.showToast(msg, 'Error');
					};
				});
			});
		};

		Ctrl.OpsBooleano = [
			{ Mostrar: 'Ninguno', 	Valor: null  },
			{ Mostrar: 'Verdadero', Valor: true  },
			{ Mostrar: 'Falso',     Valor: false },
		];

		Ctrl.dragListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.CamposCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};

		Ctrl.getCamposAuto = () => {
			Rs.http('api/Entidades/campos-autoget', { Bdd: Ctrl.BddSel, Entidad: Ctrl.EntidadSel, Campos: Ctrl.CamposCRUD.rows }).then((r) => {
				if(r.length == 0) return Rs.showToast('No se encontraron nuevos campos');

				$mdDialog.show({
					controller: 'Entidades_AddColumnsCtrl',
					templateUrl: 'Frag/Entidades.Entidades_AddColumns',
					clickOutsideToClose: false,
					fullscreen: false,
					multiple: true,
					locals: { ParentCtrl: Ctrl, newCampos: r }
				}).then((newCampos) => {
					if(newCampos.length == 0) return;
					
					var current_index = Ctrl.CamposCRUD.rows.length;
					angular.forEach(newCampos, (nc) => {
						nc.Indice = current_index;
						current_index++;
					});

					Rs.http('api/Entidades/campos-add', { newCampos: newCampos }).then(() => {
						Ctrl.getCampos();
						Rs.showToast(newCampos.length+' campos agregados');
					});
				});
			});
		};

		//Restricciones
		Ctrl.RestricCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/restricciones', add_research: true, add_with:['campo'] });
		
		Ctrl.getRestricciones = () => {
			Ctrl.RestricCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.RestricCRUD.get();
		};

		Ctrl.addRestriccion = () => {
			Ctrl.RestricCRUD.add({
				entidad_id: Ctrl.EntidadSel.id,
				campo_id:   Ctrl.newRestriccion,
				Comparador: '=',
				Valor:      null
			});
			Ctrl.newRestriccion = null;
		};

		Ctrl.removeRestriccion = (R) => {
			Ctrl.RestricCRUD.delete(R);
		};

		//Grids
		Ctrl.GridsCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/grids', order_by: ['Titulo'] });

		Ctrl.getGrids = () => {
			Ctrl.GridsCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.GridsCRUD.get().then(() => {
				if(Ctrl.GridsCRUD.rows.length == 0) return;
				//Ctrl.openGrid(Ctrl.GridsCRUD.rows[0]);
			});
		};

		Ctrl.addGrid = () => {
			Ctrl.GridsCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
			}, {
				title: 'Crear Grid',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return;
				Ctrl.GridsCRUD.add(R);
			});
		};

		Ctrl.openGrid = (G) => {
			Ctrl.GridSel = G;
			Ctrl.getColumnas().then(() => { Ctrl.getFiltros(); });
			
		};

		//Columnas
		Ctrl.GridColumnasCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-columnas', query_with:['campo'], add_append:'refresh', order_by: ['Indice'] });

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
		Ctrl.GridFiltrosCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-filtros', query_with:[], order_by: ['Indice'] });

		Ctrl.prepFiltros = () => {
			angular.forEach(Ctrl.GridFiltrosCRUD.rows, (F) => {
				//var Columna = Ctrl.GridColumnasCRUD.one(F.columna_id);
				//F.columna = Columna;
				//F.campo   = Columna.campo;
			});
		};

		Ctrl.getFiltros = () => {
			Ctrl.GridFiltrosCRUD.setScope('grid', Ctrl.GridSel.id);
			return Ctrl.GridFiltrosCRUD.get().then(() => {
				Ctrl.prepFiltros();
			});
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


		//Start Up
		Ctrl.getBdds();
        
		
	}
]);