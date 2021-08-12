angular.module('EntidadesCtrl', [])
.controller('EntidadesCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', '$filter', '$timeout',
	function($scope, $rootScope, $injector, $mdDialog, $filter, $timeout) {

		console.info('EntidadesCtrl 1');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';
		if(!('EntidadSidenav' in Rs.Storage) || !Rs.Storage.EntidadSelId) Rs.Storage.EntidadSidenav = true;
		Ctrl.loadingEntidad = false;
		Ctrl.showCampos = true;
		if(!Rs.Storage.EntidadSubseccion) Rs.Storage.EntidadSubseccion = 'General';
		Ctrl.filterEntidades = '';

		Ctrl.EntidadesCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades', 					order_by: ['Nombre'] });
		Ctrl.CamposCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', 			order_by: ['Indice'] });
		Ctrl.RestricCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/restricciones', 		add_research: true, add_with:['campo'] });
		Ctrl.GridsCRUD 			= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids', 				order_by: ['Titulo'] });
		Ctrl.GridColumnasCRUD 	= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-columnas', 	query_with:['campo'], add_append:'refresh', order_by: ['Indice'] });
		Ctrl.GridFiltrosCRUD 	= $injector.get('CRUD').config({ base_url: '/api/Entidades/grids-filtros', 		order_by: ['Indice'] });
		Ctrl.EditoresCRUD 		= $injector.get('CRUD').config({ base_url: '/api/Entidades/editores', 			order_by: ['Titulo'] });
		Ctrl.EditoresCamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/editores-campos', 	order_by: ['Indice'] });
		Ctrl.CargadoresCRUD 	= $injector.get('CRUD').config({ base_url: '/api/Entidades/cargadores', 		order_by: ['Titulo'] });
		

		Ctrl.EntidadesSecciones = [
			['General',  	'fa-chess-pawn' ],
			['Grids'  ,  	'fa-table' ],
			['Editores', 	'fa-pen-square' ],
			['Cargadores', 	'fa-sign-in-alt fa-rotate-270' ],
		];

		Ctrl.navToSubsection = (subsection) => {
			Rs.Storage.EntidadSubseccion = subsection;
			Rs.navTo('Home.Section.Subsection', { section: 'Entidades', subsection: subsection }); 
		};

		Ctrl.getBdds = () => {

			Promise.all([
				Rs.getProcesos(Ctrl),
				Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds')
			]).then(() => {
				if(Ctrl.Bdds.length > 0){

					var bdd_sel_id = (Rs.Storage.BddSelId) ? Rs.getIndex(Ctrl.Bdds, Rs.Storage.BddSelId) : 0;
					Ctrl.BddSel = Ctrl.Bdds[bdd_sel_id];
					Ctrl.getEntidades();
				}
			});
		};

		Ctrl.getEntidades = () => {

			Ctrl.EntidadesCRUD.setScope('bdd', Ctrl.BddSel.id);
			Ctrl.EntidadesCRUD.get().then(() => {
				
				Rs.getProcesosFS(Ctrl);

				if(Rs.Storage.EntidadSelId){
					var entidad_sel_id = Rs.getIndex(Ctrl.EntidadesCRUD.rows, Rs.Storage.EntidadSelId);
					Ctrl.openEntidad(Ctrl.EntidadesCRUD.rows[entidad_sel_id]);
				};

				Ctrl.navToSubsection('General'); //Rs.Storage.EntidadSubseccion
			});
		};

		Ctrl.getFsEntidades = () => {
			Ctrl.filterEntidades = "";
			Ctrl.FsEntidades = Rs.FsGet(Ctrl.EntidadesCRUD.rows,'Ruta','Entidad');
		};

		Ctrl.getEntidadesFiltered = () => {
			//EntidadesCRUD.rows | filter:{ proceso_id: ProcesoSelId }:true | filter:filterEntidades | orderBy:'Nombre'
			if(Ctrl.filterEntidades.trim() == ''){
				return $filter('filter')(Ctrl.EntidadesCRUD.rows, { proceso_id: Ctrl.ProcesoSelId }, true);
			}else{
				return $filter('filter')(Ctrl.EntidadesCRUD.rows, Ctrl.filterEntidades);
			}
			//return [];
		}

		Ctrl.openProceso = (P) => { 
			Ctrl.ProcesoSelId = P.id;
		}

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
			Ctrl.ProcesoSelId = E.proceso_id;

			//Rs.Refresh();

			Ctrl.getCampos().then(Ctrl.getRestricciones);
		}

		Ctrl.fijarEntidad = () => {
			Rs.Storage.EntidadSelId = Ctrl.EntidadSel.id;
			Rs.Storage.BddSelId = Ctrl.EntidadSel.bdd_id;
		}

		Ctrl.addEntidad = () => {

			Ctrl.getFsEntidades();
			Rs.BasicDialog({
				Title: 'Crear Entidad', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  	Value: '', 					Required: true, flex: 50 },
					{ Nombre: 'Tabla',   	Value: '', 					Required: true, flex: 50 },
					{ Nombre: 'Proceso',   	Value: Ctrl.ProcesoSelId, 	Required: true, flex: 100, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.EntidadesCRUD.add({
					//Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Nombre: f.Nombre, Tabla: f.Tabla,
					bdd_id: Ctrl.BddSel.id, Tipo: 'Tabla',
					proceso_id: f.Proceso
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


		Ctrl.seleccionarEntidad = (Campo) => {
			Rs.TableDialog(Ctrl.EntidadesCRUD.rows, {
				Title: 'Seleccionar Entidad', Flex: 30,
				primaryId: 'id', pluck: true,
				Columns: [
					{ Nombre: 'Nombre', Desc: 'Entidad', numeric: false }
				],
				selected: [], multiple: false,
			}).then(r => {
				if(!r) return;
				Campo.Op1 = r[0];
			});
		}


		//Campos
		Ctrl.getCampos = () => {
	
			Ctrl.CamposCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			return Ctrl.CamposCRUD.get().then(() => {
				Ctrl.loadingEntidad = false;

				//Ctrl.configImagen(Ctrl.CamposCRUD.rows[1]); //FIX
				//Ctrl.configLista(Ctrl.CamposCRUD.rows[3]); FIX
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
			Tipo: 'Texto',
			Config: []
		};
		
		Ctrl.addCampo = (newCampo) => {
			newCampo.Columna = newCampo.Columna.trim();
			if(newCampo.Columna == '') return Rs.showToast('Falta Columna', 'Error');
			//if(Rs.found(newCampo.Columna, Ctrl.CamposCRUD.rows, 'Columna')) return; //FIX - Puedo repetir Columnas
			newCampo.entidad_id = Ctrl.EntidadSel.id;
			newCampo.Indice = Ctrl.CamposCRUD.rows.length;
			Ctrl.CamposCRUD.add(newCampo).then(() => {
				newCampo = angular.copy(newCampoDef);
				Ctrl.setTipoDefaults(newCampo);
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

		Ctrl.configLista = (C) => {
			$mdDialog.show({
				controller: 'Entidades_Campos_ListaConfigCtrl',
				templateUrl: 'Frag/Entidades.Entidades_Campos_ListaConfig',
				clickOutsideToClose: false,
				fullscreen: false,
				multiple: true,
				locals: { C: C }
			}).then((newC) => {
				if(!newC) return;
				C = newC; C.changed = true;
			});
		};

		Ctrl.configImagen = (C) => {
			$mdDialog.show({
				controller: 'Entidades_Campos_ImagenConfigCtrl',
				templateUrl: 'Frag/Entidades.Entidades_Campos_ImagenConfig',
				clickOutsideToClose: false,
				fullscreen: false,
				multiple: true,
				locals: { C: C }
			}).then((newC) => {
				if(!newC) return;
				C = newC; C.changed = true;
			});
		};

		//Lista Avanzada
		Ctrl.browseListas = (C) => {

			let Config = {
				bdd_id: Ctrl.BddSel.id,
			};

			console.log(C);

			$mdDialog.show({
				controller: 'BDD_ListasDiagCtrl',
				templateUrl: '/Frag/BDD.BDD_ListasDiag',
				locals: { Config: Config },
				clickOutsideToClose: true, fullscreen: false, multiple: true,
			}).then((L) => {
				if(!L) return;
				//var newC = angular.copy(C);
				if(C.Config == null) C.Config = {};
				C.Config = angular.extend(C.Config, L);
				//C = newC;
				C.changed = true;
			});
		}



		//Restricciones
		Ctrl.getRestricciones = () => {
			Ctrl.RestricCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			return Ctrl.RestricCRUD.get();
		};

		Ctrl.addRestriccion = (newRestriccion) => {
			console.log(newRestriccion);
			Ctrl.RestricCRUD.add({
				entidad_id: Ctrl.EntidadSel.id,
				campo_id:   newRestriccion,
				Comparador: '=',
				Valor:      null
			});
		};

		Ctrl.removeRestriccion = (R) => {
			Ctrl.RestricCRUD.delete(R);
		};

		Ctrl.stopEv = ev => {
			ev.stopPropagation();
		};





		//Start Up
		Rs.navTo('Home.Section', { section: 'Entidades' });
		Ctrl.getBdds();
	}
]);