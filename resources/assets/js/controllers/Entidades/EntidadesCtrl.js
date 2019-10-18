angular.module('EntidadesCtrl', [])
.controller('EntidadesCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', '$filter',
	function($scope, $rootScope, $injector, $mdDialog, $filter) {

		console.info('EntidadesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.EntidadSidenav = true;
		Ctrl.loadingEntidad = false;


		Ctrl.EntidadesCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades', 					order_by: ['Nombre'] });
		Ctrl.CamposCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', 			order_by: ['Indice'] });
		Ctrl.RestricCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/restricciones', 		add_research: true, add_with:['campo'] });
		Ctrl.GridsCRUD 			= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids', 				order_by: ['Titulo'] });
		Ctrl.GridColumnasCRUD 	= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-columnas', 	query_with:['campo'], add_append:'refresh', order_by: ['Indice'] });
		Ctrl.GridFiltrosCRUD 	= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-filtros', 		query_with:[], order_by: ['Indice'] });
		Ctrl.EditoresCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/editores', 			query_with:[], order_by: ['Titulo'] });
		Ctrl.EditoresCamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/editores-campos', 	query_with:[], order_by: ['Indice'] });
		
		Ctrl.navToSubsection = (subsection) => { Rs.navTo('Home.Section.Subsection', { section: 'Entidades', subsection: subsection }); };

		Ctrl.getBdds = () => {
			Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds').then(() => {
				if(Ctrl.Bdds.length > 0){
					Ctrl.BddSel = Ctrl.Bdds[0];
					Ctrl.getEntidades();
				}
			});
		};

		Ctrl.getEntidades = () => {
			Ctrl.EntidadesCRUD.get().then(() => {
				Ctrl.getFsEntidades();
				Ctrl.openEntidad(Ctrl.EntidadesCRUD.rows[1]); //QUITAR
				Ctrl.navToSubsection('General');
			});
		};

		Ctrl.getFsEntidades = () => {
			Ctrl.filterEntidades = "";
			Ctrl.FsEntidades = Rs.FsGet(Ctrl.EntidadesCRUD.rows,'Ruta','Entidad');
		};

		Ctrl.searchEntidades = () => {
			if(Ctrl.filterEntidades == ""){
				Ctrl.getFsEntidades();
			}else{
				Ctrl.FsEntidades = Rs.FsGet($filter('filter')(Ctrl.EntidadesCRUD.rows, Ctrl.filterEntidades),'Ruta','Entidad',true);
			};
		};

		Ctrl.getEntidad = (id) => {
			return $filter('filter')(Ctrl.EntidadesCRUD.rows, { id: id }, true)[0];
		};

		Ctrl.openEntidad = (E) => {
			if(!E) return;
			if(Ctrl.EntidadSel){ if(Ctrl.EntidadSel.id == E.id) return; }
			Ctrl.loadingEntidad = true;
			Ctrl.EntidadSel = E;
			Ctrl.getCampos();
			Ctrl.getRestricciones();
		}

		Ctrl.addEntidad = () => {

			Ctrl.getFsEntidades();
			Rs.BasicDialog({
				Title: 'Crear Entidad', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '', Required: true, flex: 50 },
					{ Nombre: 'Tabla',   Value: '', Required: true, flex: 50 },
					{ Nombre: 'Ruta',    Value: '', flex: 70, Type: 'fsroute', List: Ctrl.FsEntidades },
					{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.EntidadesCRUD.add({
					Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Nombre: f.Nombre, Tabla: f.Tabla,
					bdd_id: Ctrl.BddSel.id, Tipo: 'Tabla'
				}).then(() => {
					Ctrl.getFsEntidades();
				});
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
		Ctrl.getCampos = () => {
	
			Ctrl.CamposCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.CamposCRUD.get().then(() => {
				Ctrl.loadingEntidad = false;
			});

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

		//Start Up
		Rs.navTo('Home.Section', { section: 'Entidades' });
		Ctrl.getBdds();
	}
]);