angular.module('ProcesosCtrl', [])
.controller('ProcesosCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ProcesosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Rs.mainTheme = 'Snow_White';
		
		Ctrl.ProcesoSel = null;
		Ctrl.ProcesosNav = true;
		Ctrl.TiposProcesos = [ 
			{ id: 'Empresa', 		Nombre: 'Empresa', 		    Icono: 'fa-building' },
			{ id: 'Subdireccion',   Nombre: 'Subdirección', 	Icono: 'fa-cubes' },
			{ id: 'Agrupador', 		Nombre: 'Agrupador', 		Icono: 'fa-cubes' },
			{ id: 'MacroProceso', 	Nombre: 'MacroProceso', 	Icono: 'fa-cube' },
			{ id: 'Proceso', 		Nombre: 'Proceso', 			Icono: 'fa-cube' },
			{ id: 'SubProceso', 	Nombre: 'SubProceso', 		Icono: 'fa-cube' },
			{ id: 'Concesionario', 	Nombre: 'Concesionario', 	Icono: 'external-link-square-alt' },
			{ id: 'Programa', 		Nombre: 'Programa',			Icono: 'fa-crosshairs' },
			{ id: 'Utilitario', 	Nombre: 'Utilitario', 		Icono: 'fa-cog' }
		];

		Ctrl.getProcesoIcon = (id) => {
			if(!id) return;
			return Ctrl.TiposProcesos.find(p => (p.id == id) ).Icono;
		};

		Ctrl.getProcesos = () => {
			Rs.http('api/Procesos', {}, Ctrl, 'Procesos').then(() => {

				//console.log(Ctrl.Procesos);

				Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos,'Ruta','Proceso',false,true);
				if(Rs.Storage.procesosel){
					var Ps = Ctrl.Procesos.filter((P) => {
						return ( P.id == Rs.Storage.procesosel );
					});
					if(Ps.length > 0) Ctrl.openProceso(Ps[0]);
				}

				angular.forEach(Ctrl.ProcesosFS, fs => {
					if(fs.type == 'folder'){
						console.log(p.Ruta + '\\' + p.Proceso);
						var proceso = Ctrl.Procesos.find(p => (p.Ruta + '\\' + p.Proceso) == fs.route);

						if(proceso){
							fs.proceso = proceso;
							console.log(fs);
						}
						//Ctrl.//fs.route

						
					}
				});
			});	
		};

		Ctrl.openProceso = (P) => {
			Ctrl.ProcesoSel = P;
			Ctrl.getAsignaciones();
			Ctrl.getIndicadores();

			Rs.Storage.procesosel = P.id;

		};

		Ctrl.lookupProceso = (F) => {

			//console.log(F);

			var Ps = Ctrl.Procesos.filter((P) => {
				return ( P.children > 0 && P.Ruta == F.route );
			});
			if(Ps.length > 0) Ctrl.openProceso(Ps[0]);
		};

		Ctrl.getProcesos();

		Ctrl.updateProceso = () => {
			Rs.http('api/Procesos/update', { Proceso: Ctrl.ProcesoSel }).then(() => {
				Rs.showToast('Proceso Actualizado', 'Success');
			});
		};

		Ctrl.sendCreate = (p) => {
			Rs.http('api/Procesos/create', { Proceso: p }).then(() => {
				Rs.showToast(p.Tipo+' Creado', 'Success');
				Ctrl.getProcesos();
			});
		};

		Ctrl.createSubproceso = () => {
			Rs.BasicDialog({
				Title: 'Crear Subproceso',
				Fields: [
					{ Nombre: 'Nombre',  Value: '', Required: true },
					{ Nombre: 'Tipo',    Value: 'Proceso', Required: true, Type: 'list', List: Ctrl.TiposProcesos, Item_Val: 'id', Item_Show: 'Nombre' },

				],
			}).then((r) => {
				Ctrl.sendCreate({
					Proceso: r.Fields[0].Value.trim(),
					padre_id: Ctrl.ProcesoSel.id, 
					Tipo: r.Fields[1].Value
				});
			});
		};

		Ctrl.createEmpresa = () => {
			Rs.BasicDialog({
				Title: 'Crear Empresa',
			}).then((r) => {
				Ctrl.sendCreate({
					Proceso: r.Fields[0].Value.trim(),
					Tipo: 'Empresa'
				});
			});
		};
		
		Rs.http('api/Usuario/perfiles', {}, Ctrl, 'Perfiles');


		Ctrl.userSearch = (searchText) => {
			return Rs.http('api/Usuario/search', { searchText: searchText, limit: 5 });
		};

		Ctrl.selectedItem = null;
		Ctrl.selectedUser = (item) => {
			if(!item) return;

			var User = angular.copy(item);
			Ctrl.selectedItem = null;
			Ctrl.searchText = '';

			perfil_id = 2;
			if(Ctrl.AsignacionesCRUD.rows.length > 0) perfil_id = 3;

			Ctrl.AsignacionesCRUD.add({
				usuario_id: User.id,
				nodo_id: Ctrl.ProcesoSel.id,
				perfil_id: perfil_id
			});

		}

		//Asignaciones
		Ctrl.AsignacionesCRUD = $injector.get('CRUD').config({ base_url: '/api/Usuario/asignaciones', add_append: 'refresh' });
		Ctrl.getAsignaciones = () => {
			Ctrl.AsignacionesCRUD.setScope('Nodo',  Ctrl.ProcesoSel.id).get();
		}

		//Indicadores
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.getIndicadores = () => {
			Ctrl.IndicadoresCRUD.setScope('proceso',  Ctrl.ProcesoSel.id).get();
		}

	}
]);