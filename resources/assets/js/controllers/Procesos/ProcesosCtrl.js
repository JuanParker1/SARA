angular.module('ProcesosCtrl', [])
.controller('ProcesosCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ProcesosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.ProcesoSel = null;
		Ctrl.ProcesosNav = true;
		Ctrl.TiposProcesos = [ 
			{ id: 'Agrupador', 		Nombre: 'Agrupador' },
			{ id: 'Proceso', 		Nombre: 'Proceso' },
			{ id: 'Concesionario', 	Nombre: 'Concesionario' },
			{ id: 'Programa', 		Nombre: 'Programa' },
			{ id: 'Utilitario', 	Nombre: 'Utilitario' }
		];

		Ctrl.getProcesos = () => {
			Rs.http('api/Procesos', {}, Ctrl, 'Procesos').then(() => {

				Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos,'Ruta','Proceso',false,true);
				//console.log(Ctrl.ProcesosFS);
			});	
		};

		Ctrl.openProceso = (P) => {
			Ctrl.ProcesoSel = P;
		};

		Ctrl.lookupProceso = (F) => {
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
		
	}
]);