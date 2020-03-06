 angular.module('AppsCtrl', [])
.controller('AppsCtrl', ['$scope', '$rootScope', '$injector', '$http', '$filter', '$window',
	function($scope, $rootScope, $injector, $http, $filter, $window) {

		console.info('AppsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.AppsSidenav = true;
		Rs.mainTheme = 'Snow_White';
		Rs.http('/api/Entidades/grids-get', {}, Ctrl, 'Grids');
		Rs.http('/api/Entidades/cargadores-get', {}, Ctrl, 'Cargadores');
		Rs.http('/api/Scorecards/all', {}, Ctrl, 'Scorecards');
		Ctrl.AppsCRUD  = $injector.get('CRUD').config({ base_url: '/api/App/apps' });
		Ctrl.PagesCRUD = $injector.get('CRUD').config({ base_url: '/api/App/pages' });
		Ctrl.TiposPage = [
			{ id: 'ExternalUrl', Icono: 'fa-external-link-square-alt',  Nombre: 'Url Externa' 	 },
			{ id: 'Scorecard',   Icono: 'fa-th-large', 					Nombre: 'Dashboard' 	 },
			{ id: 'Grid', 		 Icono: 'fa-table', 					Nombre: 'Tabla de Datos' },
			{ id: 'Cargador', 	 Icono: 'fa-sign-in-alt fa-rotate-270', Nombre: 'Cargador' },
		];
		var DefConfig = { url: '', element_id: null, elements_ids: [], buttons_main: [], buttons_grid: [] };

		Ctrl.AppsCRUD.get().then(() => {
			if(Ctrl.AppsCRUD.rows.length > 0){
				Ctrl.openApp(Ctrl.AppsCRUD.rows[0]);
			};
		});

		Ctrl.addApp = () => {
			Rs.BasicDialog({
				Title: 'Crear App',
				Fields: [{ Nombre: 'Titulo',  Value: '', Required: true },],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.AppsCRUD.add(f);
			});
		};

		Ctrl.openApp = (A) => {
			if(A == Ctrl.AppSel) return;
			Ctrl.AppSel = A;
			Ctrl.PageSel = null;
			Ctrl.PagesCRUD.setScope('app', Ctrl.AppSel.id);
			Ctrl.PagesCRUD.get().then(() => {
				if(Ctrl.PagesCRUD.rows.length == 0) return;
				Ctrl.openPage(Ctrl.PagesCRUD.rows[0]);
			});
		};

		Ctrl.addButton = (group, btn) => {
			Ctrl.PageSel.Config[group].push(btn);
		};

		Ctrl.openAppWindow = (ev) => {
			ev.preventDefault();
			var Url = 'http://sara.local/#/a/' + Ctrl.AppSel.Slug;
			$window.open(Url,"Ratting","width=800,height=600,left=0,top=0,toolbar=0,status=0,")
		};

		Ctrl.updateApp = () => {
			Ctrl.AppsCRUD.update(Ctrl.AppSel).then(() => {
				if(Ctrl.PageSel) Ctrl.PagesCRUD.update(Ctrl.PageSel);
				Rs.showToast('Guardado', 'Success');
			});
		};


		Ctrl.changeTextColor = () => {
			Ctrl.AppSel.textcolor = Rs.calcTextColor(Ctrl.AppSel.Color);
		};

		Ctrl.calcSlug = () => {
			Rs.http('/api/App/slug').then(Slug => {
				Ctrl.AppSel.Slug = Slug;
			});
		};

		Ctrl.addPage = () => {

			Rs.BasicDialog({
				Title: 'Crear Página',
				Fields: [
					{ Nombre: 'Titulo',  Value: '', Required: true },
					{ Nombre: 'Tipo',    Value: Ctrl.TiposPage[0]['id'], Type: 'list', Required: true, List: Ctrl.TiposPage, Item_Show: 'Nombre', Item_Val: 'id' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				angular.extend(f, { app_id: Ctrl.AppSel.id, Indice: Ctrl.PagesCRUD.rows.length, Config: [] });
				Ctrl.PagesCRUD.add(f);
			});

		};

		Ctrl.movePageUp = (P) => {
			var indexAnt = Rs.getIndex(Ctrl.PagesCRUD.rows, (P.Indice-1), 'Indice' );
			PAnt = Ctrl.PagesCRUD.rows[indexAnt];
			PAnt.Indice++;
			P.Indice--;

			Ctrl.PagesCRUD.updateMultiple([PAnt, P]);
		};

		Ctrl.prepConfig = () => {
			Ctrl.PageSel.Config = angular.copy(DefConfig);
		};

		Ctrl.openPage = (P) => {
			P.Config = angular.extend({}, DefConfig, P.Config);
			Ctrl.PageSel = P;
		};


		Rs.http('api/Procesos', {}, Ctrl, 'Procesos');
		Ctrl.buscarProcesos = (searchText) => {
			return $filter('filter')(Ctrl.Procesos, { Proceso: searchText });
		};

		Ctrl.selectedProceso = (item) => {
			if(!item) return;

			var Proceso = angular.copy(item);
			Ctrl.selectedP = null;
			Ctrl.searchText = '';

			Ctrl.AppSel.Procesos.push(Proceso.id);
		}
		Ctrl.removeProceso = (kP) => {
			Ctrl.AppSel.Procesos.splice(kP, 1);
		}

	}
]);