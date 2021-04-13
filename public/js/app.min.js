angular.module('MainCtrl', [])
.controller('MainCtrl', ['$rootScope', 'appFunctions', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$localStorage', '$mdMedia',
	function($rootScope, appFunctions, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $localStorage, $mdMedia) {

		console.info('MainCtrl');
		var Rs = $rootScope;

		Rs.ToogleSidebar = function(nav_id){ $mdSidenav(nav_id).toggle(); }
		Rs.CloseSidebar = function(nav_id){  $mdSidenav(nav_id).close();  }
		Rs.OpenSidebar = function(nav_id){ 	 $mdSidenav(nav_id).open();   }

		//Rs.mainTheme = 'Snow_White';
		Rs.mainTheme = 'Black';

		//Check state
		Rs.StateChanged = function(){
			Rs.State = $state.current;
			Rs.State.route = $location.path().split('/');
		};

		Rs.Refresh = function() {
			$state.go($state.current, $state.params, {reload: true});
		}
		
		Rs.$on("$stateChangeSuccess", Rs.StateChanged);
		Rs.navTo = function(Dir, params){ $state.go(Dir, params); }

		Rs.StateChanged();

		Rs.Logout = function(){
			//Rs.navTo('Login', {});
			location.replace("/#/Login");
		};

		Rs.$watch(function() { return $mdMedia('gt-sm'); }, function(gtsm) { Rs.gtsm = gtsm; });

		if(typeof Rs.Storage.mainSidenavLabels == 'undefined') Rs.Storage.mainSidenavLabels = false;
		Rs.mainSidenavLabels = Rs.Storage.mainSidenavLabels;
		Rs.mainSidenav = function() {
			Rs.mainSidenavLabels = !Rs.mainSidenavLabels;
			Rs.Storage.mainSidenavLabels = !Rs.Storage.mainSidenavLabels;
			Rs.OpenSidebar('SectionsNav');
		};

		Rs.retroalimentarDiag = (Subject) => {
			$mdDialog.show({
				templateUrl: 'Frag/Core.RetroalimentarDiag',
				controller: 'RetroalimentarDiagCtrl',
				locals: { Subject: Subject },
				multiple: true, clickOutsideToClose: true
			});
		};


		Rs.agregators = [
			{ id: 'count', 			Nombre: 'Contar' },
			{ id: 'countdistinct',  Nombre: 'Contar Distintos' },
			{ id: 'sum',  			Nombre: 'Suma' },
			{ id: 'avg',  			Nombre: 'Promedio' },
			{ id: 'min',  			Nombre: 'Mínimo' },
			{ id: 'max',  			Nombre: 'Máximo' },
		];



		if (window.self != window.top) {
			$(document.body).addClass("in-iframe");
		}
		
	}
]);


angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', '$filter', '$mdMedia', '$window',
	function($scope, $rootScope, $filter, $mdMedia, $window) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;


		//Rs.mainTheme = 'Snow_White';
		Rs.mainTheme = 'Black';
		Rs.InicioSidenavOpen = $mdMedia('min-width: 750px');



		Ctrl.makeFavorite = (A,make) => {
			A.favorito = make;
			Rs.http('api/App/favorito', { usuario_id: Rs.Usuario.id, app_id: A.id, favorito: make });
		};

		var HoraDelDia = parseInt(moment().format('H'));
			 if(HoraDelDia < 7){ Rs.Saludo = 'Hola'; Rs.mainTheme = 'Black'; }
		else if(HoraDelDia >= 7 && HoraDelDia < 12){ Rs.Saludo = 'Buenos días'; }
		else if(HoraDelDia >= 12 && HoraDelDia < 18){ Rs.Saludo = 'Buenas tardes'; }
		else{ Rs.Saludo = 'Buenas noches'; Rs.mainTheme = 'Black'; }


		//Búsqueda
		Ctrl.searchMode = false;
		Ctrl.searchText = '';
		Ctrl.searchGroups = [
			{ Titulo: 'Reportes',    Value: 'Reporte', 	    Icono: 'fa-clipboard' },
			{ Titulo: 'Indicadores', Value: 'Indicador', 	Icono: 'fa-chart-line' },
			{ Titulo: 'Variables',   Value: 'Variable', 	Icono: 'fa-superscript' },
			{ Titulo: 'Procesos',    Value: 'Proceso', 	    Icono: 'fa-cube' },
		];
		Ctrl.searchGroupSel = 0;

		Ctrl.mainSearch = () => {
			//Ctrl.searchResults = null;
			if(Ctrl.searchText.trim() == '' || Ctrl.searchText.trim().length < 3){
				Ctrl.searchMode = false;
				return;
			}

			Ctrl.searchGroupSel = 0;
			Ctrl.searchMode = true;

			Rs.http('api/Main/main-search', { searchText: Ctrl.searchText }, Ctrl, 'searchResults').then(() => {
				//Ctrl.
			});
		};

		Ctrl.filteredSearchResults = () => {
			if(!Ctrl.searchResults) return [];
			if(Ctrl.searchGroupSel == 0){ return Ctrl.searchResults.results; }
			return $filter('filter')(Ctrl.searchResults.results, { Tipo: Ctrl.searchGroups[Ctrl.searchGroupSel-1].Value });
		}

		Ctrl.selectSearchGroup = (k) => {
			Ctrl.searchGroupSel = k;
		}

		Ctrl.mainSearch();

		Ctrl.showSearchRes = (R) => {
			if(R.Tipo == 'Reporte')   {
				console.log("#/a/" + R.Slug, R);
				$window.open(("#/a/" + R.Slug), '_blank');
				//href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_blank"
			};
			if(R.Tipo == 'Indicador') return Rs.viewIndicadorDiag(R.id);
			if(R.Tipo == 'Variable')  return Rs.viewVariableDiag(R.id);
		};

		Ctrl.getFavorites = () => {
			Rs.http('api/Main/get-favorites', {}).then(r => {
				Ctrl.Recientes = r.Recientes;
			});
		};

		Ctrl.getFavorites();
	}
]);
angular.module('LoginCtrl', [])
.controller('LoginCtrl', ['$scope', '$rootScope', '$http', '$localStorage', '$mdToast', '$state', 
	function($scope, $rootScope, $http, $localStorage, $mdToast, $state) {

		console.info('LoginCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
	
		delete $localStorage.token;

		Ctrl.User = '';
		Ctrl.Pass = '';

		Ctrl.ShowErr = function(Msg, Delay){
			var errTxt = '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>';
			$mdToast.show({
				template: errTxt,
				hideDelay: Delay
			});
		}

		Ctrl.Login = function(){
			$http.post('api/Usuario/login', { Email: Ctrl.User, Pass: Ctrl.Pass }).then(function(r){
				var token = r.data;
				$localStorage.token = token;

				if($localStorage.returnUrl){
					let returnUrl = angular.copy($localStorage.returnUrl);
					delete $localStorage.returnUrl;
					location.replace("/#/"+returnUrl);
				}else{
					$state.go('Home');
				}
				
			}, function(r){
				Ctrl.ShowErr(r.data.Msg); 
				Ctrl.Pass = '';
			});
		};
	}
]);
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
		Ctrl.AppsCRUD  = $injector.get('CRUD').config({ base_url: '/api/App/apps',  order_by: [ 'Titulo' ] });
		Ctrl.PagesCRUD = $injector.get('CRUD').config({ base_url: '/api/App/pages', order_by: [ 'Indice' ] });
		Ctrl.TiposPage = [
			{ id: 'ExternalUrl', Icono: 'fa-external-link-square-alt',  Nombre: 'Url Externa' 	 },
			{ id: 'Scorecard',   Icono: 'fa-th-large', 					Nombre: 'Dashboard' 	 },
			{ id: 'Grid', 		 Icono: 'fa-table', 					Nombre: 'Tabla de Datos' },
			{ id: 'Cargador', 	 Icono: 'fa-sign-in-alt fa-rotate-270', Nombre: 'Cargador' },
		];
		var DefConfig = { url: '', element_id: null, elements_ids: [], buttons_main: [], buttons_grid: [], proceso_id: null };
		Ctrl.orderBy = 'Titulo';
		Ctrl.changeAppOrder = (order) => { Ctrl.orderBy = order; }

		Ctrl.AppsCRUD.get().then(() => {
			if(Rs.Storage.AppSelId){
				var App = Ctrl.AppsCRUD.rows.find((A) => {
					return ( A.id == Rs.Storage.AppSelId );
				});
				if(App) Ctrl.openApp(App);
				
			}
		});

		Ctrl.addApp = () => {
			Rs.BasicDialog({
				Title: 'Crear App',
				Fields: [{ Nombre: 'Titulo',  Value: '', Required: true },],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				f.Navegacion = 'Superior';
				f.ToolbarSize = 30;
				Ctrl.AppsCRUD.add(f);
			});
		};

		Ctrl.openApp = (A) => {
			//if(A == Ctrl.AppSel) return;
			Rs.Storage.AppSelId = A.id;
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
				if(Ctrl.PageSel ) Ctrl.PagesCRUD.update(Ctrl.PageSel);
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

		Ctrl.dragListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				var cambios = 0;
				angular.forEach(Ctrl.PagesCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						cambios ++;
					};
				});
				if(cambios > 0){
					Ctrl.PagesCRUD.updateMultiple(Ctrl.PagesCRUD.rows);
				}
			}
		};

		Ctrl.removePage = () => {
			Rs.confirmDelete({
				Title: '¿Eliminar la página "'+Ctrl.PageSel.Titulo+'"?',
			}).then(d => {
				if(!d) return;

				Ctrl.PagesCRUD.delete(Ctrl.PageSel).then(() => {
					Ctrl.openApp(Ctrl.AppSel);
				});

			});
		}

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


		//Filtro sobre Scorecard
		Ctrl.selectedFilterProceso = (item) => {
			if(!item) return;

			Ctrl.searchText2 = '';
			Ctrl.PageSel.Config.proceso_id = item.id;
		}



	}
]);
angular.module('App_ViewCtrl', [])
.controller('App_ViewCtrl', ['$scope', '$rootScope', 'appFunctions', '$http', '$location', '$sce', '$filter',
	function($scope, $rootScope, appFunctions, $http, $location, $sce, $filter) {

		console.info('App_ViewCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		
		Ctrl.ops = {
			general_class: '',
			Color: '', textcolor: ''
		};
		Ctrl.PageSel = null;

		Ctrl.openPage = (page_id) => {

			//console.log(page_id);

			angular.forEach(Ctrl.AppSel.pages, (P) => {
				if(P.id == page_id) Ctrl.PageSel = P;
			});

			if(!Ctrl.PageSel) return Ctrl.gotoPage(Ctrl.AppSel.pages[0]);

			//P.loaded = true;
			if(Ctrl.PageSel.Tipo == 'Scorecard'){ Ctrl.ops.Color = '#2d2d2d'; Ctrl.ops.textcolor = 'white' }
			else{ Ctrl.ops.Color = Ctrl.AppSel.Color; Ctrl.ops.textcolor = Ctrl.AppSel.textcolor };
			Ctrl.ops.general_class = 'app_text_'+Ctrl.ops.textcolor+' app_nav_'+Ctrl.AppSel.Navegacion;
			

			//Notify server of section open
			Rs.http('/api/Main/add-log', { usuario_id: Rs.Usuario.id, Evento: 'AppPage', Op1: Ctrl.PageSel.id });
			//console.log(P);
		};

		Ctrl.getIframeUrl = (url) => {
			return $sce.trustAsResourceUrl(url);
		};

		Ctrl.getApp = (app_id) => {
			Rs.http('/api/App/app-get', { app_id: app_id }).then((r) => {
				Ctrl.AppSel = r.App;
				document.title = Ctrl.AppSel.Titulo;

				if(Rs.State.route.length == 3 && Ctrl.AppSel.pages.length > 0){
					var page_id = Ctrl.AppSel.pages[0]['id'];
					Ctrl.gotoPage(page_id);
				}else if(Rs.State.route.length == 4){
					Ctrl.openPage(Rs.State.route[3]);
				};

				//Ctrl.openPage(Ctrl.AppSel.pages[0]);
			});
		}

		Ctrl.gotoPage = (page_id) => {
			Rs.navTo('App.App.Page', { page_id: page_id });
		}

		Ctrl.$on("$stateChangeSuccess", () => {

			if(Rs.State.route.length <= 2) return;

			var app_id = Rs.State.route[2];
			if(!Ctrl.AppSel || Ctrl.AppSel.Slug !== app_id ) return Ctrl.getApp(app_id);
			
			if(Rs.State.route.length == 4){
				Ctrl.openPage(Rs.State.route[3]);
			};

		});



	}
]);
angular.module('BDDCtrl', [])
.controller('BDDCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', 
	function($scope, $rootScope, $injector, $mdDialog) {

		console.info('BDDCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Rs.mainTheme = 'Snow_White';
		Ctrl.BDDSidenav = true;
		Ctrl.BDDFavSidenav = false;

		Ctrl.SectionSel = 'Listas';
		Ctrl.SeccionesBDD = [
			[ 'ConsultaSQL', 'fa-bolt',	'Consulta SQL'  ],
			[ 'Listas', 	 'fa-list', 'Listas'		 ]
		];

		Ctrl.changeSection = (S) => {
			Ctrl.SectionSel = S[0];
		}

		Ctrl.BDDsCRUD = $injector.get('CRUD').config({ base_url: '/api/Bdds' });

		Ctrl.BDDsCRUD.get().then(() => {
			if(Ctrl.BDDsCRUD.rows.length > 0){
				Ctrl.openBDD(Ctrl.BDDsCRUD.rows[0]);
			};
		});

		Ctrl.openBDD = (B) => {
			Ctrl.BDDSel = B;
			Ctrl.FavsCRUD.setScope(  'bddid', Ctrl.BDDSel.id).get();
			Ctrl.ListasCRUD.setScope('bddid', Ctrl.BDDSel.id).get().then(() => {
				//Ctrl.browseLista(Ctrl.ListasCRUD.rows[0]);
			});
			//Ctrl.executeQuery(); //REmove
		};

		Ctrl.TiposBDD = {
			ODBC_DB2:     { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			ODBC_MySQL:   { Op1: 'DSN', Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			MySQL:  	  { Op1: false, Op2: 'Servidor', 		Op3: 'Base de Datos', Op4: false, Op5: false },
			SQLite: 	  { Op1: false, Op2: 'Ruta al Archivo', Op3: 'Base de Datos', Op4: false, Op5: false },
		};

		Ctrl.addBDD = () => {
			Rs.BasicDialog({
				Title: 'Crear Conexión a Base de Datos'
			}).then((r) => {
				var Nombre = r.Fields[0].Value.trim();
				if(Rs.found(Nombre, Ctrl.BDDsCRUD.rows, 'Nombre')) return;

				Ctrl.BDDsCRUD.add({
					Nombre: Nombre,
					Tipo: 'ODBC'
				});
			});
		};

		Ctrl.updateBDD = () => {
			Ctrl.BDDsCRUD.update(Ctrl.BDDSel).then(() => {
				Rs.showToast('Actualizado', 'Success', 5000, 'bottom right');
			});
		};

		Ctrl.removeBDD = () => {
			Rs.confirmDelete({
				Title: '¿Borrar la Conexión a la Base de Datos "'+Ctrl.BDDSel.Nombre+'"?'
			}).then((del) => {
				if(!del) return;
				Ctrl.BDDsCRUD.delete(Ctrl.BDDSel).then(() => {
					Ctrl.BDDSel = null;
				});
			});
		};

		Ctrl.testBDD = () => {
			Rs.http('/api/Bdds/probar', { BDD: Ctrl.BDDSel }).then((r) => {
				Rs.showToast('Conexión Exitosa', 'Success', 5000, 'bottom right');
			});
		};

		//Panel de Consultas SQL
		Ctrl.SQLQuery = "";
		Ctrl.executingQuery = false;
		Ctrl.QueryRows = null;
		Ctrl.executeQuery = () => {
			if(Ctrl.SQLQuery == "" || Ctrl.executingQuery) return;
			Ctrl.executingQuery = true;

			Rs.http('/api/Bdds/query', { BDD: Ctrl.BDDSel, Query: Ctrl.SQLQuery }).then((r) => {
				Ctrl.QueryRows = r;
			}).finally(() => {
				Ctrl.executingQuery = false;
			});
		};



		//Panel de Favoritos
		Ctrl.FavsCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Bdds/favoritos',
			query_scopes: [
				[ 'mine', true ]
			]
		});

		Ctrl.useFav = (F) => {
			if(Ctrl.executingQuery) return;

			Ctrl.SQLQuery = F.Consulta;

			if(F.EjecutarAutom == 'S'){
				Ctrl.executeQuery();
			};
		};

		Ctrl.addFav = () => {
			Ctrl.FavsCRUD.dialog({
				Consulta: angular.copy(Ctrl.SQLQuery),
				EjecutarAutom: 'N',
				bdd_id: Ctrl.BDDSel.id,
				usuario_id: Rs.Usuario.id
			}, {
				title: 'Crear Favorito',
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				Ctrl.FavsCRUD.add(R);
			});
		};

		Ctrl.editFav = (F) => {
			Ctrl.FavsCRUD.dialog(angular.copy(F), {
				title: 'Favorito: ' + F.Nombre,
				only: [ 'Nombre', 'Consulta', 'EjecutarAutom' ]
			}).then((R) => {
				if(!R) return;
				if(R == 'DELETE') return Ctrl.FavsCRUD.delete(F);
				Ctrl.FavsCRUD.update(R);
			});
		};



		//Panel de Listas
		Ctrl.ListasCRUD = $injector.get('CRUD').config({ base_url: '/api/Bdds/listas' });

		Ctrl.addLista = () => {
			Ctrl.ListasCRUD.dialog({
				bdd_id: Ctrl.BDDSel.id
			}, {
				title: 'Crear Proveedor de Listas',
				class: 'w400',
				except: [ 'bdd_id' ]
			}).then((R) => {
				if(!R) return;
				Ctrl.ListasCRUD.add(R);
			});
		}

		Ctrl.editLista = (L) => {
			Ctrl.ListasCRUD.dialog(L, {
				title: 'Editar Proveedor de Listas',
				class: 'w400',
				except: [ 'bdd_id' ]
			}).then((R) => {
				if(!R) return;
				if(R=='DELETE') return Ctrl.ListasCRUD.delete(L);
				Ctrl.ListasCRUD.update(R);
			});
		}

		Ctrl.browseListas = () => {

			let Config = {
				bdd_id: Ctrl.BDDSel.id,
			};

			$mdDialog.show({
				controller: 'BDD_ListasDiagCtrl',
				templateUrl: '/Frag/BDD.BDD_ListasDiag',
				locals: { Config: Config },
				clickOutsideToClose: true, fullscreen: false, multiple: true,
			});
		}
	}
]);
angular.module('BDD_ListasDiagCtrl', [])
.controller(   'BDD_ListasDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'Config',
	function ($scope, $rootScope, $mdDialog, Config) {

		console.info('BDD_ListasDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		var DefConfig = {
		};

		Ctrl.Config = angular.extend(DefConfig, Config);

		//Obtener las listas
		Rs.http('api/Bdds/get-listas', { bdd_id: Ctrl.Config.bdd_id }, Ctrl, 'Listas').then(() => {
			if(Ctrl.Listas.length > 0){
				Ctrl.Config.lista_id = Ctrl.Listas[0].id;
				Ctrl.getIndices();
			}
		});

		//Obtener los indices
		Ctrl.getIndices = () => {
			Ctrl.IndiceSel = null;

			Rs.http('api/Bdds/get-indices', { lista_id: Ctrl.Config.lista_id }).then(r => {
				Ctrl.Indices = r.Indices;
				//Ctrl.openLista(Ctrl.Listas[2]);
			});
		}
		

		Ctrl.openIndice = (I) => {
			Ctrl.IndiceSel = I;
			Ctrl.Detalles = null;
			Rs.http('api/Bdds/get-listadetalles', { lista_id: Ctrl.Config.lista_id, indice_cod: I.IndiceCod }, Ctrl, 'Detalles');
		};

		Ctrl.selectLista = () => {
			$mdDialog.hide({ lista_id: Ctrl.Config.lista_id, indice_cod: Ctrl.IndiceSel.IndiceCod, indice_des: Ctrl.IndiceSel.IndiceDes });
		};

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }
	}

]);
angular.module('BotsCtrl', [])
.controller('BotsCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdDialog) {

		console.info('BotsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Black';
		Ctrl.BotsNav = true;
		Ctrl.BotRunning = false;

		Ctrl.EstadosBots = ['Espera', 'Corriendo', 'Inactivo', 'Error'];
		Ctrl.EstadosBotsDet = {
			'Espera':    { Color: '#03ab3b' },
			'Corriendo': { Color: '#d3ff76' },
			'Inactivo':  { Color: '#545454' },
			'Error':     { Color: '#ff0000' },
		};

		Ctrl.BotsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Bots' });
		Ctrl.PasosCRUD = $injector.get('CRUD').config({ base_url: '/api/Bots/pasos' });
		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Bots/variables' });

		Ctrl.getBots = () => {
			Ctrl.BotsCRUD.get().then(() => {

				var bot_sel_id = Rs.Storage.BotSelId ? Rs.getIndex(Ctrl.BotsCRUD.rows, Rs.Storage.BotSelId) : 0;
				Ctrl.openBot(Ctrl.BotsCRUD.rows[bot_sel_id]);
			});
		}
		
		Ctrl.addBot = () => {
			Ctrl.BotsCRUD.dialog({ Estado: 'Inactivo' }, {
				title: 'Crear Nuevo Bot',
				only: ['Nombre']
			}).then(nB => {
				Ctrl.BotsCRUD.add(nB);
			});
		};

		Ctrl.openBot = (B) => { 
			Ctrl.BotSel = B;
			Rs.Storage.BotSelId = Ctrl.BotSel.id;
			Ctrl.BotSel.config.Horas = Ctrl.BotSel.config.Horas.map(H => {
				var D = moment(H, ['HH:mm']).toDate();
				return D;
			});
			//
			Ctrl.PasosCRUD.setScope('bot', Ctrl.BotSel.id).get();
			Ctrl.VariablesCRUD.setScope('bot', Ctrl.BotSel.id).get();

			//Ctrl.seeLogs();
		}

		Ctrl.saveBot = async () => {
			var Bot = angular.copy(Ctrl.BotSel);
			Bot.config.Horas = Bot.config.Horas.map(H => {
				var D = moment(H).format('HH:mm');
				console.log(H, D);
				return D;
			});
			await Ctrl.BotsCRUD.update(Bot);

			var Variables = Ctrl.VariablesCRUD.rows.filter(V => V.changed);
			if(Variables.length > 0) await Ctrl.VariablesCRUD.updateMultiple(Variables);

			await Ctrl.PasosCRUD.updateMultiple(Ctrl.PasosCRUD.rows);

			Rs.showToast('Bot Actualizado', 'Success');
		}
 	
		Ctrl.DiasSemana = [
			[ 'Lun', 'Lun'],
			[ 'Mar', 'Mar'],
			[ 'Mie', 'Mié'],
			[ 'Jue', 'Jue'],
			[ 'Vie', 'Vie'],
			[ 'Sab', 'Sáb'],
			[ 'Dom', 'Dom'],
		];

		Ctrl.addHour = () => {
			var Horas = Ctrl.BotSel.config.Horas;
			var Time = (Horas.length > 0) ? moment(Horas[(Horas.length - 1)]) : moment('05:00', ['H:m']);
			Time.add(1, 'hours');
			Horas.push(Time.toDate());
		}

		Ctrl.setHour = (H, kH) => {
			Ctrl.BotSel.config.Horas[kH] = H;
		}

		Ctrl.removeHour = (kH) => {
			Ctrl.BotSel.config.Horas.splice(kH, 1);
		}

		//Pasos
		Ctrl.addPaso = () => {
			var Indice = Ctrl.PasosCRUD.rows.length;
			Ctrl.PasosCRUD.dialog({
				Tipo: 'Url', Indice: Indice, bot_id: Ctrl.BotSel.id, config: '[]'
			}, {
				title: 'Agregar Paso', class: 'wu600',
				only: ['Tipo', 'Nombre'],
				confirmText: 'Agregar'
			}).then(nP => {
				if(!nP) return;
				Ctrl.PasosCRUD.add(nP);
			});
		}

		Ctrl.delPaso = (P) => {
			Rs.confirmDelete({
				Title: '¿Eliminar el paso: "'+P.Nombre+'"?',
			}).then((d) => {
				if(!d) return;
				Ctrl.PasosCRUD.delete(P);
			});
		}

		//Variables
		Ctrl.addVariable = () => {
			Ctrl.VariablesCRUD.add({
				bot_id: Ctrl.BotSel.id
			});
		}

		Ctrl.delVariable = (V) => {
			Ctrl.VariablesCRUD.delete(V);
		}


		//Run
		Ctrl.runBot = () => {
			Ctrl.BotRunning = true;
			Rs.http('/api/Bots/run/' + Ctrl.BotSel.id, {}, false, false, 'GET').finally(() => {
				Ctrl.BotRunning = false;
				Ctrl.getBots();
			});
		}


		Ctrl.seeLogs = () => {
			$mdDialog.show({
				controller: 'Bot_LogsCtrl',
				templateUrl: '/Frag/Bots.Bot_Logs',
				locals: { Bot : Ctrl.BotSel },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		}


		//ACE
		Ctrl.aceOptionsJs = {
			theme:'twilight',
			mode: 'json',
			onLoad: (_editor) => {
				var _session = _editor.getSession();
				_editor.setFontSize(15);
				_session.setTabSize(3);
				_editor.setOptions({
				    minLines: 1,
				    maxLines: 5000
				});
				//_editor.setUseSoftTabs(true);
			},
		};

		Ctrl.aceOptionsSql = {
			theme:'twilight',
			mode: 'sql',
			onLoad: (_editor) => {
				var _session = _editor.getSession();
				_editor.setFontSize(13);
				_session.setTabSize(3);
				_editor.setOptions({
				    minLines: 2,
				    maxLines: 5000
				});
				//_editor.setUseSoftTabs(true);
			},
		};




		Promise.all([
			Rs.http('api/Bdds/all', {}, Ctrl, 'Bdds')
		]).then(() => {
			Ctrl.getBots();
		});
		




	}
]);
angular.module('Bot_LogsCtrl', [])
.controller(   'Bot_LogsCtrl', ['$scope', '$rootScope', '$mdDialog', 'Bot',
	function ($scope, $rootScope, $mdDialog, Bot) {

		console.info('Bot_LogsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Bot = Bot;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.filters = {
			bot_id: Bot.id,
			Inicio: moment().add(-2, 'days').toDate(),
			Fin:    moment().toDate()
		};

		Ctrl.getLogs = () => {
			var filters = angular.copy(Ctrl.filters);
			filters.Inicio = moment(filters.Inicio).format('YYYY-MM-DD');
			filters.Fin    = moment(filters.Fin).format('YYYY-MM-DD');

			Rs.http('/api/Bots/logs', filters, Ctrl, 'BotLogs');

		}	

		Ctrl.getLogs();
	}

]);
angular.module('ConsultasSQLCtrl', [])
.controller('ConsultasSQLCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ConsultasSQLCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.FechaIni = moment().add(-6, 'days').format('YYYY-MM-DD');
		Ctrl.FechaFin = moment().format('YYYY-MM-DD');

		Ctrl.FechaAct = angular.copy(Ctrl.FechaIni);

		Ctrl.Consultas = [
			{ Nombre: 'Ejecución PGP', url: '/api/ConsultasSQL/pgp-nt' },
		];
		Ctrl.ConsultaSel = Ctrl.Consultas[0];

		Ctrl.Status = 'Stopped';

		Ctrl.Go = () => {
			Ctrl.Status = 'Playing';
			Ctrl.Step();
		};

		Ctrl.Pause = () => {
			Ctrl.Status = 'Paused';
		};

		Ctrl.Stop = () => {
			Ctrl.Status = 'Stopped';
			Ctrl.FechaAct = moment(angular.copy(Ctrl.FechaIni)).format('YYYY-MM-DD');
			Ctrl.Report = [];
		};

		Ctrl.Report = [];

		Ctrl.Step = () => {
			var startTime = performance.now();

			Rs.http(Ctrl.ConsultaSel.url, { Dia: Ctrl.FechaAct }).then(() => {
				
				if(Ctrl.Status == 'Playing'){

					var endTime = performance.now();
					var timeDiff = (endTime - startTime) / 1000; 
					var seconds = Math.round(timeDiff);
					
					Ctrl.Report.unshift({ Dia: Ctrl.FechaAct, Tiempo: seconds });

					if(Ctrl.FechaAct == Ctrl.FechaFin) return Ctrl.Pause();

					var NewDay = moment(Ctrl.FechaAct).add(1, 'day').format('YYYY-MM-DD');
					Ctrl.FechaAct =  NewDay;
					


					Ctrl.Step();
				}

			});
		}
		
	}
]);
angular.module('BasicDialogCtrl', [])
.controller(   'BasicDialogCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;
		Ctrl.periodDateLocale = {
			formatDate: (date) => {
				if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){ return null; }else{
					return moment(date).format('YMM');
				}
			}
		};

		Ctrl.Cancel = function(){
			$mdDialog.hide();
		}

		Ctrl.SendData = function(){
			$mdDialog.hide(Ctrl.Config);
		}

		Ctrl.selectItem = (Field, item) => {
			if(!Field.opts.itemVal){
				Field.Value = item;
			}else{
				Field.Value = item[Field.opts.itemVal];
			}
			
		};

		Ctrl.Delete = function(ev) {
			if(Config.HasDelete){
				Config.HasDeleteConf = true;

				Ctrl.SendData();
			}
		}
	}

]);
angular.module('BottomSheetCtrl', [])
.controller('BottomSheetCtrl', ['$scope', '$rootScope', '$mdBottomSheet', 'Config', 
	function($scope, $rootScope, $mdBottomSheet, Config) {

		var Ctrl = $scope;
		var Rs = $rootScope;
	
		Ctrl.Cancel = function(){ $mdBottomSheet.cancel(); }

		Ctrl.Config = angular.copy(Config);

		Ctrl.Send = function(Item){
			$mdBottomSheet.hide(Item);
		}
	}
]);
angular.module('ConfirmCtrl', [])
.controller(   'ConfirmCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}

		Ctrl.Send = function(val){
			$mdDialog.hide(val);
		}
		
	}

]);
angular.module('ConfirmDeleteCtrl', [])
.controller(   'ConfirmDeleteCtrl', ['$scope', 'Config', '$mdDialog', 
	function ($scope, Config, $mdDialog) {

		var Ctrl = $scope;

		Ctrl.Config = Config;

		Ctrl.Cancel = function(){
			$mdDialog.hide(false);
		}

		Ctrl.Delete = function(){
			$mdDialog.hide(true);
		}
		
	}

]);
angular.module('CRUDDialogCtrl', [])
.controller('CRUDDialogCtrl', ['$rootScope', '$scope', '$mdDialog', 'ops', 'config', 'columns', 'Obj', 'rows', 
	function($rootScope, $scope, $mdDialog, ops, config, columns, Obj, rows) {

		console.info('CRUDDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.config = {};
		Ctrl.columns = columns;
		Ctrl.Obj = {};
		//console.log(columns);
		//Ctrl.Obj = angular.copy(Obj);

		//Saber si es nuevo
		Ctrl.new = !(ops.primary_key in Obj);
		Ctrl.config.confirmText = Ctrl.new ? 'Crear' : 'Guardar';
		Ctrl.config.title = Ctrl.new ? ('Nuevo '+ops.name) : ('Editando '+ops.name);
		Ctrl.config.delete_title = '¿Borrar '+ops.name+'?';

		angular.forEach(columns, function(F){
			if(F.Default !== null){
				var DefValue = angular.copy(F.Default);
				Ctrl.Obj[F.Field] = DefValue;
			};

			F.show = true;
			if(config.only.length > 0){
				F.show = Rs.inArray(F.Field, config.only);
			}else if(config.except.length > 0){
				F.show = !Rs.inArray(F.Field, config.except);
			};
		});

		angular.extend(Ctrl.Obj, Obj);
		angular.extend(Ctrl.config, config);

		Ctrl.cancel = function(){ $mdDialog.hide(false); };

		Ctrl.sendData = function(){
			//Verificar los Uniques
			var Errors = 0;
			angular.forEach(columns, function(C){
				if(C.Unique){
					//console.log(ops.primary_key, Ctrl.Obj[ops.primary_key]);
					var except = Ctrl.new ? false : [ ops.primary_key, Ctrl.Obj[ops.primary_key] ];
					var Found = Rs.found(Ctrl.Obj[C.Field], rows, C.Field, undefined, except );
					if(Found) Errors++;
				};
			});

			if(Errors > 0) return false;

			$mdDialog.hide(Ctrl.Obj);
		};


		Ctrl.delete = function(ev){
			var config = {
				Title: Ctrl.config.delete_title,
			};

			Rs.confirmDelete(config).then(function(del){
				if(del){
					$mdDialog.hide('DELETE');
				};
			});
		};


		
		//Campos
		//Ctrl.fields = angular.copy

	}
]);
angular.module('ExternalLinkCtrl', [])
.controller(   'ExternalLinkCtrl', ['$scope', 'Link', '$mdDialog', '$sce',  
	function ($scope, Link, $mdDialog, $sce) {

		var Ctrl = $scope;

		Ctrl.Link = $sce.trustAsResourceUrl(Link);

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}
		
	}

]);
angular.module('FileDialogCtrl', [])
.controller('FileDialogCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', '$mdToast', 'FileSel', 
	function($scope, $rootScope, $http, $mdDialog, $mdToast, FileSel) {

		console.info('FileDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.FileSel = FileSel;
		Ctrl.inArray = Rs.inArray;

		//Dialog
		Ctrl.Cancel = function(){
			$mdDialog.hide();
		};

	}
]);
angular.module('IconSelectDiagCtrl', [])
.controller(   'IconSelectDiagCtrl', ['$scope',  '$mdDialog', '$http', '$filter',
	function ($scope, $mdDialog, $http, $filter) {

		var Ctrl = $scope;
		Ctrl.Cancel = function(){ $mdDialog.cancel(); }
		Ctrl.filter = ''; Ctrl.CatSel = null;

		$http.get('/api/Main/iconos').then((r) => {
			Ctrl.Categorias = r.data.Categorias;
			Ctrl.IconosRaw	= r.data.Iconos;
		});

		Ctrl.Iconos = [];

		Ctrl.filterCat = (C) => { Ctrl.CatSel = C; Ctrl.filterIconos(); }

		Ctrl.filterIconos = () => {
			console.log(Ctrl.CatSel, Ctrl.filter);
			if(Ctrl.CatSel == null && Ctrl.filter == ''){ Ctrl.Iconos = []; }
			else if(Ctrl.filter !== ''){   Ctrl.Iconos = $filter('filter')(Ctrl.IconosRaw, Ctrl.filter) }
			else if(Ctrl.CatSel !== null){ Ctrl.Iconos = $filter('filter')(Ctrl.IconosRaw, { Categoria: Ctrl.CatSel }) };
		};

		Ctrl.selectIcon = (I) => {
			$mdDialog.hide(I.IconoFull);
		};
		
	}

]);
angular.module('ImageEditor_DialogCtrl', [])
.controller(   'ImageEditor_DialogCtrl', ['$scope', '$rootScope', '$mdDialog', '$mdToast', '$timeout', '$http', 'Upload', 'Config', 
	function ($scope, $rootScope, $mdDialog, $mdToast, $timeout, $http, Upload, Config) {

		var Ctrl = $scope;
		var Rs = $rootScope;

		//console.info('-> Image Editor');

		Ctrl.Config = {
			Theme : 'Snow_White',		//El tema
			Title: 'Cambiar Imágen',	//El Titulo
			CanvasWidth:  350,			//Ancho del canvas
			CanvasHeight: 350,			//Alto del canvas
			CropWidth:  100,			//Ancho del recorte que se subirá
			CropHeight: 100,			//Alto del recorte que se subirá
			MinWidth:  50,				//Ancho mínimo del selector
			MinHeight: 50,				//Ancho mínimo del selector
			KeepAspect: true,			//Mantener aspecto
			Preview: false,				//Mostrar vista previa
			PreviewClass: '',			//md-img-round
			RemoveOpt: false,			//Si es texto muestra la opcion de borrar
			Daten: null,				//La data a enviar al servidor
			Class: '',
			UploadUrl: '/api/Main/upload-image',
			UploadPath: null,
			ImageMode: null,
			RemoveImage: false
		};

		Ctrl.RotationCanvas = document.createElement("canvas");

		Ctrl.cropper = {};
		Ctrl.cropper.sourceImage = null;
		Ctrl.cropper.croppedImage = null;
		Ctrl.bounds = {};

		Ctrl.Progress = null;

		angular.extend(Ctrl.Config, Config);

		Ctrl.CancelText = Ctrl.Config.RemoveOpt ? Ctrl.Config.RemoveOpt : 'Cancelar';
		
		Ctrl.CancelBtn = function(){
			if(!Ctrl.Config.RemoveOpt){
				Ctrl.Cancel();
			}else{
				$http.post('/api/Upload/remove', { Path: Ctrl.Config.Daten.Path }).then(function(){
					$mdDialog.hide({Removed: true});
				});
			}
		}

		Ctrl.Cancel = function(){
			$mdDialog.hide();
		}

		Ctrl.Rotar = function(dir){
			var canvas = Ctrl.RotationCanvas;
			var ctx = canvas.getContext("2d");

			var image = new Image();
			image.src = Ctrl.cropper.sourceImage;
			image.onload = function() {
				canvas.width = image.height;
				canvas.height = image.width;
				ctx.rotate(dir * Math.PI / 180);
				ctx.translate(0, -canvas.width);
				ctx.drawImage(image, 0, 0); 
				Ctrl.cropper.sourceImage = canvas.toDataURL();
			};
		}

		Ctrl.$watch('Ctrl.cropper.sourceImage', function(nv, ov){
			if(nv){
				console.log('Imagen Cargada');
			}
		});

		Ctrl.SendImage = function(){

			var Daten = {
				file: Upload.dataUrltoBlob(Ctrl.cropper.croppedImage),
				Quality: 90,
				savepath: Ctrl.Config.UploadPath,
				imagemode: Ctrl.Config.ImageMode
			};

			angular.extend(Daten, Config.Daten);

			Upload.upload({

				url: Ctrl.Config.UploadUrl,
				data: Daten,

			}).then(function (res) {
				
				$timeout(function () {
					$mdDialog.hide(res.data);
				});

			}, function (response) {
				if (response.status > 0){
					
					var Msg = response.status + ': ' + response.data;
					var errTxt = '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>';

					$mdToast.show({
						template: errTxt,
						hideDelay: 5000
					});

				}
			}, function (evt) {
				Ctrl.Progress = parseInt(100.0 * evt.loaded / evt.total);
			});

		}

		//console.log(angular.element(document.querySelector('#Canvas')));
	}

]);
angular.module('ImportCtrl', [])
.controller('ImportCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', 'Upload', 'Config',
	function($scope, $rootScope, $http, $mdDialog, Upload, Config) {

		console.info('ImportCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		var DefConfig = {
			Paso: 1,
		};
		Ctrl.Config = Config;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.Pasos = [ '',
			'Paso 1: Diligenciar la plantilla',
			'Paso 2: Verificar datos a importar',
			'Paso 3: Importando',
			'Finalizado',
			'Errores encontrados',
			'Error al cargar el archivo'
		];

		Ctrl.Config.Paso = 1;

		Ctrl.DownloadPlantilla = function(){
			$http.get(Ctrl.Config.PlantillaUrl, { responseType: 'arraybuffer' }).then(function(r) {
        		var blob = new Blob([r.data], { type: "application/vnd.ms-excel; charset=UTF-8" });
		        var filename = Ctrl.Config.PlantillaUrl.split('/').pop();
		        saveAs(blob, filename);
        	});
		};


		Ctrl.UploadTemplate = function(file, invalidfile){
			if(file) {
	            Upload.upload({
					url: '/api/Upload/file',
					data: {
						file: file,
						Path: Ctrl.Config.Upload.Path,
						Name: Ctrl.Config.Upload.Name,
					}
				}).then(function(r){
					if(r.status == 200){
						Ctrl.VerifyData();
					}else{
						Ctrl.Config.Paso = 6;
					};
				});
			};
		};

		Ctrl.VerifyData = function(){
			Ctrl.Config.Paso = 2;
			$http.post(Ctrl.Config.VerifyUrl, { Config: Ctrl.Config }).then(function(r){
				var Msgs = r.data;
				console.log(Msgs);
				if(Msgs.length == 0){
					Ctrl.Config.Paso = 3;
				}else{ //Hubo errores en la verificacion
					Ctrl.Config.Paso = 5;
					Ctrl.Errores = Msgs;
				}
			});
		}

		//Ctrl.VerifyData();

		Ctrl.DownloadErrors = function(){
			var Headers = [ 'Fila', 'Error' ];
			var e = {
        		filename: 'Errores_Importacion',
        		ext: 'xls',
        		sheets: [
        			{
						name: 'Errores',
						headers: Headers,
						rows: Ctrl.Errores,
					}
        		]
			};
			Rs.DownloadExcel(e);
		};

		//console.log(Ctrl.Config.PlantillaUrl);

	}
]);
angular.module('ListSelectorCtrl', [])
.controller('ListSelectorCtrl', ['$scope', '$rootScope', '$http', '$mdDialog', 'List', 'Config',
	function($scope, $rootScope, $http, $mdDialog, List, Config) {

		//console.info('ListSelectorCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Config = Config;
		Ctrl.Searching = false;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.getData = function(){
			Ctrl.Searching = true;
			//Traer los datos del servidor
			$http({
				method: Ctrl.Config.remoteMethod,
				url: Ctrl.Config.remoteUrl,
				data: Ctrl.Config.remoteData,
			}).then(function(r){
				Ctrl.Searching = false;
				Ctrl.List = r.data;
			}, function(){
				Ctrl.Searching = false;
			});
		};

		//Si pasan la lista usarla
		if(List !== null){
			Ctrl.List = List;
		}else if(Ctrl.Config.remoteUrl){
			Ctrl.getData();
		};

		Ctrl.changeSearch = function(){

			if(Ctrl.Config.remoteQuery){
				if(Ctrl.Searching) return false;
				Ctrl.Config.remoteData.filter = Ctrl.Search;
				Ctrl.getData();
			}else{
				Ctrl.SearchFilter = Ctrl.Search;
			}
		}

		Ctrl.Resp = function(Row){
			$mdDialog.hide(Row);
		}


	}
]);
angular.module('RetroalimentarDiagCtrl', [])
.controller(   'RetroalimentarDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'Subject',
	function ($scope, $rootScope, $mdDialog, Subject) {

		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Stage = 'Writting';
		

		Ctrl.Cancel = function(){
			$mdDialog.cancel();
		}

		Ctrl.feedbackComment = '';
		Ctrl.Subject = Subject;

		Ctrl.enviarFeedback = () => {

			if(Ctrl.feedbackComment.trim() == '') return Rs.showToast('Por favor incluya un comentario', 'Error');

			Ctrl.Stage = 'Sending';

			Rs.http('api/Main/feedback', { Subject: Ctrl.Subject, feedbackComment: Ctrl.feedbackComment, usuario_id: Rs.Usuario.id }).then(() => {
				Ctrl.Stage = 'Sent';
			});

		}
	}

]);
angular.module('TableDialogCtrl', [])
.controller('TableDialogCtrl', ['$scope', '$rootScope', '$mdDialog', 'Elements', 'Config',
	function($scope, $rootScope, $mdDialog, Elements, Config) {

		//console.info('TableDialogCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Config = Config;
		Ctrl.Searching = false;
		Ctrl.Elements = Elements;

		Ctrl.Cancel = function(){ $mdDialog.cancel(); }

		Ctrl.getProp = (Obj, Prop) => {
			return Prop.split('.').reduce(function(a, b) {
				return a[b];
			}, Obj);
		}

		Ctrl.Resp = function(){

			if(Config.pluck){
				var Sel = Ctrl.Config.selected.map( e => e[Config.primaryId] );
			}else{
				var Sel = Ctrl.Config.selected;
			}

			$mdDialog.hide(Sel);
		}


	}
]);
angular.module('EntidadesCamposCtrl', [])
.controller('EntidadesCamposCtrl', ['$scope', '$rootScope', 
	function($scope, $rootScope) {

		console.info('EntidadesCamposCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		
	}
]);
angular.module('EntidadesCtrl', [])
.controller('EntidadesCtrl', ['$scope', '$rootScope', '$injector', '$mdDialog', '$filter', '$timeout',
	function($scope, $rootScope, $injector, $mdDialog, $filter, $timeout) {

		console.info('EntidadesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';
		if(!Rs.Storage.EntidadSidenav) Rs.Storage.EntidadSidenav = true;
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
				Rs.http('api/Procesos', {}, Ctrl, 'Procesos'),
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
				//Ctrl.getFsEntidades();
				var ids_procesos = Ctrl.EntidadesCRUD.rows.map(e => e.proceso_id).filter((v, i, a) => a.indexOf(v) === i);
				Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos.filter(p => ids_procesos.includes(p.id)),'Ruta','Proceso',false,true);
				angular.forEach(Ctrl.ProcesosFS, (P) => {
					if(P.type == 'folder'){
						P.file = Ctrl.Procesos.find(p => p.Ruta == P.route);
					}
				});

				if(Rs.Storage.EntidadSelId){
					var entidad_sel_id = Rs.getIndex(Ctrl.EntidadesCRUD.rows, Rs.Storage.EntidadSelId);
					Ctrl.openEntidad(Ctrl.EntidadesCRUD.rows[entidad_sel_id]);
				};

				Ctrl.navToSubsection(Rs.Storage.EntidadSubseccion);
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

		Ctrl.openProceso = (P) => { Ctrl.ProcesoSelId = P.id; }

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
angular.module('Entidades_AddColumnsCtrl', [])
.controller('Entidades_AddColumnsCtrl', ['$scope', '$mdDialog', 'ParentCtrl', 'newCampos',
	function($scope, $mdDialog, ParentCtrl, newCampos) {

		console.info('Entidades_AddColumnsCtrl');
		var Ctrl = $scope;

		Ctrl.CancelDiag = () => {
			$mdDialog.cancel();
		};

		Ctrl.EntidadSel = ParentCtrl.EntidadSel;
		Ctrl.newCampos = newCampos;
		Ctrl.newCamposSel = [];
		Ctrl.TiposCampo 	 = ParentCtrl.TiposCampo;
		Ctrl.markChanged 	 = ParentCtrl.markChanged;
		Ctrl.setTipoDefaults = ParentCtrl.setTipoDefaults;
		Ctrl.inArray         = ParentCtrl.inArray;

		Ctrl.addNewColumns = () => {
			$mdDialog.hide(Ctrl.newCamposSel);
		};
	}
]);
angular.module('Entidades_Campos_ImagenConfigCtrl', [])
.controller('Entidades_Campos_ImagenConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_ImagenConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.C = C;

		Ctrl.ImageModes = [ 'Recortar', 'Ajustar Ancho', 'Ajustar Alto', 'Contener' ];

		var ConfigDefault = {
			img_ruta: '/img/photos/$id.jpg',
			img_width: 450, img_height: 350,
			img_uploader: Rs.Usuario.url + 'api/Main/upload-image',
			img_imagemode: 'Recortar',
			img_quickpreview: true,
		};

		//console.log(Rs.Usuario);

		Ctrl.C.Config = angular.extend({}, ConfigDefault, C.Config);

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.C);
		};

	}
]);
angular.module('Entidades_Campos_ListaConfigCtrl', [])
.controller('Entidades_Campos_ListaConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_ListaConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.calcTextColor = Rs.calcTextColor;
		Ctrl.C = C;

		var ConfigDefault = {
			opciones: [],
		};

		Ctrl.C.Config = angular.extend({}, ConfigDefault, C.Config);

		Ctrl.addElemento = (newOpt) => {
			if(!newOpt) return;
			newOpt = newOpt.trim();
			if(newOpt !== ''){
			
				Ctrl.C.Config.opciones.push({
					value: newOpt,
					desc:  '',
					color: '#ffffff', icono: null
				});

			};

			Ctrl.newOpt = '';
		};

		Ctrl.removeElemento = (kOp) => {
			Ctrl.C.Config.opciones.splice(kOp, 1);
		};

		Ctrl.dragListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; }
		};

		Ctrl.changeIcon = (Op) => {
			Rs.selectIconDiag().then(r => {
				console.log(r);
				//if(!r) return;
				Op.icono = r;
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.C);
		};

	}
]);
angular.module('Entidades_CargadorDiagCtrl', [])
.controller('Entidades_CargadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Upload',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Upload) {

		console.info('Entidades_CargadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.ExcludeTipos = ['Sin Valor','Variable de Sistema'];
		Ctrl.Etapa = 'PreLoad';
		Ctrl.pag_pages = 50;
		Ctrl.pag_from  = 0;
		Ctrl.pag_to    = null;
		//Ctrl.Etapa = 'TestLoad';

		var load_data_raw = [];
		Ctrl.load_data_len = 0;

		Ctrl.pag_go = (i) => {
			var from = (Ctrl.pag_from + (Ctrl.pag_pages*i) );
			if(from < 0 || from >= Ctrl.load_data_len) return false;
			Ctrl.pag_from = from;
			Ctrl.pag_to = Math.min((Ctrl.pag_from + Ctrl.pag_pages), (Ctrl.load_data_len));
			Ctrl.load_data = load_data_raw.slice(Ctrl.pag_from, Ctrl.pag_to);
		};

		Ctrl.ConfTipoArchivo = {
			csv: ['text/*'],
		};

		Ctrl.getCargador = (cargador_id) => {
			Rs.http('api/Entidades/cargador-get', { cargador_id: cargador_id }, Ctrl, 'Cargador').then(() => {
				
			});
		};

		Ctrl.upload = (file) => {
			if(!file) return;

			Ctrl.Etapa = 'Loading';

			Upload.upload({
	            url: 'api/Entidades/cargador-upload',
	            data: {file: file, 'cargador_id': Ctrl.Cargador.id }
	        }).then((r) => {
	            Ctrl.Etapa = 'TestLoad';
	            Ctrl.Entidad   = r.data.entidad;
	            load_data_raw = r.data.load_data;

	            Ctrl.pag_from      = 0;
	            Ctrl.load_data_len = load_data_raw.length;
	            Ctrl.pag_go(0);

	        }, (r) => {
	        	Rs.showToast('Ocurrió un error, por favor reintente','Error');
	            Ctrl.Etapa = 'PreLoad';
	        }, (evt) => {
	            //var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
	            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
	        });
		};

		Ctrl.sendData = () => {
			Ctrl.Etapa = 'Loading';
			Rs.http('api/Entidades/cargador-insert', { Cargador: Ctrl.Cargador, Entidad: Ctrl.Entidad, load_data: load_data_raw }).then(() => {
				Rs.showToast('Se cargaron '+Ctrl.load_data_len+' registros','Success',7500,'bottom right');
				Ctrl.Etapa = 'PreLoad';
			}, () => {
				Rs.showToast('Ocurrió un error, por favor reintente','Error');
				Ctrl.Etapa = 'TestLoad';
			});
		};

	}
]);
angular.module('Entidades_CargadoresCtrl', [])
.controller('Entidades_CargadoresCtrl', ['$scope', '$rootScope', '$timeout', '$filter',
	function($scope, $rootScope, $timeout, $filter) {

		var Ctrl = $scope.$parent;
		var Rs = $rootScope;

		$scope.CargadoresSidenav = false;
		$scope.CargadoresCamposSel = [];

		$scope.TiposArchivo = {
			csv:   [ 'Archivo .CSV',  			'fa-file-csv' ],
			excel: [ 'Archivo Excel .XLSX', 	'fa-file-excel' ],
		};

		$scope.TiposValor = ['Columna','Fijo','Variable de Sistema','Sin Valor'];

		var DefConfig = {
			tipo_archivo: 'csv',
			delimiter: ',',
			with_headers: true,
			campos: {}
		};

		//Cargadores
		Ctrl.getCargadores = () => {
			if(!Ctrl.EntidadSel) return;
			Ctrl.CargadoresCRUD.setScope('entidad', Ctrl.EntidadSel.id);
			Ctrl.CargadoresCRUD.get().then(() => {
				if(Ctrl.CargadoresCRUD.rows.length > 0){
					Ctrl.openCargador(Ctrl.CargadoresCRUD.rows[0]);
				}else{
					$scope.CargadoresSidenav = true;
				};
			});
		};

		Ctrl.addCargador = () => {
			Ctrl.CargadoresCRUD.dialog({
				entidad_id: Ctrl.EntidadSel.id,
				Titulo: 'General', Secciones: []
			}, {
				title: 'Crear Cargador',
				only: ['Titulo']
			}).then((R) => {
				if(!R) return; Ctrl.CargadoresCRUD.add(R);
			});
		};

		Ctrl.openCargador = (G) => {
			G.Config = angular.extend({}, DefConfig, G.Config);
			Ctrl.CargadorSel = G;

			angular.forEach(Ctrl.CamposCRUD.rows, (C) => {
				if(!Ctrl.CargadorSel.Config.campos.hasOwnProperty(C.id)){
					Ctrl.CargadorSel.Config.campos[C.id] = {
						campo_id: C.id,
						tipo_valor: 'Sin Valor',
						Defecto: null
					};
				};
			});

			//Rs.viewCargadorDiag(Ctrl.CargadorSel.id);

		};

		Ctrl.updateCargador = () => {
			Ctrl.CargadoresCRUD.update(Ctrl.CargadorSel).then(() => {
				Rs.showToast('Cargador Actualizado', 'Success');
			});
		};

		Ctrl.getCargadores();

	}
]);
angular.module('Entidades_EditorConfigDiagCtrl', [])
.controller('Entidades_EditorConfigDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'B', 'TiposCampo', 'GridColumnas',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, B, TiposCampo, GridColumnas) {

		console.info('Entidades_EditorConfigDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.queryElm = Rs.queryElm;
		Ctrl.inArray  = Rs.inArray;
		Ctrl.TiposCampo = TiposCampo;
		Ctrl.B = B;
		Ctrl.GridColumnas = GridColumnas;
		Ctrl.TiposValor = ['Por Defecto','Columna','Fijo','Sin Valor'];


		Ctrl.getEditor = () => {
			if(!B) return;
			Rs.http('api/Entidades/editor-get', { editor_id: B.accion_element_id }, Ctrl, 'Editor').then(() => {
				console.log(B);
				angular.forEach(Ctrl.Editor.campos, (C) => {
					
					if(typeof Ctrl.B[C.id] == 'undefined'){

						if(Ctrl.B.modo == 'Crear'){
							Ctrl.B.campos[C.id] = { tipo_valor: 'Por Defecto' };
						};

						if(Ctrl.B.modo == 'Editar'){
							var columnas   = $filter('filter')(GridColumnas, { campo_id: C.campo_id });
							var columna_id = (columnas.length > 0) ? columnas[0]['id'] : null;
							Ctrl.B.campos[C.id] = { tipo_valor: 'Columna', columna_id: columna_id };
						};
					};
				});
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.B);
		};

		Ctrl.getEditor();

	}
]);
angular.module('Entidades_EditorDiagCtrl', [])
.controller('Entidades_EditorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Upload',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Upload) {

		console.info('Entidades_EditorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray = Rs.inArray;
		Ctrl.submitForm = Rs.submitForm;
		Ctrl.loading = true;

		var DefConfig = {
			color: '#e2e2e2', textcolor: 'black'
		};

		Ctrl.getEditor = (editor_id, Obj, Config) => {
			Ctrl.Config = angular.extend(DefConfig, Config);
			Ctrl.Config.llaveprim_val = (Ctrl.Config.modo == 'Crear') ? null : Obj.id;
			Rs.http('api/Entidades/editor-get', { editor_id: editor_id, Obj: Obj, Config: Config }).then((Editor) => {
				Ctrl.prepEditor(Editor);
				Ctrl.loading = false;
			});
		};

		Ctrl.prepEditor = (Editor) => {
			angular.forEach(Editor.campos, (C) => {
				if(Rs.inArray(C.campo.Tipo, ['Fecha','Hora','FechaHora'])){
					C.val = Rs.parseDate(C.val);
				};

				if(C.campo.Tipo == 'ListaAvanzada'){
					if(C.campo.Op4 == 'AddDate' && C.val == '_SELECT_DATE_'){
						C.val_aux = Rs.parseDate(C.val_aux);
					}
				}

			});

			Ctrl.Editor = Editor;
		};

		Ctrl.searchEntidad = (C) => {
			if(C.val !== null) return false;
			var search_elms = C.campo.entidadext.config.search_elms;
			return Rs.http('api/Entidades/search', { entidad_id: C.campo.Op1, searchText: C.searchText, search_elms: search_elms });
		};

		Ctrl.selectedItem = (item, C) => {
			if(!item) return;
			C.val = item.C0;
		};

		Ctrl.clearCampo = (C) => {
			C.val = null; C.searchText = null; C.selectedItem = null;
		};

		Ctrl.enviarDatos = (ev) => {
			//return console.log(ev);

			Ctrl.loading = true;
			Rs.http('api/Entidades/editor-save', { Editor: Ctrl.Editor, Config: Ctrl.Config }).then(() => {
				Ctrl.loading = false;
				$mdDialog.hide(true);
			}, (d) => {
				Ctrl.loading = false;
				console.log(d);
				Rs.showToast('Ha ocurrido un error, por favor guarde la información e intente nuevamente.', 'Error');
			});
		};


		//Fields Changed
		Ctrl.changedField = (C) => {
			/*if(C.campo.Tipo == 'FechaHora'){
				C.val = moment(C.dateval).format('YYYY-MM-DD HH:mm');
			};

			console.log(C.val);*/
		};

		//Subir imágen
		Ctrl.uploadImage = (C, file) => {
			if(!file) return;
			let data = {
				width: C.campo.Config.img_width,
				height: C.campo.Config.img_height,
				imagemode: C.campo.Config.img_imagemode
			};

			Upload.upload({
            	url: C.campo.Config.img_uploader, method: 'POST', 
            	file: file,
            	data: data
	        }).then(function(r) {
	        	C.val.changed = true;
	            C.val.url = r.data;
	        });
		};
	}
]);
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


		Ctrl.dragEditorListener = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.EditoresCamposCRUD.rows, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};

		Ctrl.getEditores();

	}
]);
angular.module('Entidades_GridDiagCtrl', [])
.controller('Entidades_GridDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 
	function($scope, $rootScope, $mdDialog, $filter) {

		console.info('Entidades_GridDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.inArray = Rs.inArray;
		Ctrl.loadingGrid = false;
		Ctrl.sidenavSel = null;
		Ctrl.filterRows = '';
		Ctrl.orderRows = '';
		Ctrl.SidenavIcons = [
			['fa-filter', 						'Filtros'		,false],
			['fa-sign-in-alt fa-rotate-90', 	'Descargar'		,false],
			['fa-info-circle', 					'Información'	,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};

		Ctrl.Cancel = () => { $mdDialog.cancel(); };

		Ctrl.getOpcionLista = (Val, Config) => {
			return Config.opciones.find(el => el.value === Val);
		};

		var Data = null;
		var filteredData = null;
		Ctrl.Data = [];
		Ctrl.load_data_len = 0;
		Ctrl.pag_pages = 50;
		Ctrl.pag_from  = 0;
		Ctrl.pag_to    = null;

		Ctrl.pag_go = (i) => {
			var from = (Ctrl.pag_from + (Ctrl.pag_pages*i) );
			if(from < 0 || from >= Ctrl.load_data_len) return false;
			Ctrl.pag_from = from;
			Ctrl.pag_to = Math.min((Ctrl.pag_from + Ctrl.pag_pages), (Ctrl.load_data_len));
			Ctrl.Data = filteredData.slice(Ctrl.pag_from, Ctrl.pag_to);


		};

		Ctrl.filterData = () => {

			filteredData = Data.slice();
			if(Ctrl.filterRows.trim() !== '') filteredData = $filter('filter')(filteredData, Ctrl.filterRows);
			if(Ctrl.orderRows !== ''){
				var orderNum = parseInt(Ctrl.orderRows);
				filteredData = filteredData.sort((a,b) => {
					if(orderNum < 0){ //DESC
						return (a[(orderNum*-1)] < b[(orderNum*-1)]) ? 1 : -1;
					}else{
						return (a[orderNum]      > b[orderNum]     ) ? 1 : -1;
					};
				});
			};

			Ctrl.load_data_len = filteredData.length;
			Ctrl.pag_go(0);
		};

		Ctrl.reloadData = (emptyFilter = true) => {
			Ctrl.loadingGrid = true;
			Rs.http('api/Entidades/grids-reload-data', { Grid: Ctrl.Grid }).then((r) => {
				Ctrl.Grid.sql  = r.sql;
				Data = r.Data;

				Ctrl.loadingGrid = false;
				if(emptyFilter) Ctrl.filterRows = '';
				Ctrl.filterData();
			});
		};

		Ctrl.getSelectedText = (Text) => {
			if(Text === null) return 'Seleccionar...';
			if(angular.isArray(Text)){
				return JoinedText = Text.join(', ');
			};
			return Text;
		};
		
		Ctrl.getGrid = (grid_id) => {
			
			if(!grid_id) return;
			Ctrl.loadingGrid = true;
			Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
				Ctrl.Grid = r.Grid;
				Data = r.Data;

				if(Ctrl.Grid.filtros.length > 0) Ctrl.SidenavIcons[0][2] = true;
				
				Ctrl.loadingGrid = false;
				Ctrl.filterRows = '';
				Ctrl.filterData();
				//return Ctrl.triggerButton(Ctrl.Grid.Config.row_buttons[0], Data[0]); //TEST
				//return Ctrl.triggerButton(Ctrl.Grid.Config.main_buttons[0]); //TEST
			});
		};

		var prepRow = (R) => {
			if(!R) return null;
			var Obj = { id: R[0] };
			angular.forEach(Ctrl.Grid.columnas, (C, kC) => {
				if(C.id){ Obj[C.id] = { val: R[kC] }; };
			});
			return Obj;
		};

		Ctrl.triggerButton = (B,R) => {

			var Obj = prepRow(R);

			var DefConfig = {};
			if(Ctrl.AppSel){
				DefConfig = angular.extend(DefConfig, {
					color: Ctrl.AppSel.Color, textcolor: Ctrl.AppSel.textcolor
				});
			};

			if(B.accion == 'Editor'){
				Config = angular.extend(DefConfig, B);
				Rs.viewEditorDiag(B.accion_element_id, Obj, Config).then((r) => {
					if(!r) return;
					Ctrl.reloadData(false);
				});
			};
		};

		function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;        
        }

        function excelColName(n) {
			var ordA = 'a'.charCodeAt(0);
			var ordZ = 'z'.charCodeAt(0);
			var len = ordZ - ordA + 1;

			var s = "";
			while(n >= 0) {
				s = String.fromCharCode(n % len + ordA).toUpperCase() + s;
				n = Math.floor(n / len) - 1;
			}
			return s;
		}

		Ctrl.downloadData = () => {
			var wb = XLSX.utils.book_new();
	        wb.Props = {
	                Title: "SheetJS Tutorial",
	                CreatedDate: new Date(2017,12,19)
	        };

	        var SheetData = [ [] ];
	        var ColumnsNo = 0;
	        Ctrl.Grid.columnas.forEach((C) => {
	        	if(C.Visible){
	        		SheetData[0].push(C.column_title);
	        		ColumnsNo++;
	        	}
	        });

	        filteredData.forEach((Row) => {
	        	var RowData = [];
	        	Ctrl.Grid.columnas.forEach((C,kC) => {
	        		if(C.Visible){
		        		RowData.push(Row[kC]);
		        	}
		        });
		        SheetData.push(RowData);
	        });

			var ws = XLSX.utils.aoa_to_sheet(SheetData);
			var last_cell = excelColName(ColumnsNo - 1) + (Data.length + 1);
			ws['!autofilter'] = { ref: ('A1:'+last_cell) };
	        
	        XLSX.utils.book_append_sheet(wb, ws, "Datos");
	        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
	     
	        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), Ctrl.Grid.Titulo + '.xlsx');
		};

		//Previsualizar un Campo
		Ctrl.previewCampo = (C, val) => {
			if(!val || val == '') return;
			$mdDialog.show({
				templateUrl: 'Frag/Entidades.Entidades_GridDiag_PreviewDiag',
				controller: 'Entidades_GridDiag_PreviewDiagCtrl',
				locals: { C: C, val: val },
				clickOutsideToClose: true, fullscreen: false, multiple: true,
			});
		};
		
		//Ctrl.openSidenavElm(['fa-sign-in-alt fa-rotate-90', 'Descargar',false]) //FIX
		
	}
]);
angular.module('Entidades_GridDiag_PreviewDiagCtrl', [])
.controller('Entidades_GridDiag_PreviewDiagCtrl', ['$scope', '$rootScope', '$mdDialog', 'C', 'val',
	function($scope, $rootScope, $mdDialog, C, val) {

		console.info('Entidades_GridDiag_PreviewDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.C = C;
		Ctrl.val = val;

	}
]);
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
			
			var Indice = Ctrl.GridColumnasCRUD.rows.length;

			if(Llaves.length > 1){
				Indice = Rs.getIndex( Ctrl.GridColumnasCRUD.rows, Llaves[1], 'campo_id' );
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
angular.module('Entidades_Grids_TestCtrl', [])
.controller('Entidades_Grids_TestCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'grid_id', 
	function($scope, $rootScope, $mdDialog, $filter, grid_id) {

		console.info('Entidades_Grids_TestCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.inArray = Rs.inArray;

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Ctrl.Data = [];

		Ctrl.filterData = () => {
			/*Ctrl.Data = [];
			var d = angular.copy(Ctrl.Grid.data);
			angular.forEach(Ctrl.Grid.filtros, (F) => {
				if(d.length > 0 && Ctrl.inArray(F.Comparador, ['lista','radios'])){

					if(angular.isArray(F.val)){ if(F.val.length == 0) F.val = null }
					if(F.val !== null){
						d = $filter('filter')(d, function (item) {
							if(angular.isArray(F.val)){
								return F.val.includes(item[F.columna.header_index]);
							}
							return item[F.columna.header_index] === F.val;
						});
					}
				};
			});

			Ctrl.Data = d; delete d;*/
			Rs.http('api/Entidades/grids-reload-data', { Grid: Ctrl.Grid }).then((r) => {
				Ctrl.Grid.sql  = r.sql;
				Ctrl.Grid.data = r.data;
			});
		};

		Rs.http('api/Entidades/grids-get-data', { grid_id: grid_id }).then((r) => {
			Ctrl.Grid = r.Grid;
			//Ctrl.filterData();
		});

		Ctrl.getSelectedText = (Text) => {
			if(Text === null) return 'Seleccionar...';
			if(angular.isArray(Text)){
				return JoinedText = Text.join(', ');
				//var Len = JoinedText.length;
				//return ( Len < 1000 ) ? JoinedText : (Text.length + ' Seleccionados');
			}
			return Text;
		};
		
	}
]);
angular.module('Entidades_VerCamposCtrl', [])
.controller('Entidades_VerCamposCtrl', ['$scope', '$rootScope', '$injector', 'Entidad', 'Ruta', 'Llaves', 'ParentCtrl', '$mdDialog',
	function($scope, $rootScope, $injector, Entidad, Ruta, Llaves, ParentCtrl, $mdDialog) {

		console.info('Entidades_VerCamposCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Entidad = Entidad;
		Ctrl.TiposCampo = ParentCtrl.TiposCampo;

		Ctrl.CamposCRUD = $injector.get('CRUD').config({ base_url: '/api/Entidades/campos', order_by: ['Indice'] });

		Ctrl.CamposCRUD.setScope('entidad', Entidad.id);
		Ctrl.CamposCRUD.get();

		var DaRuta = angular.copy(Ruta);
		DaRuta.push(Entidad.id);

		Ctrl.Cancel = () => {
			$mdDialog.cancel();
		};

		Ctrl.addColumna = (C) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(C.id);
			ParentCtrl.addColumna(C, DaRuta, DaLlaves).then(() => {

			});
		};

		Ctrl.verCamposDiag = (entidad_id, campo_id) => {
			var DaLlaves = angular.copy(Llaves);
			DaLlaves.push(campo_id);
			$mdDialog.hide([entidad_id, DaRuta, DaLlaves]);
		};
	}
]);
angular.module('FuncionesCtrl', [])
.controller('FuncionesCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('FuncionesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.FuncionSel = null;
		Ctrl.FuncionesNav = true;
		Rs.mainTheme = 'Snow_White';
		
	}
]);
angular.module('IndicadoresCtrl', [])
.controller('IndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog', '$http',
	function($scope, $rootScope, $injector, $filter, $mdDialog, $http) {

		console.info('IndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.IndSel = null;
		Ctrl.IndicadoresNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.tiposDatoInd = ['Numero','Porcentaje','Moneda','Millones'];
		Ctrl.OpsUsar = [
			{id: 'Cump', desc: 'Cumplimiento (1/0)'},
			{id: 'PorcCump', desc: '% de Cumplimiento'},
			{id: 'Valor', desc: 'Valor del Indicador'},
		];
		Ctrl.filterIndicadores = '';

		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables', order_by: ['Variable'] });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.IndicadoresVarsCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/variables' });
		Ctrl.MetasCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores/metas' });
		Ctrl.NodosCRUD = $injector.get('CRUD').config({ base_url: '/api/Scorecards/nodos', add_append: 'refresh', order_by: ['padre_id'], query_call_arr: [ ['getFullRuta', null] ] });
		Ctrl.IndicadoresLoaded = false;

		Ctrl.getIndicadores = () => {
			//return Ctrl.addIndicador(false); //FIX
			
			Ctrl.IndicadoresCRUD.get().then(() => {
				//Ctrl.getFs();

				if(Rs.Storage.IndicadorSel){
					var indicador_sel_id = Rs.getIndex(Ctrl.IndicadoresCRUD.rows, Rs.Storage.IndicadorSel);
					Ctrl.openIndicador(Ctrl.IndicadoresCRUD.rows[indicador_sel_id]); //FIX
				};

				Ctrl.IndicadoresLoaded = true;
				//Ctrl.addToTablero(); //FIX

			});
			
		};

		Ctrl.openProceso = (P) => { Ctrl.ProcesoSelId = P.id; }

		Ctrl.getIndicadoresFiltered = () => {
			if(Ctrl.filterIndicadores.trim() == ''){
				return $filter('filter')(Ctrl.IndicadoresCRUD.rows, { proceso_id: Ctrl.ProcesoSelId }, true);
			}else{
				return $filter('filter')(Ctrl.IndicadoresCRUD.rows, Ctrl.filterIndicadores);
			}
		}

		Ctrl.getFs = () => {
			Ctrl.filterIndicadores = "";
			Ctrl.IndicadoresFS = Rs.FsGet(Ctrl.IndicadoresCRUD.rows,'Ruta','Indicador');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.IndicadoresCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getIndicadorData(Vars);
		};

		Ctrl.searchIndicador = () => {
			if(Ctrl.filterIndicadores == ""){
				Ctrl.getFs();
			}else{
				Ctrl.IndicadoresFS = Rs.FsGet($filter('filter')(Ctrl.IndicadoresCRUD.rows, Ctrl.filterIndicadores),'Ruta','Indicador',true);
			};
		};

		Ctrl.addIndicador = (route) => {
			if(route){
				var route = route.split('\\').slice(0, -1).join('\\');
				proceso_id = Rs.def(Ctrl.Procesos.filter(e => e.Ruta == route).pop().id, null);
			}else{
				proceso_id = null;
			};

			$mdDialog.show({
				controller: 'Indicadores_AddDiagCtrl',
				templateUrl: 'Frag/Indicadores.Indicadores_AddDiag',
				locals: { proceso_id: proceso_id, tiposDatoInd : Ctrl.tiposDatoInd, Procesos: Ctrl.Procesos },
				clickOutsideToClose: false, fullscreen: false, multiple: true
			}).then(newInd => {
				if(!newInd) return;
				Rs.http('api/Indicadores/add-indicador', { newInd: newInd }).then(() => {
					Rs.showToast('Indicador Agregado', 'Success');
					Ctrl.getIndicadores();
				});
			});

			console.log(proceso_id);
		};

		Ctrl.openIndicador = (V) => {
			Ctrl.IndSel = V;
			Rs.Storage.IndicadorSel = Ctrl.IndSel.id;
			Ctrl.ProcesoSelId = Ctrl.IndSel.proceso_id;
			
			Promise.all([
				Ctrl.IndicadoresVarsCRUD.setScope('indicador', Ctrl.IndSel.id).get(),
				Ctrl.MetasCRUD.setScope('indicador', Ctrl.IndSel.id).get()
			]).then(() => {
				
				//Ctrl.openComponente(Ctrl.IndicadoresVarsCRUD.rows[0]);
				//Ctrl.searchComponente();
				
			});

			

			//Rs.viewIndicadorDiag(Ctrl.IndSel.id); //FIX
		};

		Ctrl.updateIndicador = () => {
			Ctrl.IndicadoresCRUD.update(Ctrl.IndSel).then(() => {
				Rs.showToast('Indicador Actualizada', 'Success');
				Ctrl.saveVariables();
				//Ctrl.openIndicador(Ctrl.IndSel);
			});
		};



		Promise.all([
			Rs.getProcesos(Ctrl),
			Ctrl.VariablesCRUD.get(),
			Ctrl.NodosCRUD.get()
		]).then(() => {
			var ids_procesos = Ctrl.VariablesCRUD.rows.map(e => e.proceso_id).filter((v, i, a) => a.indexOf(v) === i);
			Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos.filter(p => ids_procesos.includes(p.id)),'Ruta','Proceso',false,true);
			angular.forEach(Ctrl.ProcesosFS, (P) => {
				if(P.type == 'folder'){
					P.file = Ctrl.Procesos.find(p => p.Ruta == P.route);
				}
			});

			Ctrl.NodosFS = Rs.FsGet(Ctrl.NodosCRUD.rows,'Ruta','Nodo',false,true,false);
			angular.forEach(Ctrl.NodosFS, (P) => {
				if(P.type == 'folder'){
					P.file = Ctrl.NodosCRUD.rows.find(p => { return (p.Ruta == P.route && p.tipo == 'Nodo'); });
				}
			});

			Ctrl.getIndicadores();
		});
			

		Ctrl.reloadIndicador = () => {
			Promise.all([
				Ctrl.VariablesCRUD.get(),
				Ctrl.IndicadoresCRUD.get()
			]).then( () => {
				Ctrl.openIndicador(Ctrl.IndSel);
			});
		}


		Ctrl.addVariable = () => {

			Ctrl.VariablesCRUD.dialog({
				proceso_id: Ctrl.ProcesoSelId,
				Filtros: []
			}, {
				title: 'Nueva Variable',
				only: ['Variable']
			}).then(r => {
				Ctrl.VariablesCRUD.add(r);
			});

			/*return Rs.TableDialog(Ctrl.VariablesCRUD.rows, {
				Title: 'Seleccionar Variable', Flex: 60, 
				Columns: [
					{ Nombre: 'proceso.Proceso',  Desc: 'Nodo',       numeric: false, orderBy: 'Ruta' },
					{ Nombre: 'Variable', 	 	  Desc: 'Variable',  numeric: false, orderBy: 'Variable' }
				],
				orderBy: 'Ruta', select: 'Row.id', multiple: true
			}).then(Selected => {
				if(!Selected || Selected.length == 0 ) return;
				
				Ctrl.addComponente({ Tipo: 'Variable', variable_id: Selected[0] });
				
			});*/

		};

		Ctrl.searchComponente = () => {

			var Componentes = Ctrl.VariablesCRUD.rows.map(r => {
				return {
					id: 'Var_' + r.id,
					Tipo: 'Variable', variable_id: r.id, Titulo: r.Variable,
					Ruta: '1_' + r.Ruta,
					Nodo: r.proceso.Proceso, 
				};
			});

			Componentes = Componentes.concat(Ctrl.IndicadoresCRUD.rows.map(r => {
				return {
					id: 'Ind_' + r.id,
					Tipo: 'Indicador', variable_id: r.id, Titulo: r.Indicador,
					Ruta: '2_' + r.Ruta,
					Nodo: r.proceso.Proceso, 
				};
			}));

			return Rs.TableDialog(Componentes, {
				Title: 'Seleccionar Componente', Flex: 60, 
				Columns: [
					{ Nombre: 'Tipo',  		Desc: 'Tipo',       numeric: false,  orderBy: 'Tipo' },
					{ Nombre: 'Nodo',  		Desc: 'Nodo',       numeric: false,  orderBy: 'Ruta' },
					{ Nombre: 'Titulo', 	Desc: 'Titulo',     numeric: false,  orderBy: 'Titulo' }
				],
				orderBy: 'Ruta', select: 'Row', multiple: false, pluck: false
			}).then(Selected => {
				if(!Selected || Selected.length == 0) return;
				var newComp = Selected[0];
				delete newComp.id;
				Ctrl.addComponente(newComp);
			});
		}

		Ctrl.delVariable = (Var) => {
			Ctrl.IndicadoresVarsCRUD.delete(Var).then(() => {
				angular.forEach(Ctrl.IndicadoresVarsCRUD.rows, (V,i) => {
					var Letra = String.fromCharCode(97 + i);
					if(Letra !== V.Letra){
						V.Letra = Letra;
						V.changed = true;
					};
				});
				Ctrl.saveVariables();
			});
		};

		Ctrl.saveVariables = () => {
			var Updatees = $filter('filter')(Ctrl.IndicadoresVarsCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.IndicadoresVarsCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.IndicadoresVarsCRUD.rows, IV => {
				IV.changed = false;
			});
		};

		Ctrl.addComponente = (newComp) => {
			newComp.indicador_id = Ctrl.IndSel.id;
			newComp.Letra = String.fromCharCode(97 + Ctrl.IndicadoresVarsCRUD.rows.length);

			Ctrl.IndicadoresVarsCRUD.add(newComp);
		}

		Ctrl.openComponente = (C) => {
			if(C.Tipo == 'Variable'){
				var newCtrl = Ctrl.$new();
				newCtrl.variable_id = C.variable_id;
				Rs.viewVariableEditorDiag(newCtrl);
			}

			if(C.Tipo == 'Indicador'){
				var indsel = Ctrl.IndicadoresCRUD.rows.filter(i => i.id == C.variable_id )[0];
				Ctrl.openIndicador(indsel);
			}
		}

		Ctrl.deleteComponente = (Comp) => {
			Ctrl.IndicadoresVarsCRUD.delete(Comp);
		}

		Ctrl.convertIndicador = (V) => {

			if(Number.isInteger(V)) V = Ctrl.VariablesCRUD.rows.filter(va => (va.id == V) )[0];

			Rs.Confirm({
				Titulo: '¿Convertir la variable en un indicador?',
				Detail: 'Se creará un indicador llamado: "' +V.Variable+ '", y se cambiará la asignación en cualquier indicador asociado',
			}).then(c => {
				if(!c) return;

				$http.post('/api/Variables/convertir-en-indicador', { Variable: V }).then(() => {
					Ctrl.reloadIndicador();
				});

			});

		}

		Ctrl.deleteVariable = (V) => {
			Rs.confirmDelete({
				Title: '¿Eliminar la variable: "' +V.Variable+ '"?',
				Detail: '',
			}).then(d => {
				if(!d) return;

				$http.post('/api/Variables/delete-variable', { Variable: V }).then(() => {
					Ctrl.reloadIndicador();
				});
			});
		}

		//Metas
		Ctrl.addMeta = () => {
			var PeriodoDef = Rs.AnioActual+'-01-15';
			var f = [
				{ Nombre: 'Periodo',  Type: 'period', Value: PeriodoDef, Required: true, flex: 50 }
			];
			if(Ctrl.IndSel.Sentido == 'RAN'){
				f.push({Nombre: 'Límite Inferior',  Value: '', Type: 'string', Required: true, flex: 100 });
				f.push({Nombre: 'Límite Superior',  Value: '', Type: 'string', Required: true, flex: 100 });
			}else{
				f.push({Nombre: 'Meta',  			Value: '', Type: 'string', Required: true, flex: 50 });
			};

			Rs.BasicDialog({
				Title: 'Crear Meta',
				Fields: f
			}).then(f => {
				if(!f) return;
				var m = {
					indicador_id: Ctrl.IndSel.id,
					PeriodoDesde: moment(f.Fields[0].Value).format('YYYYMM'),
					Meta: f.Fields[1].Value,
					Meta2: ((Ctrl.IndSel.Sentido == 'RAN') ? f.Fields[2].Value : null),
				};

				if($filter('filter')(Ctrl.MetasCRUD.rows, { PeriodoDesde: m.PeriodoDesde }).length > 0) return Rs.showToast('Periodo ya existe', 'Error');
				
				Ctrl.MetasCRUD.add(m);
			});
		};

		Ctrl.editMeta = (M) => {
			var only = ['PeriodoDesde', 'Meta'];
			if(Ctrl.IndSel.Sentido == 'RAN') only.push('Meta2');

			Ctrl.MetasCRUD.dialog(M, {
				title: 'Editar Meta',
				only: only
			}).then(Meta => {
				if(!Meta) return;
				if(Meta == 'DELETE') return Ctrl.MetasCRUD.delete(M);
				Ctrl.MetasCRUD.update(Meta);
			});
		}

		Ctrl.formatPeriodo = (date) => {
        	var m = moment(date);
      		return m.isValid() ? m.format('YYYYMM') : '';
        };

		Ctrl.delMeta = (Meta) => {
			Ctrl.MetasCRUD.delete(Meta);
		};

		
		//Tablero
		Ctrl.addToTablero = () => {

			return $mdDialog.show({
				controller: 'Scorecards_NodoSelectorCtrl',
				templateUrl: 'Frag/Scorecards.Scorecards_NodoSelector',
				locals: { NodosFS: angular.copy(Ctrl.NodosFS) },
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
			}).then(Sel => {
				if(!Sel) return;

				var nodos_ind = Ctrl.NodosCRUD.rows.filter(N => { return N.tipo != 'Nodo' && N.Ruta == Sel.Ruta });
				var Indice = nodos_ind.length;
				Ctrl.NodosCRUD.add({
					scorecard_id: Sel.scorecard_id, Nodo: null, padre_id: Sel.id, Indice: Indice, tipo: 'Indicador', elemento_id: Ctrl.IndSel.id, peso: 1
				});
			});
		}

		Ctrl.deleteToTablero = (N) => {
			Rs.confirmDelete({
				Title: '¿Eliminar del Nodo "' +N.Ruta+ '"?',
				Detail: 'Esta acción no se puede deshacer',
			}).then(d => {
				if(d) return Ctrl.NodosCRUD.delete(N);
			});
			
		}





	}
]);
angular.module('Indicadores_AddDiagCtrl', [])
.controller('Indicadores_AddDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$localStorage', 'tiposDatoInd', 'Procesos', 'proceso_id',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $localStorage, tiposDatoInd, Procesos, proceso_id) {

		console.info('Indicadores_AddDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.tiposDatoInd = tiposDatoInd;
		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda'];
		Ctrl.newVariable = '';

		Ctrl.newInd = {
			TipoDato: "Porcentaje",
			Decimales: 1,
			Sentido: 'ASC',
			Formula: 'a / b',
			Meta: null,
			variables: [
				{ Variable: '', TipoDato: 'Numero', Decimales: 0 },
				{ Variable: '', TipoDato: 'Numero', Decimales: 0 }
			]
		};
		

		Ctrl.searchProceso = (searchText) => {
			if(!searchText || searchText.trim() == '') return Procesos;

			return $filter('filter')(Procesos, searchText);

		}

		Ctrl.getLetra = (k) => { return String.fromCharCode(97 + k); }

		Ctrl.removeVar = (k) => { Ctrl.newInd.variables.splice(k,1); }

		Ctrl.addVariable = () => {
			if(Ctrl.newVariable.trim() == '') return;
			
			Ctrl.newInd.variables.push({
				Variable: Ctrl.newVariable, TipoDato: 'Numero', Decimales: 0 
			});

			Ctrl.newVariable = '';

		}

		if(proceso_id){
			Ctrl.newVariable.proceso = $filter('filter')(Procesos, { id: proceso_id });
		}


		Ctrl.submitInd = () => {
			if(!Ctrl.newInd.proceso) return Rs.showToast('Falta el proceso', 'Error', 1000);
			Ctrl.newInd.proceso_id = Ctrl.newInd.proceso.id;
			$mdDialog.hide(Ctrl.newInd);
		}

	}
]);

angular.module('Indicadores_IndicadorDiagCtrl', [])
.controller('Indicadores_IndicadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'indicador_id', '$timeout', '$injector', '$mdPanel',
	function($scope, $rootScope, $mdDialog, $filter, indicador_id, $timeout, $injector, $mdPanel) {

		console.info('Indicadores_IndicadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { d3.selectAll('.nvtooltip').style('opacity', 0); $mdDialog.cancel(); }

        Ctrl.SidenavIcons = [
            ['fa-comment',      'Mejoramiento',     false],
            ['fa-list',         'Desagregar Datos', false],
            ['fa-info-circle',  'Ficha Técnica',    false],
        ];
        Ctrl.openSidenavElm = (S) => {
            Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
            $timeout(() => {
                Ctrl.updateChart();
            }, 300);
        };

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
        Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.anioAdd = (num) => { Ctrl.Anio += num; Ctrl.getIndicadores(); };
		Ctrl.Sentidos = Rs.Sentidos;
        Ctrl.Usuario = Rs.Usuario;

        Ctrl.modoComparativo = false;

		Ctrl.getIndicadores = () => {

			Rs.http('api/Indicadores/get', { id: indicador_id, Anio: Ctrl.Anio, modoComparativo: Ctrl.modoComparativo }, Ctrl, 'Ind').then(() => {

				angular.forEach(Ctrl.Ind.valores, (m,k) => {
					var i = parseInt(m.mes);
					Ctrl.graphData[0].values[i-1] = { x: i, y: m.Valor, 	  val: m.val,         series: 0, key: 'Valor', color: m.color };
                    Ctrl.graphData[1].values[i-1] = { x: i, y: m.meta_Valor,  val: m.meta_val,    series: 1, key: 'Meta'     };
                    Ctrl.graphData[2].values[i-1] = { x: i, y: m.meta2_Valor, val: m.meta_val,    series: 2, key: 'Meta2'    };
					Ctrl.graphData[3].values[i-1] = { x: i, y: m.anioAnt,     val: m.anioAnt_val, series: 3, key: 'AnioAnt', color: m.anioAnt_color  };
				});

                Ctrl.updateChart();

                Ctrl.Desagregacion = null;
                Ctrl.getComentarios();
                //Ctrl.getDesagregatedData();

			});

		};

        Ctrl.updateChart = () => {
            Ctrl.graphData[2].disabled = !(Ctrl.Ind.Sentido == 'RAN');
            Ctrl.graphData[3].disabled = !Ctrl.modoComparativo;
            d3.selectAll('.nvtooltip').style('opacity', 0);
            Ctrl.graphApi.update();
        }
		







 		Ctrl.grapOptions = {
            chart: {
                type: 'multiChart',
                margin: {
                	top:10, right:20, bottom:0, left:100
                },
                height: 150,
                y: function(d,i) { return d.y; },
                x: function(d,i) { return d.x; },
                showLegend: false,
                xAxis: {
                	showMaxMin: false,
                    ticks: 0,
                    tickFormat: function(d){
                        return Rs.Meses[d-1][1];
                    },
                },
                yAxis1: {
                    tickFormat: function(d){
                        return Rs.formatVal(d,Ctrl.Ind.TipoDato,Ctrl.Ind.Decimales);
                    },
                },
                bars1: {
                },
                lines1: {
                	padData: true,
                },
                padData: true,
                //yDomain1: [0,0.1],
                useInteractiveGuideline: true,
                interactiveLayer:{
                	showGuideLine: false,
                    tooltip: {
                        contentGenerator: (obj) => {
                            var Periodo = `${Rs.Meses[obj.index][1]} ${Ctrl.Anio}`;
                            var Resultado = obj.series[0].data.val;
                            var Meta      = obj.series[1].data.val;
                            var Color     = obj.series[0].data.color;
                            return `<table><thead><tr><td class=x-value colspan=3><div class='md-title'>${Periodo}</div></td></tr></thead><tbody>
                            <tr style='color:${Color}'><td class=key>Resultado</td><td class='value'>${Resultado}</td></tr>
                            <tr><td class=key>Meta:</td><td class=value>${Meta}</td></tr>
                            </tbody></table>`;
                        }
                    }
                    
                }
            }
        };

        Ctrl.graphData = [
        	{ key: 'Valor',    yAxis: 1, type: 'bar',  values: [] },
            { key: 'Meta',     yAxis: 1, type: 'line', values: [], classed: 'dashed', color: 'white' },
            { key: 'Meta2',    yAxis: 1, type: 'line', values: [], classed: 'dashed', color: 'white' },
        	{ key: 'AnioAnt',  yAxis: 1, type: 'bar',  values: [] },
        ];

        Ctrl.getIndicadores();

        Ctrl.viewCompDiag = (comp) => {
            if(comp.Tipo == 'Variable')  return Rs.viewVariableDiag(comp.variable_id);
            if(comp.Tipo == 'Indicador') return Rs.viewIndicadorDiag(comp.variable_id);
        };

        //Comments
        Ctrl.ComentariosCRUD = $injector.get('CRUD').config({ 
            base_url: '/api/Main/comentarios', 
            query_with: [ 'autor' ], add_append: 'refresh', 
            order_by: ['-created_at']
        });
        var ComentariosLoaded = false;
        Ctrl.getComentarios = () => {
            Ctrl.ComentariosCRUD.setScope('Entidad', ['Indicador', indicador_id]).get().then(() => {
                ComentariosLoaded = true;
            });
        };

        Ctrl.addComment = () => {

            var Periodos = [
                moment().add(-1, 'month').format('YYYYMM'),
                moment().format('YYYYMM')
            ];

            Rs.BasicDialog({
                Theme: 'Black', Title: 'Agregar Comentario',
                Fields: [
                    { Nombre: 'Periodo',     Value: Periodos[0], Required: true, Type: 'simplelist',  List: Periodos },
                    { Nombre: 'Comentario',  Value: '',          Required: true, Type: 'textarea',    opts: { rows: 4 } }
                ],
                Confirm: { Text: 'Comentar' },
            }).then(r => {
                if(!r) return;
                var f = Rs.prepFields(r.Fields);

                Ctrl.ComentariosCRUD.add({
                    Entidad: 'Indicador', Entidad_id: indicador_id, Grupo: 'Comentario',
                    usuario_id: Rs.Usuario.id, Comentario: f.Comentario, Op1: f.Periodo
                });
            });
        };

        Ctrl.addAccion = () => {
            var Periodos = [
                moment().add(-1, 'month').format('YYYYMM')
            ];

            var Tipos = ['Preventiva', 'Correctiva', 'De Mejora'];

            Rs.BasicDialog({
                Theme: 'Black', Title: 'Agregar Acción',
                Fields: [
                    { Nombre: 'Periodo',     flex: 50, Value: Periodos[0],  Required: true, Type: 'simplelist',  List: Periodos },
                    { Nombre: 'Tipo',        flex: 50, Value: 'Correctiva', Required: true, Type: 'simplelist',  List: Tipos },
                    { Nombre: 'Link Isolución',        Value: '',           Required: true }
                ],
                Confirm: { Text: 'Agregar' },
            }).then(r => {
                if(!r) return;
                var f = Rs.prepFields(r.Fields);

                Ctrl.ComentariosCRUD.add({
                    Entidad: 'Indicador', Entidad_id: indicador_id, Grupo: 'Accion',
                    usuario_id: Rs.Usuario.id, Comentario: 'Se registró una: Acción '+f.Tipo, Op1: f.Periodo, Op2: f.Tipo, Op4: f['Link Isolución']
                });
            });
        };

        Ctrl.seeExternal = (Link) => {
            window.open(Link,'popup','width=1220,height=700');
        }

        //Ctrl.toogleSidenav(); //FIX

        //Desagregacion
        Ctrl.viewDesagregacionVal = 'IndVal';
        
        Ctrl.addDesagregado = () => {
            Ctrl.Ind.desagregados.push(angular.copy(Ctrl.newChip));
            var index = Rs.getIndex(Ctrl.Ind.desagregables, Ctrl.newChip.id);
            Ctrl.Ind.desagregables.splice(index,1);
            Ctrl.newChip = null;
        };

        Ctrl.removedDesagregado = ($chip) => {
            Ctrl.Ind.desagregables.push($chip);
        };

        Ctrl.getDesagregatedData = (ev) => {
            if(ev) ev.stopPropagation();
           
            Rs.http('api/Indicadores/get-desagregacion', { Indicador: Ctrl.Ind, Anio: Ctrl.Anio, desag_campos: Ctrl.Ind.desagregados }, Ctrl, 'Desagregacion');
        };



        //Menu Valores
        Ctrl.openMenuValores = (ev, Comp, M) => {
            if(Comp.Tipo == 'Indicador') return Rs.viewIndicadorDiag(Comp.variable_id);
            var Val = Comp.valores[Ctrl.Anio+M[0]];
            Rs.viewVariableMenu(ev, Comp.variable, Ctrl.Anio+M[0], Val, Ctrl.getIndicadores);
        }



	}
]);

function Indicadores_IndicadorDiag_ValorMenuCtrl(mdPanelRef, Periodo, Variable, Val, Fn, $rootScope, $http){

	var Ctrl = this;
	var Rs = $rootScope;

	Ctrl.viewVariableDiag = (variable_id) => {
		mdPanelRef.close();
		Rs.viewVariableDiag(variable_id);
	};

	Ctrl.Periodo = Periodo;
	Ctrl.PeriodoDesc = Rs.Meses[parseInt(Periodo.substr(-2)) - 1][1] +' '+ parseInt(Periodo/100);
	Ctrl.Variable = Variable;


	Ctrl.Val = Val;
	Ctrl.Valor = Val.Valor;
	Ctrl.changed = false;
	Ctrl.editable = false;

	if(Rs.Usuario.isGod) Ctrl.editable = true;

	if(Variable.Tipo == 'Manual' && Periodo >= Rs.PeriodoActual) Ctrl.editable = true;

	Ctrl.updateValor = () => {

		var VariableValor = {
			variable_id: Variable.id,
			Periodo: Periodo,
			Valor: Ctrl.Valor
		};

		$http.post('api/Variables/update-valor', VariableValor).then(() => {
			mdPanelRef.close();
			if(Fn) Fn();
		});
	};

}
angular.module('IngresarDatosCtrl', [])
.controller('IngresarDatosCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('IngresarDatosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';
		

		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterVariablesText = '';
		Ctrl.tipoVariableSel = 'Manual';
		Ctrl.TiposVariables = {
			'Manual': { Nombre: 'Manuales' },
			'Valor Fijo': { Nombre: 'Valores Fijos' },
			'Calculado de Entidad': { Nombre: 'Automáticas' },
		};
		Ctrl.Loading = true;

		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getVariables(); };
		var Variables = [];

		Ctrl.getVariables = () => {
			Ctrl.Loading = true;
			Ctrl.hasEdited = false;
			Rs.http('api/Variables/get-usuario', { Usuario: Rs.Usuario, Anio: Ctrl.Anio }).then((r) => {
				Variables = r;

				var PeriodoAct = (Rs.AnioActual*100) + Rs.MesActual;
				var PeriodoAnt = parseInt(moment().add(-1, 'month').format('YYYYMM'));

				Variables.forEach(V => {
					//console.log(V.valores);

					Rs.Meses.forEach(M => {
						var Periodo = Ctrl.Anio + M[0];
						if(!V.valores[Periodo]){
							V.valores[Periodo] = { 'val': null, 'Valor': null, 'new_Valor': null, 'edited': false, 'readonly': false };
						}else{
							V.valores[Periodo]['new_Valor'] = V.valores[Periodo]['Valor'];
							V.valores[Periodo]['edited'] = false;
							V.valores[Periodo]['readonly'] = (Periodo < PeriodoAnt);
						};

						if(V.Tipo == 'Manual') V.valores[Periodo]['readonly'] = false;
						
						//if(Periodo >= PeriodoAct) V.valores[Periodo]['readonly'] = true;
					});
				});

				Ctrl.filterVariables();
			});
		};

		Ctrl.getVariables();

		Ctrl.filteredVariables = [];
		Ctrl.filterVariables = () => {
			var Vars = angular.copy(Variables);
			
			if(Ctrl.tipoVariableSel){
				Vars = $filter('filter')(Vars, { Tipo: Ctrl.tipoVariableSel }, true);
			}

			if(Ctrl.ProcesoSel){ 
				Vars = $filter('filter')(Vars, { proceso_id: Ctrl.ProcesoSel }, true);
			}

			if(Ctrl.filterVariablesText.trim() !== ''){
				Vars = $filter('filter')(Vars, Ctrl.filterVariablesText);
			}

			Ctrl.filteredVariables = Vars;
			Ctrl.Loading = false;
		}

		Ctrl.hasEdited = false;
		Ctrl.markChanged = (VP) => {
			VP.edited = true;
			Ctrl.hasEdited = true;
		}

		Ctrl.saveVariables = () => {
			var VariablesValores = [];

			Ctrl.filteredVariables.forEach(V => {

				Rs.Meses.forEach(M => {
					var Periodo = Ctrl.Anio + M[0];
					var VP = V.valores[Periodo];
					if(VP.edited){
						VariablesValores.push({
							variable_id: V.id,
							Periodo: parseInt(Periodo),
							Valor: VP.new_Valor,
							usuario_id: Rs.Usuario.id
						});
					}
				});

			});

			Rs.http('api/Variables/store-all', { VariablesValores: VariablesValores }).then(() => {
				Ctrl.getVariables();
			});
		};

	}
]);
angular.module('Integraciones_EnterpriseCtrl', [])
.controller('Integraciones_EnterpriseCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_EnterpriseCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/enterprise',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.regs + " Registros Cargados";
	        	Ctrl.Status = 'Ended';
	        }).catch((r) => {
	        	Ctrl.EndedMsg = 'Se presentó un error, por favor intente más tarde \n o notifique al área encargada.';
	        	Ctrl.Status = 'Error';
	        });
		}

		Ctrl.ReloadStatus = () => {
			Ctrl.Status = 'Iddle';
			Ctrl.EndedMsg = null;
		}
		
	}
]);
angular.module('Integraciones_IkonoCtrl', [])
.controller('Integraciones_IkonoCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_IkonoCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/ikono',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.regs + " Registros Cargados";
	        	Ctrl.Status = 'Ended';
	        }).catch((r) => {
	        	Ctrl.EndedMsg = 'Se presentó un error, por favor intente más tarde \n o notifique al área encargada.';
	        	Ctrl.Status = 'Error';
	        });
		}

		Ctrl.ReloadStatus = () => {
			Ctrl.Status = 'Iddle';
			Ctrl.EndedMsg = null;
		}
		
	}
]);
angular.module('Integraciones_RUAFCtrl', [])
.controller('Integraciones_RUAFCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_RUAFCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/ruaf',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.regs + " Registros Cargados";
	        	Ctrl.Status = 'Ended';
	        }).catch((r) => {
	        	Ctrl.EndedMsg = 'Se presentó un error, por favor intente más tarde \n o notifique al área encargada.';
	        	Ctrl.Status = 'Error';
	        });
		}

		Ctrl.ReloadStatus = () => {
			Ctrl.Status = 'Iddle';
			Ctrl.EndedMsg = null;
		}
		
	}
]);
angular.module('Integraciones_SolgeinCtrl', [])
.controller('Integraciones_SolgeinCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_SolgeinCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/solgein',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.variables + " Variables identificadas \n "+ r.data.variables_cargadas +" Valores cargados \n " + r.data.metas_cargadas + " Metas Cargadas";
	        	Ctrl.Status = 'Ended';
	        }).catch((r) => {
	        	Ctrl.EndedMsg = 'Se presentó un error, por favor intente más tarde \n o notifique al área encargada.';
	        	Ctrl.Status = 'Error';
	        });
		}

		Ctrl.ReloadStatus = () => {
			Ctrl.Status = 'Iddle';
			Ctrl.EndedMsg = null;
		}
		
	}
]);
angular.module('Integraciones_SOMACtrl', [])
.controller('Integraciones_SOMACtrl', ['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http) {

		console.info('Integraciones_SOMACtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		var Inicio = new Date(); Inicio.setDate( Inicio.getDate() - 3 );
		var Hoy = new Date();
		console.log(Inicio);

		Ctrl.filters = {
			Tipo: 'GCFR',
			Desde: Inicio
		};

		Ctrl.downloadFile = () => {

			$http.post('/api/Integraciones/soma', Ctrl.filters, { responseType: 'arraybuffer' }).then(function(r) {
        		var blob = new Blob([r.data], { type: "text/plain" });
		        var filename = moment(Ctrl.filters.Desde).format('YYYYMMDD') + '_' + Ctrl.filters.Tipo + '.txt';
		        //console.log(r.data, filename);
		        saveAs(blob, filename);
        	});
		};

		Ctrl.sendSoma = () => {

			$http.post('/api/Integraciones/soma-send', Ctrl.filters).then(function(r) {
        		console.log(r);
        	});

		}

		//Ctrl.downloadFile();
	}
]);
angular.module('MiProcesoCtrl', [])
.controller('MiProcesoCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdDialog) {

		console.info('MiProcesoCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';


		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterIndicadoresText = '';
		Ctrl.Loading = true;
		Ctrl.SelectedTab = 0;
		Ctrl.Sentidos = Rs.Sentidos;
		

		var Indicadores = [];
		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getIndicadores(); };

		Ctrl.cambiarFondo = () => {
			var Config = {
				CanvasWidth:  600,
				CanvasHeight: 400,
				CropWidth:    600,
				CropHeight:   160,
				Class: 'mw600',
				UploadPath: 'img/procesos_bgs/'+Ctrl.ProcesoSel.id+'.jpg',
			};

			$mdDialog.show({
				templateUrl: 'templates/dialogs/image-editor.html',
				controller: 'ImageEditor_DialogCtrl',
				locals: { Config: Config }
			}).then((r) => {
				Ctrl.getProceso(Ctrl.ProcesoSel.id);
			});
		}

		Ctrl.SelectedTab = 0;
		Ctrl.SubSecciones = [
			['General'		,'General' ],
			['Equipo'		,'Equipo' ],
			['Indicadores'	,'Indicadores' ],
			//Logros:  ['Logros'],
		];

		Ctrl.goToTab = (id) => {
			var tab_index = Rs.getIndex(Ctrl.SubSecciones, id, 0);
			//Object.keys(Ctrl.SubSecciones).indexOf(id);
			Ctrl.SelectedTab = tab_index;
		}

		Ctrl.getProceso = (proceso_id) => {
			Rs.http('api/Procesos/get-proceso', { proceso_id: proceso_id, Anio: Ctrl.Anio }, Ctrl, 'ProcesoSel').then(r => {

			});
		}


		//Introduccion
		Ctrl.addedIntro = false;
		Ctrl.markIntro = () => { Ctrl.addedIntro = true; }
		Ctrl.saveIntro = () => {
			Rs.http('api/Procesos/update', { Proceso: Ctrl.ProcesoSel  }).then(r => {
				Ctrl.addedIntro = false;
			});
			
		}

		Ctrl.viewTableroDiag = (T) => {
			//Rs.viewScorecardDiag(T.id);
			$mdDialog.show({
				controller: 'Scorecards_ScorecardDiagCtrl',
				templateUrl: '/Frag/Scorecards.ScorecardDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				onComplete: (scope, element) => {
					scope.getScorecard(T.id, { proceso_id: Ctrl.ProcesoSel.id });
				}
			});
		}


		Ctrl.verMapaNodos = () => {
			$mdDialog.show({
				controller: 'Procesos_MapaNodosDiagCtrl',
				templateUrl: '/Frag/Procesos.Procesos_MapaNodosDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				locals: { ProcesosFS: Ctrl.ProcesosFS }
			}).then(P => {
				if(!P) return;
				Ctrl.getProceso(P.id)
			});
		}



		Promise.all([
			Rs.getProcesos(Ctrl)
		]).then(() => {

			Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos,'Ruta','Proceso',false,true);

			//console.table(Ctrl.ProcesosFS);

			angular.forEach(Ctrl.ProcesosFS, (P) => {
				if(P.type == 'folder'){
					P.file = Ctrl.Procesos.find(p => (p.Ruta == P.route && p.Proceso == P.name) );
				}
			});

			var first_proceso = Rs.Usuario.Procesos.find((P) => {
				return (P.Tipo !== 'Utilitario');
			});
			//var first_proceso = {id:50};
			if(first_proceso) Ctrl.getProceso(first_proceso.id);
			//Ctrl.verMapaNodos();
		});

		
		
		


	}
]);
angular.module('MisIndicadoresCtrl', [])
.controller('MisIndicadoresCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('MisIndicadoresCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Black';

		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterIndicadoresText = '';
		Ctrl.Loading = true;

		var Indicadores = [];
		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getIndicadores(); };

		Ctrl.getIndicadores = () => {
			Ctrl.Loading = true;
			Ctrl.hasEdited = false;
			Rs.http('api/Indicadores/get-usuario', { Usuario: Rs.Usuario, Anio: Ctrl.Anio }).then((r) => {
				Indicadores = r;
				Ctrl.filterIndicadores();
			});
		};

		Ctrl.getIndicadores();

		Ctrl.filteredIndicadores = [];
		Ctrl.filterIndicadores = () => {
			var Vars = angular.copy(Indicadores);
			
			if(Ctrl.ProcesoSel){ 
				Vars = $filter('filter')(Vars, { proceso_id: Ctrl.ProcesoSel }, true);
			}

			if(Ctrl.filterIndicadoresText.trim() !== ''){
				Vars = $filter('filter')(Vars, Ctrl.filterIndicadoresText);
			}

			Ctrl.filteredIndicadores = Vars;
			Ctrl.Loading = false;
		}


		
	}
]);
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
angular.module('Procesos_MapaNodosDiagCtrl', [])
.controller('Procesos_MapaNodosDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$injector', '$filter', 'ProcesosFS',
	function($scope, $rootScope, $mdDialog, $injector, $filter, ProcesosFS) {

		console.info('Procesos_MapaNodosDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.FsOpenFolder = Rs.FsOpenFolder;
		Ctrl.ProcesosFS = ProcesosFS;
		Ctrl.Cancel = $mdDialog.cancel;

		Ctrl.openProceso = (P) => {
			$mdDialog.hide(P);
		}
	}
]);
angular.module('ScorecardsCtrl', [])
.controller('ScorecardsCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$timeout', '$mdDialog', 
	function($scope, $rootScope, $injector, $filter, $timeout, $mdDialog) {

		console.info('ScorecardsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.ScoSel = null;
		Ctrl.ScorecardsNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.ScorecardsCRUD  = $injector.get('CRUD').config({ base_url: '/api/Scorecards' });
		Ctrl.CardsCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Scorecards/cards' });
		Ctrl.NodosCRUD 		 = $injector.get('CRUD').config({ base_url: '/api/Scorecards/nodos', query_call_arr: [['getElementos',null],['getRutas',null]] });
		Ctrl.IndicadoresCRUD = $injector.get('CRUD').config({ base_url: '/api/Indicadores' });
		Ctrl.VariablesCRUD 	 = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.NodosSelected = [];

		Ctrl.getScorecards = () => {
			Ctrl.ScorecardsCRUD.get().then(() => {
				
				if(Rs.Storage.ScorecardSel){
					var scorecard_sel_id = Rs.getIndex(Ctrl.ScorecardsCRUD.rows, Rs.Storage.ScorecardSel);
					Ctrl.openScorecard(Ctrl.ScorecardsCRUD.rows[scorecard_sel_id]);
				};
				//Ctrl.getFs();
			});
		};

		Ctrl.getFs = () => {
			Ctrl.filterScorecards = "";
			Ctrl.NodosFS = Rs.FsGet(Ctrl.NodosCRUD.rows,'Ruta','Nodo',false,true,false);
			angular.forEach(Ctrl.NodosFS, (F) => {
				if(F.type == 'folder'){
					F.file = Ctrl.NodosCRUD.rows.filter(N => { return ( N.tipo == 'Nodo' && N.Ruta == F.route ) })[0];
				};
			});
		};

		Ctrl.addNodo = (NodoPadre) => {
			var Nodos = Ctrl.NodosCRUD.rows.filter(N => { return N.tipo == 'Nodo' });
			Rs.BasicDialog({
				Title: 'Crear Nodo', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '',   			Required: true, flex: 60 },
					{ Nombre: 'Peso',    Value: 1,    			Required: true, flex: 10, Type: 'number' },
					{ Nombre: 'Padre',   Value: NodoPadre.id, 	Required: true, flex: 30, Type: 'list', List: Nodos, Item_Val: 'id', Item_Show: 'Nodo' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.NodosCRUD.add({
					scorecard_id: Ctrl.ScoSel.id, Nodo: f.Nombre, padre_id: f.Padre, Indice: Ctrl.NodoSel.subnodos.length, tipo: 'Nodo', peso: f.Peso 
				}).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
				});
			});
		};

		Ctrl.deleteScorecardNodo = () => {
			if(Ctrl.NodoSel.indicadores.length > 0 || Ctrl.NodoSel.subnodos.length > 0 ) return Rs.showToast('Solo se pueden eliminar nodos vacíos', 'Error');
			Ctrl.NodosCRUD.delete(Ctrl.NodoSel).then(() => {
				Ctrl.openScorecard(Ctrl.ScoSel);
			});
		}

		Ctrl.openNodo = (Nodo) => {
			
			Ctrl.NodoSel = Nodo;
			Ctrl.NodoSel.indicadores = $filter('orderBy')(Ctrl.NodosCRUD.rows.filter(N => { return (N.tipo !== 'Nodo' && N.padre_id == Nodo.id) }), 'Indice');
			Ctrl.NodoSel.subnodos    = Ctrl.NodosCRUD.rows.filter(N => { return (N.tipo ==  'Nodo'      && N.padre_id == Nodo.id) });
			Ctrl.NodosSelected = [];
			//Rs.viewScorecardDiag(Ctrl.ScoSel.id); //FIX

		};

		Ctrl.addIndicador = () => {

			var indicadores_ids = Ctrl.NodosCRUD.rows.filter(n => n.tipo == 'Indicador').map(n => n.elemento_id);
			var Indicadores = Ctrl.IndicadoresCRUD.rows.filter(i => !Rs.inArray(i.id, indicadores_ids) );

			//return console.log(indicadores_ids);

			Rs.TableDialog(Indicadores, {
				Title: 'Seleccionar Indicadores', Flex: 60, 
				Columns: [
					{ Nombre: 'proceso.Proceso',  Desc: 'Nodo',       numeric: false, orderBy: 'Ruta' },
					{ Nombre: 'Indicador', 	 	  Desc: 'Indicador',  numeric: false, orderBy: 'Indicador' },
					{ Nombre: 'proceso.Tipo',     Desc: 'Tipo Nodo',  numeric: false, orderBy: false },
					{ Nombre: 'updated_at',       Desc: 'Actualizado',  numeric: false, orderBy: 'updated_at' },
				],
				orderBy: 'Ruta', select: 'Row.id'
			}).then(Selected => {
				if(!Selected || Selected.length == 0 ) return;
				var Indice = Ctrl.NodoSel.indicadores.length;
				Selected = Selected.map(indicador_id => {
					return {
						scorecard_id: Ctrl.ScoSel.id, 
						Nodo: null, padre_id: Ctrl.NodoSel.id, 
						Indice: Indice++, tipo: 'Indicador', elemento_id: indicador_id, peso: 1 
					}
				});

				Ctrl.NodosCRUD.addMultiple(Selected).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
				});
			});
		};

		Ctrl.addVariable = () => {
			Rs.BasicDialog({
				Title: 'Agregar Variable', Flex: 50,
				Fields: [
					{ Nombre: 'Variable', Value:null, Required: true, flex: 90, Type: 'autocomplete', 
					opts: {
						itemsFn: (text) => { return $filter('filter')(Ctrl.VariablesCRUD.rows, { Variable: text }); },
						itemDisplay: (item) => { return item.Variable }, itemText: 'Variable',
						minLength: 0, delay: 300, itemVal: false
					}},
					{ Nombre: 'Peso',    Value: 1,    			Required: true, flex: 10, Type: 'number' }
				],
			}).then(r => {
				if(!r) return;

				var f = Rs.prepFields(r.Fields);
				var Indice = Ctrl.NodoSel.indicadores.length;
				Ctrl.NodosCRUD.add({
					scorecard_id: Ctrl.ScoSel.id, Nodo: null, padre_id: Ctrl.NodoSel.id, Indice: Indice, tipo: 'Variable', elemento_id: f.Variable.id, peso: f.Peso 
				}).then(() => {
					Ctrl.openNodo(Ctrl.NodoSel);
					Ctrl.getFs();
				});
			});
		};

		Ctrl.searchScorecard = () => {
			if(Ctrl.filterScorecards == ""){
				Ctrl.getFs();
			}else{
				Ctrl.ScorecardsFS = Rs.FsGet($filter('filter')(Ctrl.ScorecardsCRUD.rows, Ctrl.filterScorecards),'Ruta','Scorecard',true);
			};
		};

		Ctrl.addScorecard = () => {
			Rs.BasicDialog({
				Title: 'Crear Scorecard', Flex: 50,
				Fields: [
					{ Nombre: 'Titulo',  		Value: '', Required: true },
					{ Nombre: 'Primer Nodo',    Value: '', Required: true },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				console.log(f);
				Ctrl.ScorecardsCRUD.add({ Titulo: f.Titulo, Secciones: [] }).then(r => {
					Ctrl.NodosCRUD.add({ scorecard_id: r.id, Nodo: r.Titulo, tipo: 'Nodo' }).then(n => {
						Ctrl.NodosCRUD.add({ scorecard_id: r.id, Nodo: f['Primer Nodo'], tipo: 'Nodo', padre_id: n.id }).then(() => {
							Rs.showToast('Scorecard Creado');
						});
					});
				});
			});
		};

		Ctrl.openScorecard = (V, Nodo) => {
			Ctrl.ScoSel = V;
			Rs.Storage.ScorecardSel = V.id;
			Ctrl.NodoSel = Rs.def(Nodo, null);
			Ctrl.NodosCRUD.setScope('scorecard', Ctrl.ScoSel.id).get().then(() => {
				if(!Nodo) Nodo = Ctrl.NodosCRUD.rows[0];
				Ctrl.openNodo(Nodo);
				Ctrl.getFs();
			});
		};

		Ctrl.updateScorecard = () => {

			if(Ctrl.ScoSel.changed){  
				Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel); 
				Rs.showToast('Scorecard Actualizado', 'Success');
				Ctrl.ScoSel.changed = false;
			}

			if(Ctrl.NodoSel.changed){ 
				Ctrl.NodosCRUD.update(Ctrl.NodoSel).then(() => { Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel); }); 
				Rs.showToast('Nodo Actualizado', 'Success');
				Ctrl.NodoSel.changed = false; 
			}

			var IndicadoresChanged = Ctrl.NodoSel.indicadores.filter(i => { return (i.changed == true); });
			var SubnodosChanged    = Ctrl.NodoSel.subnodos.filter(i => {    return (i.changed == true); });
			var Changed = IndicadoresChanged.concat(SubnodosChanged);
			if(Changed.length > 0){
				Ctrl.NodosCRUD.updateMultiple(Changed).then(() => {
					Rs.showToast('Indicadores Actualizados', 'Success');
					angular.forEach(Ctrl.NodoSel.indicadores, I => {
						I.changed = false;
					});

					angular.forEach(Ctrl.NodoSel.subnodos, I => {
						I.changed = false;
					});
				});
			}

			
			/*Ctrl.ScorecardsCRUD.update(Ctrl.ScoSel).then(() => {
				Rs.showToast('Scorecard Actualizada', 'Success');
				Ctrl.saveCards();
			});*/
		};

		Ctrl.delIndicador = (I) => {
			Ctrl.NodosCRUD.delete(I).then(() => {
				Ctrl.openNodo(Ctrl.NodoSel);
			});

		}

		//Nuevo multiple delete
		Ctrl.deleteNodosInd = () => {
			if(Ctrl.NodosSelected.length == 0) return;
			return Rs.confirmDelete({
				Title: '¿Eliminar estos '+Ctrl.NodosSelected.length+' Indicadores/Variables ?',
			}).then(d => {
				if(!d) return;
				Ctrl.NodosCRUD.ops.selected = angular.copy(Ctrl.NodosSelected);
				Ctrl.NodosCRUD.deleteMultiple().then(() => {
					return Ctrl.reindexarNodo(Ctrl.NodoSel);
				});
			})
		}

		Ctrl.reindexarNodo = (Nodo) => {
			return Rs.http('api/Scorecards/reindexar', { Nodo: Nodo }).then(() => {
				Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
			});
		}

		Ctrl.moveNodosInd = () => {
			return $mdDialog.show({
				controller: 'Scorecards_NodoSelectorCtrl',
				templateUrl: 'Frag/Scorecards.Scorecards_NodoSelector',
				locals: { NodosFS: angular.copy(Ctrl.NodosFS) },
				clickOutsideToClose: true,
				fullscreen: true,
				multiple: true,
			}).then(N => {
				if(!N) return;
				Rs.http('api/Scorecards/move-inds', { Inds: Ctrl.NodosSelected, nodo_destino_id: N.id }).then(() => {
					Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				});

				//console.log(N);
			});
		}

		Ctrl.eraseCacheNodosInd = () => {
			Rs.http('api/Scorecards/erase-cache', { Inds: Ctrl.NodosSelected }).then(() => {
				//Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				Rs.showToast('Caché Borrada', 'Success');
			});
		}


		//Cards
		Ctrl.addCard = () => {
			Rs.BasicDialog({
				Title: 'Agregar Tarjeta', Flex: 50,
				Fields: [
					{ Nombre: 'Indicador', Value: null, Type: 'list', List: Ctrl.IndicadoresCRUD.rows, Item_Val: 'id', Item_Show: 'Indicador' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				var Indice = Ctrl.CardsCRUD.rows.length;
				var seccion_id = (Indice == 0) ? null : Ctrl.CardsCRUD.rows[Indice-1].seccion_id;
				Ctrl.CardsCRUD.add({
					Indice: Indice,
					scorecard_id: Ctrl.ScoSel.id,
					seccion_id: seccion_id,
					tipo: 'Indicador', elemento_id: f.Indicador
				});
			});
		};

		Ctrl.saveCards = () => {
			var Updatees = $filter('filter')(Ctrl.CardsCRUD.rows, { changed: true });
			if(Updatees.length == 0) return;
			Ctrl.CardsCRUD.updateMultiple(Updatees);
			angular.forEach(Ctrl.CardsCRUD.rows, C => {
				C.changed = false;
			});
		};

		Ctrl.delCard = (C) => {
			Ctrl.CardsCRUD.delete(C);
		};


		Ctrl.getProcesos = () => {
			return Rs.http('api/Procesos', {}, Ctrl, 'Procesos');
		};


		Promise.all([Ctrl.IndicadoresCRUD.get(), Ctrl.VariablesCRUD.get(), Ctrl.getProcesos()]).then(values => { 
			Ctrl.getScorecards();
		});
		

		//Reordenar Indicadores
		Ctrl.dragListener2 = {
			accept: function (sourceItemHandleScope, destSortableScope) { return true; },
			orderChanged: () => {
				angular.forEach(Ctrl.NodoSel.indicadores, (C,index) => {
					if(C.Indice !== index){
						C.Indice = index;
						C.changed = true;
					};
				});
			}
		};



	}
]);
angular.module('Scorecards_NodoSelectorCtrl', [])
.controller('Scorecards_NodoSelectorCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'NodosFS', 
	function($scope, $rootScope, $mdDialog, $filter, NodosFS) {

		console.info('Scorecards_NodoSelectorCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.NodosFS = NodosFS;
		Ctrl.FsOpenFolder = Rs.FsOpenFolder;

		Ctrl.selectNodo = (N) => { Ctrl.NodoSel = N; }

		Ctrl.submitNodo = () => {
			$mdDialog.hide(Ctrl.NodoSel)
		}

	}
]);

angular.module('Scorecards_ScorecardDiagCtrl', [])
.controller('Scorecards_ScorecardDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$localStorage',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $localStorage) {

		console.info('Scorecards_ScorecardDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
        Ctrl.viewVariableDiag = Rs.viewVariableDiag;
        Ctrl.viewIndicadorDiag = Rs.viewIndicadorDiag;
        Ctrl.Sentidos = Rs.Sentidos;
        Ctrl.periodDateLocale = Rs.periodDateLocale;
        Ctrl.Loading = true;
        Ctrl.Procesos = null;
        Ctrl.FsOpenFolder = Rs.FsOpenFolder;

        //Sidenav
        Ctrl.sidenavSel = null;
        Ctrl.SidenavIcons = [
			['fa-filter', 	     					'Filtros'		,false],
			['fa-sign-in-alt fa-rotate-90 fa-lg', 	'Descargar'		,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};

		//Filtros
        Ctrl.filters = {
        	proceso_ruta: false,
        	cumplimiento: false
        };

        Ctrl.filtrosCumplimiento = [
			[ 'green',    'Verde', 	        '#40d802' ],
			[ 'yellow',   'Amarillo',       '#ffac00' ],
			[ 'red',      'Rojo',           '#ff2626' ],
			[ 'no_value', 'Sin Valor/Meta', '#797979' ],
        ];

		//Ctrl.Anio  = angular.copy(Rs.AnioActual);
		//Ctrl.Mes   = angular.copy(Rs.MesActual);
		if(!$localStorage['ScorecardModo']) $localStorage['ScorecardModo'] = 'Año';
		Ctrl.Modo  = $localStorage['ScorecardModo'];
		Ctrl.Modos = {
			'Mes': ['Vista Mensual', 'md-calendar-event'],
			'Año': ['Vista Anual', 'md-calendar'],
		};
		Ctrl.changeModo = () => {
			Ctrl.Modo = (Ctrl.Modo == "Mes") ? 'Año' : 'Mes';
			$localStorage['ScorecardModo'] = Ctrl.Modo;
		};

		//Periodo
        Ctrl.PeriodoDate = moment(((Rs.AnioActual*100)+Rs.MesActual), 'YYYYMM').toDate();
        Ctrl.MaxDate = moment().add(1, 'year').endOf("year").toDate();
        Ctrl.parsePeriodo = function(dateString) {
			var m = moment(dateString, 'MMM YYYY');
			return m.isValid() ? m.toDate() : new Date(NaN);
		};
        Ctrl.formatPeriodo = (date) => {
        	var m = moment(date);
      		return m.isValid() ? m.format('MMM YYYY') : '';
        };
        Ctrl.getPeriodoParts = () => {
        	var m = moment(Ctrl.PeriodoDate);
        	Ctrl.Periodo = m.format('YYYYMM');
        	Ctrl.Mes     = m.format('MM');
        	Ctrl.Anio    = Ctrl.PeriodoDate.getFullYear();
        }
        Ctrl.getPeriodoParts();


		Ctrl.periodoAdd = (num) => {
			Ctrl.PeriodoDate = moment(Ctrl.PeriodoDate).add(num, 'month').toDate();
			Ctrl.getPeriodoParts();
		};

		Ctrl.anioAdd = (num) => {
			Ctrl.PeriodoDate = moment(Ctrl.PeriodoDate).add(num, 'year').toDate();
			Ctrl.getPeriodoParts();
			Ctrl.getScorecard(Ctrl.Sco.id);
		};



        Ctrl.Secciones = [];
        

        Ctrl.getProcesos =  (scorecard_id, Config) => {
        	return Rs.http('api/Scorecards/get-procesos', { id: scorecard_id }, Ctrl, 'Procesos').then(() => {
        		
        		Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos, 'Ruta','Proceso', false, true);

        		if('proceso_id' in Config){
					if(Config.proceso_id !== null){
						Ctrl.ProcesoSel = Ctrl.Procesos.find( p => p.id == Config.proceso_id );
						if(Ctrl.ProcesoSel){
							Ctrl.filters.proceso_ruta = Ctrl.ProcesoSel.Ruta;
						}
					}
				}

        		return Ctrl.getScorecard(scorecard_id, Config);
        	});
        };

		Ctrl.getScorecard = (scorecard_id, Config) => {
			if(!scorecard_id) return;
			Ctrl.Loading = true;
			Ctrl.ProcesoSelName = '';

			if(!Ctrl.Procesos) return Ctrl.getProcesos(scorecard_id, Config);

			Ctrl.filters.Periodo = Ctrl.Periodo;

			Rs.http('api/Scorecards/get', { id: scorecard_id, Anio: Ctrl.Anio, filters: Ctrl.filters }, Ctrl, 'Sco').then(() => {
            	Ctrl.Loading = false;
            	Ctrl.SidenavIcons[0][2] = (typeof  Ctrl.filters.proceso_ruta === 'string');
            	
            	if(Ctrl.filters.proceso_ruta){
            		Ctrl.ProcesoSel = Ctrl.Procesos.find( p => p.Ruta == Ctrl.filters.proceso_ruta )
            		Ctrl.ProcesoSelName = Ctrl.filters.proceso_ruta.split('\\').pop();
            	}

            	//Ctrl.downloadIndicadores();

            });    
		};

		Ctrl.openFlatLevel = (N, ev) => {
			ev.stopPropagation();
			if(N.tipo !== 'Nodo') return Ctrl.decideAction(N);

			N.open = !N.open;

			//var cont = true;
			angular.forEach(Ctrl.Sco.nodos_flat, (nodo) => {

				var hijo = nodo.ruta.startsWith(N.ruta) && nodo.depth > N.depth;

				if(hijo){
					if(nodo.depth == N.depth + 1){ nodo.show = N.open; nodo.open = false; }
					else{
						nodo.show = nodo.open = false;
					}
				}
			});
		}

		Ctrl.decideAction = (N) => {
			if(N.tipo == 'Indicador'){
				Rs.viewIndicadorDiag(N.elemento.id);
			}else if(N.tipo == 'Variable'){
				Rs.viewVariableDiag(N.elemento.id);
			}
		};

		//Filtros
		Ctrl.lookupProceso = (F) => {
			Ctrl.filters.proceso_ruta = F.route;
		}

		Ctrl.clearCache = () => {
			var nodos_ids = [];
			angular.forEach(Ctrl.Sco.nodos_flat, N => {
				if(N.tipo != 'Nodo') nodos_ids.push(N);
			});

			Rs.http('api/Scorecards/erase-cache', { Inds: nodos_ids }).then(() => {
				//Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				Rs.showToast('Caché Borrada', 'Success');
			});
		}


		//Descarga de Datos
		function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;        
        }

        function excelColName(n) {
			var ordA = 'a'.charCodeAt(0);
			var ordZ = 'z'.charCodeAt(0);
			var len = ordZ - ordA + 1;

			var s = "";
			while(n >= 0) {
				s = String.fromCharCode(n % len + ordA).toUpperCase() + s;
				n = Math.floor(n / len) - 1;
			}
			return s;
		}

		Ctrl.downloadIndicadores = () => {

			

	        var SheetData = [
	        	['Indicador', 'Proceso', 'Sentido', 'Periodo', 'Meta', 'Real', 'Cumplimiento', 'Peso']
	        ];

	        var Niveles = 0;
	        angular.forEach(Ctrl.Sco.nodos_flat, N => {
	        	if(N.tipo !== 'Nodo'){
	        		let RutaArr = N.ruta.split("\\");
	        		RutaArr.pop();

	        		N.ruta_arr = RutaArr;

	        		Niveles = Math.max(Niveles, RutaArr.length);
	        	}
	        });

	        //Agregar niveles a cabecera
	        for (var i = 1; i <= Niveles; i++) {
	        	SheetData[0].push('Nivel_'+i);
	        }


	        angular.forEach(Ctrl.Sco.nodos_flat, N => {
	        	if(N.tipo !== 'Nodo'){

	        		angular.forEach(N.valores, P => {
	        			if(P.calculable){
	        				let Fila = [
			        			N.Nodo,
			        			N.elemento.proceso.Proceso,
			        			N.elemento.Sentido,
			        			P.Periodo,
			        			P.meta_Valor,
			        			P.Valor,
			        			P.cump_porc,
			        			N.peso
			        		];

			        		angular.forEach(N.ruta_arr, RA => {
			        			Fila.push(RA);
			        		});

			        		SheetData.push(Fila);
	        			}
	        		});

	        		
	        	}
	        });

			var wb = XLSX.utils.book_new();
	        wb.Props = {
                Title: "Datos Tablero de Mando "+ Ctrl.Sco.Titulo,
                CreatedDate: new Date()
	        };

			var ws = XLSX.utils.aoa_to_sheet(SheetData);
			var last_cell = excelColName(SheetData[0].length - 1) + (SheetData.length);
			ws['!autofilter'] = { ref: ('A1:'+last_cell) };
	        
	        XLSX.utils.book_append_sheet(wb, ws, "Datos");
	        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
	     
	        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), Ctrl.Sco.Titulo + '_Datos.xlsx');
		}

        //Ctrl.getScorecard();
	}
]);

angular.module('VariablesCtrl', [])
.controller('VariablesCtrl', ['$scope', '$rootScope', '$injector', '$filter', '$mdEditDialog', '$mdDialog',
	function($scope, $rootScope, $injector, $filter, $mdEditDialog, $mdDialog) {

		console.info('VariablesCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.VarSel = null;
		Ctrl.VariablesNav = true;
		Rs.mainTheme = 'Snow_White';
		Ctrl.VariablesCRUD = $injector.get('CRUD').config({ base_url: '/api/Variables' });
		Ctrl.filterVariables = '';

		Ctrl.Cancel = $mdDialog.cancel;

		Ctrl.tiposDatoVar = ['Numero','Porcentaje','Moneda','Millones'];
		Ctrl.Frecuencias = {
			0: 'Diario',
			1: 'Mensual',
			2: 'Bimestral',
			3: 'Trimestral',
			6: 'Semestral',
			12: 'Anual'
		};

		Ctrl.agregators = Rs.agregators;

		Ctrl.getVariables = () => {

			if(Ctrl.variable_id) return Ctrl.prepVariableDiag();

			Promise.all([
				Rs.getProcesos(Ctrl),
				Rs.http('/api/Entidades/grids-get', {}, Ctrl, 'Grids')
			]).then(() => {

				Ctrl.VariablesCRUD.get().then(() => {

					var ids_procesos = Ctrl.VariablesCRUD.rows.map(e => e.proceso_id).filter((v, i, a) => a.indexOf(v) === i);
					Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos.filter(p => ids_procesos.includes(p.id)),'Ruta','Proceso',false,true);
					angular.forEach(Ctrl.ProcesosFS, (P) => {
						if(P.type == 'folder'){
							P.file = Ctrl.Procesos.find(p => p.Ruta == P.route);
						}
					});

					//Ctrl.getFs();

					if(Rs.Storage.VariableSel){
						var variable_sel_id = Rs.getIndex(Ctrl.VariablesCRUD.rows, Rs.Storage.VariableSel);
						Ctrl.openVariable(Ctrl.VariablesCRUD.rows[variable_sel_id]);
					};
				});

			});
		};

		Ctrl.prepVariableDiag = () => {
			Ctrl.Procesos = Ctrl.$parent.Procesos;
			Ctrl.ProcesosFS = Ctrl.$parent.ProcesosFS;
			Ctrl.Grids = Ctrl.$parent.Grids;
			Ctrl.VariablesCRUD = Ctrl.$parent.VariablesCRUD;

			var variable_sel_id = Rs.getIndex(Ctrl.VariablesCRUD.rows, Ctrl.variable_id);
			Ctrl.openVariable(Ctrl.VariablesCRUD.rows[variable_sel_id]);
		}

		Ctrl.openProceso = (P) => { Ctrl.ProcesoSelId = P.id; }

		Ctrl.getVariablesFiltered = () => {
			if(Ctrl.filterVariables.trim() == ''){
				return $filter('filter')(Ctrl.VariablesCRUD.rows, { proceso_id: Ctrl.ProcesoSelId }, true);
			}else{
				return $filter('filter')(Ctrl.VariablesCRUD.rows, Ctrl.filterVariables);
			}
		}

		Ctrl.getFs = () => {
			Ctrl.filterVariables = "";
			Ctrl.VariablesFS = Rs.FsGet(Ctrl.VariablesCRUD.rows,'Ruta','Variable');
		};

		Ctrl.getFolderVarData = (F) => {
			var Vars = Ctrl.VariablesCRUD.rows.filter((v) => {
				return v.Ruta.startsWith(F.route);
			}).map(v => v.id);
			Rs.getVariableData(Vars, null);
		};

		Ctrl.searchVariable = () => {
			if(Ctrl.filterVariables == ""){
				Ctrl.getFs();
			}else{
				Ctrl.VariablesFS = Rs.FsGet($filter('filter')(Ctrl.VariablesCRUD.rows, Ctrl.filterVariables),'Ruta','Variable',true);
			};
		};

		Ctrl.addVariable = () => {
			
			Ctrl.getFs();
			Rs.BasicDialog({
				Title: 'Crear Variable', Flex: 50,
				Fields: [
					{ Nombre: 'Nombre',  Value: '', 				Required: true, flex: 100 },
					{ Nombre: 'Proceso', Value: Ctrl.ProcesoSelId, Required: true, flex: 100, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
					//{ Nombre: 'Ruta',    Value: '', flex: 70, Type: 'fsroute', List: Ctrl.VariablesFS },
					//{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				],
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.VariablesCRUD.add({
					//Ruta: Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					Variable: f.Nombre,
					Filtros: [], proceso_id: f.Proceso
				}).then(() => {
					Ctrl.getFs();
				});
			});
		};

		Ctrl.openVariable = (V) => {
			Rs.http('/api/Variables/get-variable', { id: V.id }, Ctrl, 'VarSel').then(() => {
				//Rs.getVariableData([Ctrl.VarSel.id]);
				//
				Ctrl.ProcesoSelId = Ctrl.VarSel.proceso_id;
				Rs.Storage.VariableSel = Ctrl.VarSel.id;

				//Rs.viewVariableDiag(Ctrl.VarSel.id);
			});
		};

		Ctrl.updateVariable = () => {
			Ctrl.VariablesCRUD.update(Ctrl.VarSel).then(() => {
				Rs.showToast('Variable Actualizada', 'Success');
				Ctrl.openVariable(Ctrl.VarSel);
			});
		};

		Ctrl.addFiltro = () => {
			var col = angular.copy(Ctrl.newFiltro);
			Ctrl.VarSel.Filtros.push({
				columna_id: col.id,
				column_title: col.column_title,
				tipo_campo: col.tipo_campo,
				campo_id: col.campo.id,
				campo: col.campo,
				obs: '',
				Comparador: '=', Valor: null, Op1: null, Op2: null, Op3: null
			});
			Ctrl.newFiltro = null;
		};

		Ctrl.editValor = (Periodo) => {
			var Valor = angular.isDefined(Ctrl.VarSel.valores[Periodo]) ? Ctrl.VarSel.valores[Periodo].Valor : null;
			

			Rs.BasicDialog({
				Title: 'Cambiar valor '+Periodo,
				Confirm: { Text: 'Cambiar' }, Flex: 20,
				Fields: [
					{ Nombre: 'Valor',  Value: Valor, Required: false, Regex: "\\d+" }
				], //          ^[0-9]+([.][0-9]{1,4})?$
			}).then((r) => {
				if(!r) return;
				newValor = (r.Fields[0].Value != "") ? r.Fields[0].Value : null;
				if(newValor == Valor) return;
				Rs.http('/api/Variables/update-valor', { variable_id: Ctrl.VarSel.id, Periodo: Periodo, Valor: newValor }).then(() => {
					Ctrl.openVariable(Ctrl.VarSel);
				});
			});
		};

		Ctrl.editValor2 = (event, Periodo) => {
			event.stopPropagation(); // in case autoselect is enabled

			var Valor = angular.isDefined(Ctrl.VarSel.valores[Periodo]) ? Ctrl.VarSel.valores[Periodo].Valor : null;
			if(Ctrl.VarSel.TipoDato == 'Porcentaje') Valor *= 100;
			
			return $mdEditDialog.small({
				modelValue:  Valor,
				targetEvent: event,
				placeholder: Periodo, title: Periodo,
				save: function (input) {
					var newValor = parseFloat(input.$modelValue);
					if(Number.isNaN(newValor)) newValor = null;
					if(Ctrl.VarSel.TipoDato == 'Porcentaje') newValor /= 100;
					if(newValor == Valor) return;

					Rs.http('/api/Variables/update-valor', { variable_id: Ctrl.VarSel.id, Periodo: Periodo, Valor: newValor }).then(() => {
						Ctrl.openVariable(Ctrl.VarSel);
					});

				}
			});
		}

		Ctrl.copyVar = () => {
			Rs.BasicDialog({
				Title: 'Copiar Variable', Flex: 50, clickOutsideToClose: false,
				Confirm: { Text: 'Crear' },
				Fields: [
					{ Nombre: 'Nombre',  	    Value: Ctrl.VarSel.Variable + ' (copia)', Required: true, flex: 60 },
					{ Nombre: 'Proceso',        Value: Ctrl.VarSel.proceso_id,  Required: true, flex: 40, Type: 'list', List: Ctrl.Procesos, Item_Val: 'id', Item_Show: 'Proceso' },
					{ Nombre: 'Descripcion',  	Value: Ctrl.VarSel.Descripcion, Required: true },
					//{ Nombre: 'Ruta',       Value: Ctrl.VarSel.Ruta, flex: 70, Type: 'fsroute', List: Ctrl.VariablesFS },
					//{ Nombre: 'Crear Carpeta', Value: '', flex: 30, Type: 'string' },
				]
			}).then((r) => {
				if(!r) return;
				var f = Rs.prepFields(r.Fields);
				Ctrl.VariablesCRUD.add({
					//Ruta: 			Rs.FsCalcRoute(f.Ruta, f['Crear Carpeta']),
					proceso_id:     f.Proceso, 
					Variable: 		f.Nombre,
					Descripcion: 	f.Descripcion,
					TipoDato: 		Ctrl.VarSel.TipoDato,
					Decimales: 		Ctrl.VarSel.Decimales,
					Tipo: 			Ctrl.VarSel.Tipo,
					grid_id: 		Ctrl.VarSel.grid_id,
					ColPeriodo: 	Ctrl.VarSel.ColPeriodo,
					Agrupador: 		Ctrl.VarSel.Agrupador,
					Col: 			Ctrl.VarSel.Col,
					Filtros: 		Ctrl.VarSel.Filtros,
				}).then(() => { Ctrl.getFs(); });
			});
		};


		Ctrl.seleccionarEntidadGrid = () => {

			Rs.TableDialog(Ctrl.Grids, {
				Title: 'Seleccionar Grid', Flex: 60,
				primaryId: 'id', pluck: true,
				Columns: [
					{ Nombre: 'entidad.proceso.Proceso', Desc: 'Proceso', numeric: false },
					{ Nombre: 'entidad.Nombre', Desc: 'Entidad', numeric: false },
					{ Nombre: 'Titulo', 		Desc: 'Grid',    numeric: false }
				],
				selected: [], multiple: false, orderBy: 'Titulo',
			}).then(r => {
				if(!r) return;
				
				Ctrl.VarSel.grid_id = r[0];
				Ctrl.updateVariable();

			});

		}


		Ctrl.getVariables();
	}
]);
angular.module('VariablesGetDataDiagCtrl', [])
.controller('VariablesGetDataDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Variables', 'Tipo',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Variables, Tipo) {

		console.info('VariablesGetDataDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.PeriodoIni = moment().subtract(1, 'months').toDate();
		Ctrl.PeriodoFin = moment().subtract(1, 'months').toDate();
		Ctrl.Anios = [3,2,1,0,-1].map((n) => { return Ctrl.Anio-n});

		
		Ctrl.overwriteValues = true;

		Ctrl.periodDateLocale = Rs.periodDateLocale;
		Ctrl.TipoVar = Tipo || 'Calculado de Entidad';
		
		Ctrl.getVariables = () => {
			Rs.http('api/Variables/get-variables', { ids: Variables, Tipo: Ctrl.TipoVar }, Ctrl, 'Variables').then(() => {
				Ctrl.selectedRows = Ctrl.Variables.map( v => v.id );
			});
		}

		

		Ctrl.calcPeriodos = () =>{
			var periodoAct = parseInt(moment(Ctrl.PeriodoIni).format('YMM'));
			var periodoLim = parseInt(moment(Ctrl.PeriodoFin).format('YMM'));
			Ctrl.Periodos = [ periodoAct ];
			while (periodoAct < periodoLim){
				var y = parseInt(periodoAct/100);
				var m = periodoAct - (y*100);

				if(m < 12){
					periodoAct = (y*100) + (m+1);
				}else{
					periodoAct = ((y+1)*100) + 1;
				}

				Ctrl.Periodos.push(periodoAct);
			};
		};

		Ctrl.cellSelected = (V,M) => {
			if(V){
				var Selected = Rs.inArray(V.id, Ctrl.selectedRows);
				if(!Selected) return false;
			};
			var PeriodoCell = Ctrl.Anio*100 + parseInt(M[0]);
			return Rs.inArray(PeriodoCell, Ctrl.Periodos);
		};

		Ctrl.eraseData = () => {
			angular.forEach(Ctrl.Variables, (v) => {
				if(Rs.inArray(v.id, Ctrl.selectedRows)){
					if(!angular.isDefined(v.newValores)) v.newValores = {};
					angular.forEach(Ctrl.Periodos, (P) => {
						v.valores[P]    = { val: null, Valor: null };
						v.newValores[P] = { val: null, Valor: null };
					});
				};
			});
		};

		Ctrl.startDownload = () => {
			Ctrl.VarIndex = 0;
			Ctrl.stepDownload();
		};

		Ctrl.stepDownload = () => {
			
			var Var = Ctrl.Variables[Ctrl.VarIndex];
			if(!angular.isDefined(Var)) return;
			console.log(Ctrl.VarIndex, Rs.inArray(Var.id, Ctrl.selectedRows));
			if(!Rs.inArray(Var.id, Ctrl.selectedRows)){
				Ctrl.VarIndex++; 
				return Ctrl.stepDownload();
			}else{
				Rs.http('api/Variables/calc-valores', { Var: Var, Periodos: Ctrl.Periodos }).then((r) => {
					Var.newValores = r;
					Ctrl.VarIndex++;
					Ctrl.stepDownload();
				});
			}

			
		};

		Ctrl.storeVars = () => {
			var Variables = Ctrl.Variables.filter((e) => {
				return Rs.inArray(e.id, Ctrl.selectedRows);
			});

			Rs.http('api/Variables/store-valores', { Variables: Variables, Periodos: Ctrl.Periodos, overwriteValues: Ctrl.overwriteValues }).then((r) => {
				angular.forEach(r, (v) => {
					var i = Rs.getIndex(Ctrl.Variables, v.id);
					Ctrl.Variables[i] = v;
				});
				//Var.newValores = r;
			});
		};

		Ctrl.getVariables();
		Ctrl.calcPeriodos();
	}
]);


angular.module('Variables_VariableDiagCtrl', [])
.controller('Variables_VariableDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', 'variable_id', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, variable_id, $timeout) {

		console.info('Variables_VariableDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
        Ctrl.viewVariableDiag = Rs.viewVariableDiag;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.anioAdd = (num) => { Ctrl.Anio += num; Ctrl.getVariables(); };
        
        Ctrl.viewRelatedVariables = false;

		Ctrl.getVariables = () => {

            Rs.http('api/Variables/get', { id: variable_id, Anio: Ctrl.Anio }, Ctrl, 'Var').then(() => {
                Ctrl.Anios = [(Ctrl.Anio - 1), Ctrl.Anio];
                angular.forEach(Ctrl.Anios, (Anio, kA) => {
                    angular.forEach(Rs.Meses, (Mes, kM) => {
                        
                        var i = parseInt(kM);
                        var VarVal = Ctrl.Var.valores[(Anio*100)+(i+1)];
                        var Valor = (VarVal == null) ? null : VarVal.Valor;
                        Ctrl.graphData[kA].values[i] = { x: i, y: Valor };
                    });
                });

                Ctrl.updateChart();

            });
		};

        Ctrl.updateChart = () => {
            d3.selectAll('.nvtooltip').style('opacity', 0);
            Ctrl.graphApi.update();
        }

 		Ctrl.grapOptions = {
            chart: {
                type: 'multiChart',
                margin: {
                	top:5, right:0, bottom:5, left:80
                },
                height: 150,
                y: function(d,i) { return d.y; },
                x: function(d,i) { return d.x; },
                showLegend: false,
                xAxis: {
                	showMaxMin: false,
                    ticks: 0,
                    tickFormat: function(d){
                        //return d;
                        return Rs.Meses[d][1];
                    },
                },
                yAxis1: {
                    tickFormat: function(d){
                        return Rs.formatVal(d,Ctrl.Var.TipoDato,Ctrl.Var.Decimales);
                    },
                },
                bars1: {
                },
                lines1: {
                	padData: true,
                },
                padData: true,
                //forceY:[0],
                //yDomain1: [0,0.1],
                useInteractiveGuideline: true,
                interactiveLayer:{
                	showGuideLine: false,
                },
                legend: {
                    //margin: { right: 10 }
                },

            }
        };

        Ctrl.graphData = [
            { key: (Ctrl.Anio-1), yAxis: 1, type: 'line', values: [], color: '#ababab',  },
            { key: Ctrl.Anio,     yAxis: 1, type: 'line', values: [], color: '#6ab8ff', strokeWidth: 4 },
        ];

        Ctrl.getVariables();

        //Menu
        Ctrl.openMenuValores = (ev, Periodo) => {
            var Val = Ctrl.Var.valores[Periodo].Valor;
            Rs.viewVariableMenu(ev, Ctrl.Var, Periodo, Val, Ctrl.getVariables);
        };

        //Desagregacion
        Ctrl.addedDesagregado = ($chip) => {
            var index = Rs.getIndex(Ctrl.Var.desagregables, $chip.id);
            Ctrl.Var.desagregables.splice(index,1);
        };

        Ctrl.removedDesagregado = ($chip) => {
            Ctrl.Var.desagregables.push($chip);
        };

        Ctrl.getDesagregatedData = () => {
             Rs.http('api/Variables/get-desagregacion', { variable_id: variable_id, Anio: Ctrl.Anio, desag_campos: Ctrl.Var.desagregados }, Ctrl, 'Desagregacion');
        };



	}
]);

angular.module('CRUD', [])
.factory('CRUD', [ '$rootScope', '$q', '$mdDialog',
	function($rootScope, $q, $mdDialog){

		var Rs = $rootScope;

		var CRUD = function(ops) {
			var t = this;

			t.ops = {
				base_url: '',
				name: '',
				primary_key: 'id',
				ready: false,
				where: {},
				limit: false,
				loading: false,
				obj: null,
				only_columns: [],
				add_append: 'end',
				add_research: false,
				add_with: false,
				query_scopes: [],
				query_with: [],
				query_call: [],
				query_call_arr: [],
				order_by: [],
				selected:[]
			};
			t.columns = [];
			t.rows = [];

			angular.extend(t.ops, ops);

			//console.info('Crud initiated', t.ops);

			t.get = function(columns){
				
				if(t.ops.loading) return false;
				t.ops.loading = true;

				t.ops.only_columns = Rs.def(columns, []);
				t.rows = [];

				return Rs.http(t.ops.base_url, { fn: 'get', ops: t.ops }).then(function(r) {
					if(r.ops){
						t.columns = r.ops.columns;
						delete r.ops.columns;
						angular.extend(t.ops, r.ops);
					};
					t.rows = r.rows;
					t.ops.loading = false;
				});
			};


			t.where = function(where){
				t.ops.where[where[0]] = where;
				return t;
			};

			t.find = function(id, main, prop){
				t.ops.find_id = id;
				return Rs.http(t.ops.base_url, { fn: 'find', ops: t.ops }, main, prop);
			};

			t.add = function(Obj){
				t.ops.obj = Obj;
				return Rs.http(t.ops.base_url, { fn: 'add', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					if(t.ops.add_append == 'end'){ t.rows.push(r); }
					else if(t.ops.add_append == 'start'){ t.rows.unshift(r); }
					else if(t.ops.add_append == 'refresh'){ return t.get(); };
					return r;
				});
			};

			t.addMultiple = function(Objs){
				t.ops.obj = Objs;
				return Rs.http(t.ops.base_url, { fn: 'addmultiple', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					return t.get();
					return r;
				});
			};

			t.update = function(Obj){
				t.ops.obj = Obj;
				return Rs.http(t.ops.base_url, { fn: 'update', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					Rs.updateArray(t.rows, r, t.ops.primary_key);
					return r;
				});
			};

			t.updateMultiple = function(Objs){
				t.ops.obj = Objs || angular.copy(t.ops.selected);
				return Rs.http(t.ops.base_url, { fn: 'updatemultiple', ops: t.ops }).then(function(rs) {
					angular.forEach(rs, (r) => {
						Rs.updateArray(t.rows, r, t.ops.primary_key);
					});
					t.ops.obj = null;
					t.ops.selected = [];
				});
			};

			t.delete = function(Obj){
				t.ops.obj = Obj;
				var Index = Rs.getIndex(t.rows, Obj[t.ops.primary_key], t.ops.primary_key);
				return Rs.http(t.ops.base_url, { fn: 'delete', ops: t.ops }).then(function(r) {
					t.ops.obj = null;
					t.rows.splice(Index, 1);
				});
			};

			t.deleteMultiple = function(){
				t.ops.obj = angular.copy(t.ops.selected);
				return Rs.http(t.ops.base_url, { fn: 'deletemultiple', ops: t.ops }).then(function(r) {
					angular.forEach(t.ops.obj, (Obj) => {
						var Index = Rs.getIndex(t.rows, Obj[t.ops.primary_key], t.ops.primary_key);
						t.rows.splice(Index, 1);
					});
					t.ops.obj = null;
					t.ops.selected = [];
				});
			};

			t.dialog = function(Obj, diagConfig){
				var config = {
					theme: 'default',
					title: '',
					class: 'wu400',
					controller: 'CRUDDialogCtrl',
					templateUrl: '/templates/dialogs/crud-dialog.html',
					fullscreen: false,
					clickOutsideToClose: false,
					multiple: true,
					ev: null,
					confirmText: 'Guardar',
					with_delete: true,
					delete_title: '',
					only: [],
					except: [],
					buttons: [],
				};

				angular.extend(config, diagConfig);

				return $mdDialog.show({
					controller:  config.controller,
					templateUrl: config.templateUrl,
					locals: 	{ ops : t.ops, config: config, columns: t.columns, Obj: Obj, rows: t.rows },
					clickOutsideToClose: config.clickOutsideToClose,
					fullscreen:  config.fullscreen,
					multiple: 	 config.multiple,
					targetEvent: config.ev
				});
			};

			//Poner un scope
			t.setScope = (Scope, Params) => {
				var Index = -1;
				angular.forEach(t.ops.query_scopes, ($S, $k) => {
					if($S[0] == Scope){ Index = $k; return; }
				});
				if(Index == -1){
					t.ops.query_scopes.push([ Scope, Params ]);
				}else{
					t.ops.query_scopes[Index] = [ Scope, Params ];
				};
				return t;
			};

			//Obtener un elemento por primary_key
			t.one = (key) => {
				var Index = Rs.getIndex(t.rows, key, t.ops.primary_key);
				return t.rows[Index];
			};

		};

		return {
			config: function (ops) {
				//console.log('Creating', ops);
				var DaCRUD = new CRUD(ops);
				return DaCRUD;
			}
		};
	}
]);
angular.module('Filters', [])
	.filter('to_trusted', ['$sce', function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])
	.filter('findId', function() {
		return function(input, id) {
			var i=0, len=input.length;
			for (; i<len; i++) {
			  if (+input[i].id == +id) {
				return input[i];
			  }
			}
			return null;
		 };
	}).filter('getIndex', function() {
		return function(input, id, attr) {
			var len=input.length;
			attr = (typeof attr !== 'undefined') ? attr : 'id';
			for (i=0; i<len; i++) {
			  if(input[i][attr] === id) {
				return i;
			  }
			}
			return null;
		 };
	}).filter('include', function() {
		return function(input, include, prop) {
			if (!angular.isArray(input)) return input;
			if (!angular.isArray(include)) include = [];
			return input.filter(function byInclude(item) {
				return include.indexOf(prop ? item[prop] : item) != -1;
			});
		};
	}).filter('exclude', function() {
		return function(input, exclude, prop) {
			if (!angular.isArray(input)) return input;
			if (!angular.isArray(exclude)) exclude = [];
			/*if (prop) {
				exclude = exclude.map(function byProp(item) {
					return item[prop];
				});
			};*/

			return input.filter(function byExclude(item) {
				return exclude.indexOf(prop ? item[prop] : item) === -1;
			});
		};
	}).filter('category', function() {
		return function(input, category, prop) {
			//console.log(input, category, prop);
			if (!angular.isArray(input)) return input;
			if(!category) return input;
			return input.filter(function(item){
				return item[prop] == category;
			});
			//return input[prop] == category;
		};
	}).filter('toArray', function () {
		return function (obj, addKey) {
			if (!angular.isObject(obj)) return obj;
			if ( addKey === false ) {
			return Object.keys(obj).map(function(key) {
				return obj[key];
			});
			} else {
			return Object.keys(obj).map(function (key) {
				var value = obj[key];
				return angular.isObject(value) ?
				Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
				{ $key: key, $value: value };
			});
			}
		};
	}).filter('pluck', function() {
		return function(array, key, unique) {
			var res = new Array();
			angular.forEach(array, function(v) {
				if(unique && res.indexOf(v[key]) !== -1) return false;
				res.push(v[key]);
			});
			return res;
		};
	}).filter('switch', function() {
	    return function(input, boolean) {
	    	return (boolean) ? input : [];
	    }
	}).filter('search', function() {
		return function(input, search) {
			if (!input) return input;
			if (!search) return input;
			var expected = ('' + search).toLowerCase();
			var result = {};
			angular.forEach(input, function(value, key) {
				var actual = ('' + value).toLowerCase();
				if (actual.indexOf(expected) !== -1) {
					result[key] = value;
				}
			});
			return result;
		}
	}).filter('capitalize', function() {
	    return function(input) {
	      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
	    }
	}).filter('capitwords', function() {
	    return function(input,limit) {
	    	if (!input) return '';
	    	input = input.split('_').join(' ');
	    	limit = (!!limit) ? limit : 2;
	    	return input.split(' ').map(function(wrd){
	    		return (wrd.length) > limit ? wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase() : wrd.toLowerCase();
	    	}).join(' ');
	    }
	}).filter('traducirum', function() {
	    return function(input,um) {
	    	if(input <= 0){
	    		return 'No incluido';
	    	}else if(um == 'KG'){
	    			 if(input < 1){ input = input*1000; um = 'Gramos'  }
	    		else if(input == 1){ um = 'Kilo'  }
	    		else if(input > 1 && input < 1000 ){ um = 'Kilos'  }
	    		else if(input >= 1000){ input = input/1000; um = 'Toneladas'  }
	    	}else if(um == 'LT'){
	    			 if(input < 1){ input = input*1000; um = 'Mililitros'  }
	    		else if(input == 1){ um = 'Litro'  }
	    		else if(input > 1 ){ um = 'Litros'  }
	    	}else if(um == 'UN'){
	    			 if(input <= 1){ um = 'Unidad'  }
	    		else if(input > 1 ){ um = 'Unidades'  }
	    	};
	    	return input + ' ' + um;
	    }
	}).filter('percentage', ['$filter', function ($filter) {
		return function (input, decimals) {
		return $filter('number')(input * 100, decimals) + '%';
		};
	}]).filter('numberformat', ['$filter', function ($filter) {
		return function (input, tipodato, decimales) {
			if(!input) return input;

			if(tipodato == 'Millones') return "$ " + $filter('number')((input/1000000), decimales) + "M";

			if(tipodato == 'Porcentaje') input = input * 100;
			var number = $filter('number')(input, decimales);
			if(tipodato == 'Porcentaje') return number + "%";
			if(tipodato == 'Moneda') return "$ " + number;
			return number;
		};
	}]).filter('splice', function() {
		return function(input, index, len) {
			if(!input) return input;
			//if(!index || !len) return input;
			return input.splice(index, len);
		};
	}).filter('getword', function() {
		return function(input, index) {
			if(!input) return input;
			var arr = input.split(' ');
			return arr[index-1];
		};
	}).filter('getype', function() {
		return function(input) {
			return typeof input;
		};
	});
// Reacts upon enter key press.
angular.module('enterStroke', []).directive('enterStroke',
  function () {
    return function (scope, element, attrs) {
      element.bind('keydown keypress', function (event) {
        if(event.which === 13) {
          scope.$apply(function () {
            scope.$eval(attrs.enterStroke);
          });
          event.preventDefault();
        }
      });
    };
  }
);
angular.module('extSubmit', []).directive("extSubmit", ['$timeout',function($timeout){
    return {
        link: function($scope, $el, $attr) {
            $scope.$on('makeSubmit', function(event, data){
              if(data.formName === $attr.name) {
                $timeout(function() {
                  $el.triggerHandler('submit'); //<<< This is Important
                  //$el[0].dispatchEvent(new Event('submit')) //equivalent with native event
                }, 0, false);   
              }
            })
        }
    };
}]);
angular.module('fileread', [])
.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = JSON.parse(loadEvent.target.result);
                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);
angular.module('focusOn', [])
.directive('focusOn', function() {
   return function(scope, elem, attr) {
      scope.$on(attr.focusOn, function(e) {
      		setTimeout(function(){ 
      			elem[0].focus();
          		console.log('Focused', elem);
      		}, 3000);
      });
   };
});
angular.module('horizontalScroll', []).
directive('horizontalScroll', function () {

    return {
        link:function (scope, element, attrs) {
            var base = 0

            element.bind("DOMMouseScroll mousewheel onmousewheel", function(event) {

                // cross-browser wheel delta
                var event = window.event || event; // old IE support
                var delta = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));


                scope.$apply(function(){
                    base += (30*delta);
                    //console.log(element, base);
                    element.children().css({'transform':'translateX('+base+'px)'});
                    //element.scrollLeft(base);
                });

                // for IE
                event.returnValue = false;
                // for Chrome and Firefox
                if(event.preventDefault) { event.preventDefault(); }


            });
        }
    };
});
angular.module('hoverClass', [])
.directive('hoverClass', [function () {
    return {
        restrict: 'A',
        scope: {
            hoverClass: '@'
        },
        link: function (scope, element) {
            element.on('mouseenter', function() {
                element.addClass(scope.hoverClass);
            });
            element.on('mouseleave', function() {
                element.removeClass(scope.hoverClass);
            });
        }
    };
}]);
(function () {
    'use strict';

    angular.module('ngJsonExportExcel', [])
        .directive('ngJsonExportExcel', function () {
            return {
                restrict: 'AE',
                scope: {
                    data : '=',
                    filename: '=?',
                    reportFields: '=',
                    separator: '@'
                },
                link: function (scope, element) {
                    scope.filename = !!scope.filename ? scope.filename : 'export-excel';
                    scope.extension = !!scope.extension ? scope.extension : '.csv';

                    var fields = [];
                    var header = [];
                    var separator = scope.separator || ';';

                    angular.forEach(scope.reportFields, function(field, key) {
                        if(!field || !key) {
                            throw new Error('error json report fields');
                        }

                        fields.push(key);
                        header.push(field);
                    });

                    element.bind('click', function() {
                        var bodyData = _bodyData();
                        var strData = _convertToExcel(bodyData);

                        var blob = new Blob([strData], { 
                            type: "text/plain;charset=utf-8"
                            //type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });

                        return saveAs(blob, [scope.filename + scope.extension ]);
                    });

                    function _bodyData() {
                        var data = scope.data;
                        var body = "";
                        angular.forEach(data, function(dataItem) {
                            var rowItems = [];

                            angular.forEach(fields, function(field) {
                                if(field.indexOf('.')) {
                                    field = field.split(".");
                                    var curItem = dataItem;

                                    // deep access to obect property
                                    angular.forEach(field, function(prop){
                                        if (curItem !== null && curItem !== undefined) {
                                            curItem = curItem[prop];
                                        }
                                    });

                                    data = curItem;
                                }
                                else {
                                    data = dataItem[field];
                                }

                                var fieldValue = data !== null ? data : ' ';

                                if (fieldValue !== undefined && angular.isObject(fieldValue)) {
                                    fieldValue = _objectToString(fieldValue);
                                }

                                rowItems.push(fieldValue);
                            });

                            body += rowItems.join(separator) + '\n';
                        });

                        return body;
                    }

                    function _convertToExcel(body) {
                        return header.join(separator) + '\n' + body;
                    }

                    function _objectToString(object) {
                        var output = '';
                        angular.forEach(object, function(value, key) {
                            output += key + ':' + value + ' ';
                        });

                        return '"' + output + '"';
                    }
                }
            };
        });
})();
/*! ng-csv 10-10-2015 */
!function(a){angular.module("ngCsv.config",[]).value("ngCsv.config",{debug:!0}).config(["$compileProvider",function(a){angular.isDefined(a.urlSanitizationWhitelist)?a.urlSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|data):/):a.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|data):/)}]),angular.module("ngCsv.directives",["ngCsv.services"]),angular.module("ngCsv.services",[]),angular.module("ngCsv",["ngCsv.config","ngCsv.services","ngCsv.directives","ngSanitize"]),"undefined"!=typeof module&&"undefined"!=typeof exports&&module.exports===exports&&(module.exports="ngCsv"),angular.module("ngCsv.services").service("CSV",["$q",function(a){var b="\r\n",c="﻿",d={"\\t":"	","\\b":"\b","\\v":"","\\f":"\f","\\r":"\r"};this.stringifyField=function(a,b){return"locale"===b.decimalSep&&this.isFloat(a)?a.toLocaleString():"."!==b.decimalSep&&this.isFloat(a)?a.toString().replace(".",b.decimalSep):"string"==typeof a?(a=a.replace(/"/g,'""'),(b.quoteStrings||a.indexOf(",")>-1||a.indexOf("\n")>-1||a.indexOf("\r")>-1)&&(a=b.txtDelim+a+b.txtDelim),a):"boolean"==typeof a?a?"TRUE":"FALSE":a},this.isFloat=function(a){return+a===a&&(!isFinite(a)||Boolean(a%1))},this.stringify=function(d,e){var f=a.defer(),g=this,h="",i="",j=a.when(d).then(function(a){if(angular.isDefined(e.header)&&e.header){var d,j;d=[],angular.forEach(e.header,function(a){this.push(g.stringifyField(a,e))},d),j=d.join(e.fieldSep?e.fieldSep:","),i+=j+b}var k=[];if(angular.isArray(a)?k=a:angular.isFunction(a)&&(k=a()),angular.isDefined(e.label)&&e.label&&"boolean"==typeof e.label){var l,m;l=[],angular.forEach(k[0],function(a,b){this.push(g.stringifyField(b,e))},l),m=l.join(e.fieldSep?e.fieldSep:","),i+=m+b}angular.forEach(k,function(a,c){var d,f,h=angular.copy(k[c]);f=[];var j=e.columnOrder?e.columnOrder:h;angular.forEach(j,function(a){var b=e.columnOrder?h[a]:a;this.push(g.stringifyField(b,e))},f),d=f.join(e.fieldSep?e.fieldSep:","),i+=c<k.length?d+b:d}),e.addByteOrderMarker&&(h+=c),h+=i,f.resolve(h)});return"function"==typeof j["catch"]&&j["catch"](function(a){f.reject(a)}),f.promise},this.isSpecialChar=function(a){return void 0!==d[a]},this.getSpecialChar=function(a){return d[a]}}]),angular.module("ngCsv.directives").directive("ngCsv",["$parse","$q","CSV","$document","$timeout",function(b,c,d,e,f){return{restrict:"AC",scope:{data:"&ngCsv",filename:"@filename",header:"&csvHeader",columnOrder:"&csvColumnOrder",txtDelim:"@textDelimiter",decimalSep:"@decimalSeparator",quoteStrings:"@quoteStrings",fieldSep:"@fieldSeparator",lazyLoad:"@lazyLoad",addByteOrderMarker:"@addBom",ngClick:"&",charset:"@charset",label:"&csvLabel"},controller:["$scope","$element","$attrs","$transclude",function(a,b,e){function f(){var b={txtDelim:a.txtDelim?a.txtDelim:'"',decimalSep:a.decimalSep?a.decimalSep:".",quoteStrings:a.quoteStrings,addByteOrderMarker:a.addByteOrderMarker};return angular.isDefined(e.csvHeader)&&(b.header=a.$eval(a.header)),angular.isDefined(e.csvColumnOrder)&&(b.columnOrder=a.$eval(a.columnOrder)),angular.isDefined(e.csvLabel)&&(b.label=a.$eval(a.label)),b.fieldSep=a.fieldSep?a.fieldSep:",",b.fieldSep=d.isSpecialChar(b.fieldSep)?d.getSpecialChar(b.fieldSep):b.fieldSep,b}a.csv="",angular.isDefined(a.lazyLoad)&&"true"==a.lazyLoad||angular.isArray(a.data)&&a.$watch("data",function(){a.buildCSV()},!0),a.getFilename=function(){return a.filename||"download.csv"},a.buildCSV=function(){var g=c.defer();return b.addClass(e.ngCsvLoadingClass||"ng-csv-loading"),d.stringify(a.data(),f()).then(function(c){a.csv=c,b.removeClass(e.ngCsvLoadingClass||"ng-csv-loading"),g.resolve(c)}),a.$apply(),g.promise}}],link:function(b,c){function d(){var c=b.charset||"utf-8",d=new Blob([b.csv],{type:"text/csv;charset="+c+";"});if(a.navigator.msSaveOrOpenBlob)navigator.msSaveBlob(d,b.getFilename());else{var g=angular.element('<div data-tap-disabled="true"><a></a></div>'),h=angular.element(g.children()[0]);h.attr("href",a.URL.createObjectURL(d)),h.attr("download",b.getFilename()),h.attr("target","_blank"),e.find("body").append(g),f(function(){h[0].click(),h.remove()},null)}}c.bind("click",function(){b.buildCSV().then(function(){d()}),b.$apply()})}}}])}(window,document);
angular.module('ngRightClick', [])
.directive('ngRightClick', ['$parse', function($parse){
	return function(scope, element, attrs) {
        var fn = $parse(attrs.ngRightClick);
        element.bind('contextmenu', function(event) {
            scope.$apply(function() {
                event.preventDefault();
                fn(scope, {$event:event});
            });
        });
    };
}]);
// Reacts upon enter key press.
angular.module('printThis', []).directive('printThis',
  function () {
    return function (scope, element, attrs) {
      element.bind('click', function (event) {
          event.preventDefault();
          //console.log(_config);

          //return false;

          $(attrs.printThis).printThis({
          		debug: false,
              importStyle: true,
          });
      });
    };
  }
);
angular.module('scorecardNodo', []).component('scorecardNodo', {
  templateUrl: 'templates/scorecard/nodo.html',
  bindings: {
    nodo: '=',
    periodo: '<'
  }
});
angular.module('SARA', [
	'ui.router',

	'ngStorage',
	'ngMaterial',
	'ngSanitize',

	'md.data.table',
	'fixed.table.header',
	'ngFileUpload',
	'angular-loading-bar',
	'angularResizable',
	'nvd3',
	'ui.utils.masks',
	'as.sortable',
	'ngCsv',
	'angular-img-cropper',
	'ui.ace',

	'scorecardNodo',

	'appRoutes',
	'appConfig',
	'appFunctions',
	'CRUD',
	'CRUDDialogCtrl',

	
	'Filters',
	'enterStroke',
	'printThis',
	'ngRightClick',
	'fileread',
	'hoverClass',
	'horizontalScroll',
	'focusOn',
	'extSubmit',

	'BasicDialogCtrl',
	'ConfirmCtrl',
	'ConfirmDeleteCtrl',
	'ListSelectorCtrl',
	'FileDialogCtrl',
	'ImageEditor_DialogCtrl',
	'IconSelectDiagCtrl',
	'ExternalLinkCtrl',
	'TableDialogCtrl',
	'RetroalimentarDiagCtrl',

	'MainCtrl',
	'LoginCtrl',

	'InicioCtrl',

	'BDDCtrl',
		'BDD_ListasDiagCtrl',

	'EntidadesCtrl',
		'Entidades_Campos_ListaConfigCtrl',
		'Entidades_Campos_ImagenConfigCtrl',
		'Entidades_AddColumnsCtrl',
		'Entidades_VerCamposCtrl',
		'Entidades_GridsCtrl',
		'Entidades_GridDiagCtrl',
			'Entidades_GridDiag_PreviewDiagCtrl',
		'Entidades_Grids_TestCtrl',

		'Entidades_EditoresCtrl',
		'Entidades_EditorDiagCtrl',
		'Entidades_EditorConfigDiagCtrl',

		'Entidades_CargadoresCtrl',
		'Entidades_CargadorDiagCtrl',
		
	'VariablesCtrl',
		'VariablesGetDataDiagCtrl',
		'Variables_VariableDiagCtrl',

	'IndicadoresCtrl',
		'Indicadores_AddDiagCtrl',
		'Indicadores_IndicadorDiagCtrl',
		//'Indicadores_IndicadorDiag_ValorMenuCtrl',

	'ScorecardsCtrl',
		'Scorecards_NodoSelectorCtrl',
		'Scorecards_ScorecardDiagCtrl',

	'AppsCtrl',
		'App_ViewCtrl',

	'FuncionesCtrl',
	'ProcesosCtrl',
		'Procesos_MapaNodosDiagCtrl',

	'IngresarDatosCtrl',
	'MisIndicadoresCtrl',
	'MiProcesoCtrl',

	'ConsultasSQLCtrl',

	'Integraciones_SOMACtrl',
	'Integraciones_SolgeinCtrl',
	'Integraciones_RUAFCtrl',
	'Integraciones_EnterpriseCtrl',
	'Integraciones_IkonoCtrl',

	'BotsCtrl',
		'Bot_LogsCtrl'
]);

angular.module('appConfig', [])
.config(['$mdThemingProvider', '$mdIconProvider', '$mdDateLocaleProvider', 'cfpLoadingBarProvider', '$compileProvider', 
	function($mdThemingProvider, $mdIconProvider, $mdDateLocaleProvider, cfpLoadingBarProvider, $compileProvider){

		//Definicion de paletas
		$mdThemingProvider.definePalette('Sea', {
			'50': '#cce0ef',
			'100': '#92bedc',
			'200': '#67a4ce',
			'300': '#3a83b4',
			'400': '#32729d',
			'500': '#2b6186',
			'600': '#24506f',
			'700': '#1c3f58',
			'800': '#152f40',
			'900': '#0d1e29',
			'A100': '#cce0ef',
			'A200': '#92bedc',
			'A400': '#32729d',
			'A700': '#1c3f58',
			'contrastDefaultColor': 'light',
			'contrastDarkColors': '50 100 200 A100 A200'
		});

		$compileProvider.aHrefSanitizationWhitelist(/^\s*(blob|http|https?):/);

		//$mdAriaProvider.disableWarnings();

		$mdThemingProvider.definePalette('Gold', {
			'50': '#ffffff',
			'100': '#fcf3df',
			'200': '#f6e0ad',
			'300': '#efc86c',
			'400': '#ecbe51',
			'500': '#e9b435',
			'600': '#e6aa19',
			'700': '#cb9616',
			'800': '#af8113',
			'900': '#936d10',
			'A100': '#ffffff',
			'A200': '#fcf3df',
			'A400': '#ecbe51',
			'A700': '#cb9616',
			'contrastDefaultColor': 'light',
			'contrastDarkColors': '50 100 200 300 400 500 600 700 800 A100 A200 A400 A700'
		});


		$mdThemingProvider.theme('default')
			.primaryPalette('blue', { 'default' : '900' })
			.accentPalette('yellow', { 'default' : '800' });

		$mdThemingProvider.theme('Money', 'default')
			.primaryPalette('teal', { 'default' : '800' })
			.accentPalette('orange');

		$mdThemingProvider.theme('Ocean', 'default')
			.primaryPalette('Sea')
			.accentPalette('Gold');

		$mdThemingProvider.theme('Danger', 'default')
			.primaryPalette('red')
			.accentPalette('yellow');

		$mdThemingProvider.theme('Snow_White', 'default')
			.primaryPalette('grey', { 'default' : '100' })
			.accentPalette('orange');

		$mdThemingProvider.theme('Greyshade', 'default')
			.primaryPalette('grey', { 'default' : '800' })
			.accentPalette('Gold').dark();

		$mdThemingProvider.theme('Black', 'default')
			.primaryPalette('grey', { 'default' : '900' })
			.accentPalette('Gold').dark();

		$mdThemingProvider.theme('Transparent', 'default')
			.primaryPalette('grey', { 'default' : '900' })
			.accentPalette('grey').dark();

		$mdThemingProvider.theme('Valak', 'default')
			.primaryPalette('blue-grey', { 'default' : '700' })
			.accentPalette('red');

		//Calendar
		$mdDateLocaleProvider.months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' ];
		$mdDateLocaleProvider.shortMonths = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
		$mdDateLocaleProvider.days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado' ];
		$mdDateLocaleProvider.shortDays = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

		$mdDateLocaleProvider.parseDate = function(dateString) {
			//console.log('Parsing Date...', dateString);
			var m = moment(dateString);
			return m.isValid() ? m.toDate() : new Date(NaN);
		};

		$mdDateLocaleProvider.formatDate = function(date) {
			if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){
				return null;
			}else{
				//console.log(date, moment(date).toDate());
				return moment(date).utc().format('YYYY-MM-DD');
			}
		};

		$mdDateLocaleProvider.firstDayOfWeek = 1;

		// In addition to date display, date components also need localized messages
		// for aria-labels for screen-reader users.
		$mdDateLocaleProvider.weekNumberFormatter = function(weekNumber) {
			return 'Semana ' + weekNumber;
		};
		$mdDateLocaleProvider.msgCalendar = 'Calendario';
		$mdDateLocaleProvider.msgOpenCalendar = 'Abrir el Calendario';


		//Loading Bar
		cfpLoadingBarProvider.includeSpinner = false;
		cfpLoadingBarProvider.latencyThreshold = 300;

		var icons = {
			'md-plus' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-close' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
			'md-arrow-back' 	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>',
			'md-arrow-forward'  : '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>',
			'md-apps' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g transform="translate(3, 3)"><circle cx="2" cy="2" r="2"></circle><circle cx="2" cy="9" r="2"></circle><circle cx="2" cy="16" r="2"></circle><circle cx="9" cy="2" r="2"></circle><circle cx="9" cy="9" r="2"></circle><circle cx="16" cy="2" r="2"></circle><circle cx="16" cy="9" r="2"></circle><circle cx="16" cy="16" r="2"></circle><circle cx="9" cy="16" r="2"></circle></g></svg>',
			'md-enter' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M11 5v5.59H7.5l4.5 4.5 4.5-4.5H13V5h-2zm-5 9c0 3.31 2.69 6 6 6s6-2.69 6-6h-2c0 2.21-1.79 4-4 4s-4-1.79-4-4H6z"/></svg>',
			'md-save' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>',
			'md-delete' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-bars' 			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
			'md-more-v' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
			'md-more-h'			: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>',
			'md-search'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-chevron-down' 	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-check'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
			'md-edit'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-settings'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/></svg>',
			'md-reorder'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>',
			'md-drag-handle'	: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ><defs><path id="a" d="M0 0h24v24H0V0z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" d="M20 9H4v2h16V9zM4 15h16v-2H4v2z"/></svg>',
			'md-format-quote'   : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M7.17 17c.51 0 .98-.29 1.2-.74l1.42-2.84c.14-.28.21-.58.21-.89V8c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h2l-1.03 2.06c-.45.89.2 1.94 1.2 1.94zm10 0c.51 0 .98-.29 1.2-.74l1.42-2.84c.14-.28.21-.58.21-.89V8c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h2l-1.03 2.06c-.45.89.2 1.94 1.2 1.94z"/></svg>',
			'md-insert-comment' : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2zm-3 12H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1zm0-3H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1zm0-3H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1z"/></svg>',
			'md-calendar' 		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/><path fill="none" d="M0 0h24v24H0z"/></svg>',
			'md-calendar-event' : '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-time'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M0 0h24v24H0z" fill="none"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>',
			'md-timer'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M15 1H9v2h6V1zm-4 13h2V8h-2v6zm8.03-6.61l1.42-1.42c-.43-.51-.9-.99-1.41-1.41l-1.42 1.42C16.07 4.74 14.12 4 12 4c-4.97 0-9 4.03-9 9s4.02 9 9 9 9-4.03 9-9c0-2.12-.74-4.07-1.97-5.61zM12 20c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>',
			'md-pawn'			: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><path stroke="null" id="svg_1" d="m9.651737,9.946874l-1.073613,0a0.684375,0.684375 0 0 0 -0.684375,0.684375l0,1.36875a0.684375,0.684375 0 0 0 0.684375,0.684375l0.684375,0l0,0.234826c0,1.882031 -0.177082,3.70418 -1.026563,5.240174l7.528126,0c-0.850764,-1.535994 -1.026563,-3.358143 -1.026563,-5.240174l0,-0.234826l0.684375,0a0.684375,0.684375 0 0 0 0.684375,-0.684375l0,-1.36875a0.684375,0.684375 0 0 0 -0.684375,-0.684375l-1.073613,0c1.257111,-0.786176 2.100176,-2.172035 2.100176,-3.764063a4.448438,4.448438 0 0 0 -8.896876,0c0,1.592027 0.843065,2.977887 2.100176,3.764063zm8.507637,9.581251l-12.318751,0a0.684375,0.684375 0 0 0 -0.684375,0.684375l0,1.36875a0.684375,0.684375 0 0 0 0.684375,0.684375l12.318751,0a0.684375,0.684375 0 0 0 0.684375,-0.684375l0,-1.36875a0.684375,0.684375 0 0 0 -0.684375,-0.684375z" fill="currentColor"/></g></svg>',
			'md-toggle-on'		: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M17 7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h10c2.76 0 5-2.24 5-5s-2.24-5-5-5zm0 8c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/><path fill="none" d="M0 0h24v24H0z"/></svg>',
			'md-money'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'my-entero'			: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><text style="cursor: move;" font-weight="bold" stroke="#000" transform="matrix(0.8789344025030082,0,0,0.8789344025030082,-0.006719467772585017,1.6922866269988484) " xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" id="svg_1" y="20.046582" x="6.90954" stroke-width="0" fill="#757575">#</text></g></svg>',
			'my-decimal'		: '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g><text font-weight="bold" stroke="#000" transform="matrix(0.8789344025030082,0,0,0.8789344025030082,-0.006719467772585017,1.6922866269988484) " xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="19" id="svg_1" y="18.308238" x="0.457763" stroke-width="0" fill="#757575">.01</text></g></svg>',
			'md-color'			: '<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M24 0H0v24h24z" fill="none"/><path d="M17.66 7.93L12 2.27 6.34 7.93c-3.12 3.12-3.12 8.19 0 11.31C7.9 20.8 9.95 21.58 12 21.58c2.05 0 4.1-.78 5.66-2.34 3.12-3.12 3.12-8.19 0-11.31zM12 19.59c-1.6 0-3.11-.62-4.24-1.76C6.62 16.69 6 15.19 6 13.59s.62-3.11 1.76-4.24L12 5.1v14.49z"/></svg>',
			'md-list'			: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-list-view'		: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-refresh'		: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-trending-up'	: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>',
			'md-info'			: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>',
			'md-info-outline'	: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>',
			'md-description'	: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>',
			'md-image'			: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M23 18V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2zM8.5 12.5l2.5 3.01L14.5 11l4.5 6H5l3.5-4.5z"/></svg>',
			'md-dns'            : '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zM7 19c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zM7 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>',
			'md-list-alt'       : '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 5v14H5V5h14m1.1-2H3.9c-.5 0-.9.4-.9.9v16.2c0 .4.4.9.9.9h16.2c.4 0 .9-.5.9-.9V3.9c0-.5-.5-.9-.9-.9zM11 7h6v2h-6V7zm0 4h6v2h-6v-2zm0 4h6v2h-6zM7 7h2v2H7zm0 4h2v2H7zm0 4h2v2H7z"/></svg>',
			'md-feedback'		: '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/></svg>'
		};

		iconp = $mdIconProvider.defaultFontSet( 'fa' );

		angular.forEach(icons, function(icon, k) {
			iconp.icon(k, 'data:image/svg+xml, '+icon, 24);
		});
  }
]);


/**
 * Available palettes: red, pink, purple, deep-purple, indigo, blue, light-blue, cyan, teal, 
 * green, light-green, lime, yellow, amber, orange, deep-orange, brown, grey, blue-grey
 */
angular.module('appFunctions', [])
.factory('appFunctions', [ '$rootScope', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$filter', '$window', '$mdPanel', 
	function($rootScope, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $filter, $window, $mdPanel){

		var Rs = $rootScope;

		//State
		Rs.stateChanged = function(){
			Rs.State = $state.current;
			Rs.State.route = $location.path().split('/');

			/*if(Rs.State.route.length > 2){
				Rs.State.tabSelected = Rs.Sections[Rs.State.route[2]]['No'];
			};*/

		};
		Rs.navTo = function(Dir, params){ $state.go(Dir, params); };
		Rs.Refresh = function() { $state.go($state.current, $state.params, {reload: true}); };



		//Helpers
		Rs.def = function(arg, def) {
			return (typeof arg == 'undefined' ? def : arg);
		};

		Rs.getSize = function(obj) {
			if(typeof obj !== "undefined" && typeof obj !== "null"){
				return Object.keys(obj).length;
			}
		};

		Rs.inArray = function (item, array) {
			if(!array) return false;
			return (-1 !== array.indexOf(item));
		};

		Rs.getIndex = function(array, keyval, key){
			var key = Rs.def(key, 'id');
			return $filter('getIndex')(array, keyval, key);
		};

		Rs.updateArray = function(array, newelm, key){
			var key = Rs.def(key, 'id');
			var keyval = newelm[key];
			var I = Rs.getIndex(array, keyval, key);
			array[I] = newelm;
		};

		Rs.removeArrayElm = (array, index) => {
			array.splice(index,1);
		};

		Rs.http = function(url, data, scp, prop, method){
			var method = Rs.def(method, 'POST');
			var data = Rs.def(data, {});
			var prop = Rs.def(prop, false);

			return $q(function(res, rej) {
				$http({
					method: method,
					url: url,
					data: data
				}).then(function(r){
					if(prop) scp[prop] = r.data;
					res(r.data);
				}, function(r){
					Rs.showToast(r.data.Msg, 'Error');
					rej(r.data);
				});
			});
		};

		Rs.found = function(needle, haysack, key, msg, except){
			var except = Rs.def(except, false);
			var Found = false;

			angular.forEach(haysack, function(elm){
				if(elm[key].toUpperCase().trim() == needle.toUpperCase().trim()){
					if(except){
						if(elm[except[0]] != except[1]) Found = true;
					}else{
						Found = true;
					}
				};
			});
			if(Found){
				var msg = Rs.def(msg, needle+' ya existe.');
				if(msg !== '') Rs.showToast(msg, 'Error');
			}
			return Found;
		};

		Rs.prepFields = function(Fields, Model){
			var Model = Rs.def(Model, {});
			angular.forEach(Fields, function(F, i){
				Model[F['Nombre']] = F['Value'];
			});
			return Model;
		};

		Rs.submitForm = (name) => {
			Rs.$broadcast('makeSubmit', {formName: name});
		};

		Rs.download = function(strData, strFileName, strMimeType) {
			var D = document,
			    a = D.createElement("a");
			    strMimeType= strMimeType || "application/octet-stream";

			if (navigator.msSaveBlob) { // IE10
			    return navigator.msSaveBlob(new Blob([strData], {type: strMimeType}), strFileName);
			};

			if ('download' in a) { //html5 A[download]
			    a.href = "data:" + strMimeType + "," + encodeURIComponent(strData);
			    a.setAttribute("download", strFileName);
			    a.innerHTML = "downloading...";
			    D.body.appendChild(a);
			    setTimeout(function() {
			        a.click();
			        D.body.removeChild(a);
			    }, 66);
			    return true;
			};

			//do iframe dataURL download (old ch+FF):
			var f = D.createElement("iframe");
			D.body.appendChild(f);
			f.src = "data:" +  strMimeType   + "," + encodeURIComponent(strData);

			setTimeout(function() {
			    D.body.removeChild(f);
			}, 333);

			return true;
		};

		Rs.parseDate = (string) => {
			if(!string) return null;
			var date = moment(string); if(!date.isValid()) return null;
			return date.toDate();
		}


		//Sidenav
		Rs.toogleSidenav = function(navID){
			$mdSidenav(navID).toggle();
		};



		//Quick Lauch
		Rs.showToast = function(Msg, Type, Delay, Position){
			var Type = Rs.def(Type, 'Normal');
			var Delay = Rs.def(Delay, 5000);
			var Position = Rs.def(Position, 'bottom left')

			var Templates = {
				Normal: '<md-toast class="md-toast-normal"><span flex>' + Msg + '<span></md-toast>',
				Error:  '<md-toast class="md-toast-error"><span flex>' + Msg + '<span></md-toast>',
				Success:  '<md-toast class="md-toast-success"><span flex>' + Msg + '<span></md-toast>',
			};
			return $mdToast.show({
				template: Templates[Type],
				hideDelay: Delay,
				position: Position
			});
		};





		//Dialogs
		Rs.BasicDialog = function(params) {
			var DefConfig = {
				Theme: 'default',
				Flex: 30,
				Title: 'Crear',
				Fields: [
					{ Nombre: 'Nombre',  Value: '', Required: true }
				],
				Confirm: { Text: 'Crear' },
				HasDelete: false,
				controller: 'BasicDialogCtrl',
				templateUrl: '/templates/dialogs/basic-string.html',
				fullscreen: true,
				clickOutsideToClose: true,
				multiple: true,
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.prepFields = (Fields) => {
			var F = {};
			angular.forEach(Fields, (i) => {
				F[i.Nombre] = i.Value;
			});

			return F;
		};

		Rs.ListSelector = function(List, Config, ev){
			var List = Rs.def(List, null);
			var DefConfig = {
				controller: 'ListSelectorCtrl',
				templateUrl: '/templates/dialogs/ListSelector.html',
				clickOutsideToClose: true,
				hasBackdrop: true,
				fullscreen: false,
				parent: null,
				remoteUrl: false,
				remoteMethod: 'POST',
				remoteData: {},
				remoteQuery: false,
				remoteListName: 'Nombre',
				remoteListLogo: false,
				searchPlaceholder: 'Buscar',
			};
			var Config = angular.extend(DefConfig, Config);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config, List: List },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
				parent: Config.parent,
			});
		};

		Rs.TableDialog = (Elements, Config) => {
			var DefConfig = {
				controller: 'TableDialogCtrl',
				templateUrl: '/templates/dialogs/TableSelector.html',
				clickOutsideToClose: true,
				fullscreen: true,
				Theme: 'default', Flex: 95,
				Title: 'Seleccionar',
				primaryId: 'id', pluck: true,
				Columns: [
					{ Nombre: 'id', Desc: 'Id.', numeric: true }
				],
				selected: [], multiple: true,
			};
			var Config = angular.extend(DefConfig, Config);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config, Elements: Elements },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: true,
			});
		}
		
		Rs.Confirm = function(params){
			var DefConfig = {
				Theme: 'default',
				Titulo: '¿Seguro que desea realizar esta acción?',
				Detail: '',
				Buttons: [
					{ Text: 'Ok', Class: 'md-raised md-primary', Value: true }
				],
				Icon: false,
				hasCancel: true,
				CancelText: 'Cancelar',
				controller: 'ConfirmCtrl',
				templateUrl: '/templates/dialogs/confirm.html',
				fullscreen: false,
				clickOutsideToClose: true,
				multiple: true
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.confirmDelete = function(params){
			var DefConfig = {
				Theme: 'Danger',
				Title: '¿Eliminar?',
				Detail: 'Esta acción no se puede deshacer',
				ConfirmText: 'Eliminar',
				controller: 'ConfirmDeleteCtrl',
				templateUrl: '/templates/dialogs/confirm-delete.html',
				fullscreen: false,
				clickOutsideToClose: true,
				multiple: true,
			};

			var Config = angular.extend(DefConfig, params);

			return $mdDialog.show({
				controller: Config.controller,
				templateUrl: Config.templateUrl,
				locals: { Config : Config },
				clickOutsideToClose: Config.clickOutsideToClose,
				fullscreen: Config.fullscreen,
				multiple: Config.multiple,
			});
		};

		Rs.selectIconDiag = () => {
			return $mdDialog.show({
				controller: 'IconSelectDiagCtrl',
				templateUrl: '/templates/dialogs/icon-selector.html',
				clickOutsideToClose: true,
				multiple: true,
			});
		};

		Rs.getItemsVal = (Items, Comparator, Prop) => {
			var Elm = $filter('filter')(Rs[Items],Comparator)[0];
			//console.log(Items,Comparator,Elm);
			return Elm[Prop];
		};



		Rs.FsGet = (arr, ruta, filename, defaultOpen,modeB,skipOrder) => {

			if(!skipOrder){
				var arr = arr.sort((a, b) => {
					var ar = (a[ruta]+'\\'+a[filename]).toLowerCase();
					var br = (b[ruta]+'\\'+b[filename]).toLowerCase();
					return ar > br ? 1 : -1;
				});
			}
			
			var fs = [];
	    	var routes = {};
	    	var defaultOpen = Rs.def(defaultOpen, false);
	    	var modeB       = Rs.def(modeB, false);

	    	angular.forEach(arr, (e) => {
	    		var r = e[ruta];
    			rex = r.split('\\');
    			
    			for (var i = 0; i < rex.length; i++) {
    				for (var n = 0; n <= i; n++) {
    					
    					var subroute = rex.slice(0,n+1).join('\\');
    					if(subroute != "" && !Object.keys(routes).includes(subroute)){
    						routes[subroute] = 0;

    						var parent_route = subroute.split('\\').slice(0, -1).join('\\');
    						
    						var show = defaultOpen || (n <= 1);
    						var open = defaultOpen || (n == 0);
    						
    						if(n > 0) routes[parent_route]++;

    						//if( !modeB || ( modeB && e.children > 0 ) ){
    							fs.push({ i: fs.length, type: 'folder', name: rex[n], depth: n, open: open, show: show, route: subroute });
    						//};

    					};
	    				
    				};
    			};

    			var depth = (r == "") ? 0 : (rex.length);
    			var show = defaultOpen || (depth == 0);

    			if( !modeB || (modeB && e.children == 0) ){
    				fs.push({ i: fs.length, type: 'file', depth: depth, show: show, route: subroute, file: e });
    			};
    			
	    	});

	    	angular.forEach(fs, f => {
	    		f.children = routes[f.route];
	    	});
	    	
	    	return fs;
		};

		Rs.FsOpenFolder = (arr,folder) => {
			folder.open = !folder.open;
			var cont = true;
			angular.forEach(arr, e => {
				if(cont){
					if(e.i > folder.i){
						if(e.depth == folder.depth + 1) e.show = folder.open;
						if(e.depth >  folder.depth + 1) e.show = false;
						if(e.type == 'folder' && e.depth >= folder.depth + 1) e.open = false;
						if(e.type == 'folder' && e.depth == folder.depth) cont = false;
					};
				};
			});
		};

		Rs.FsCalcRoute = (route, newfolder) => {
			//newfolder = newfolder.trim().split('\\').join('');
			if(newfolder == "" || (newfolder.toLowerCase() == route.toLowerCase()) ) return route;
			if(route == "") return newfolder;

			return route + "\\" + newfolder;
		};

		Rs.calcTextColor = (base_color) => {
			if(!base_color) return 'black';
		    var r, g, b, hsp;
		    if(base_color.match(/^rgb/)) {
		        color = base_color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
		        r = color[1]; g = color[2]; b = color[3];
		    }else{
		        color = +("0x" + base_color.slice(1).replace(base_color.length < 5 && /./g, '$&$&'));
		        r = color >> 16;
		        g = color >> 8 & 255;
		        b = color & 255;
		    };
		    
		    // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
		    hsp = Math.sqrt( 0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b) );
		    var textColor = (hsp>127.5) ? 'black' : 'white';
		    //console.log(base_color, hsp, textColor);
		    return textColor;
		};



		Rs.AnioActual = parseInt(moment().subtract(40,'d').format('YYYY'));
		Rs.MesActual  = parseInt(moment().subtract(40,'d').format('MM'));
		Rs.Meses = [
			['01','Ene','Enero'],
			['02','Feb','Febrero'],
			['03','Mar','Marzo'],
			['04','Abr','Abril'],
			['05','May','Mayo'],
			['06','Jun','Junio'],
			['07','Jul','Julio'],
			['08','Ago','Agosto'],
			['09','Sep','Septiembre'],
			['10','Oct','Octubre'],
			['11','Nov','Noviembre'],
			['12','Dic','Diciembre'],
		];
		Rs.PeriodoActual = (Rs.AnioActual * 100) + Rs.MesActual;

		Rs.periodDateLocale = {
			formatDate: (date) => {
				if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){ return null; }else{
					return moment(date).format('YMM');
				}
			}
		};

		Rs.VariablesSistema = [ 'Fecha Actual', 'Hora Actual', 'FechaHora Actual', 'Usuario Logueado' ];

		Rs.formatVal = (d, TipoDato, Decimales) => {
			if(TipoDato == 'Porcentaje') return d3.format('.'+Decimales+'%')(d);
            if(TipoDato == 'Moneda')     return d3.format('$,.'+Decimales)(d);
            return d3.format(',.'+Decimales)(d);
		};

		Rs.getVariableData = (Variables, Tipo) => {
			$mdDialog.show({
				controller: 'VariablesGetDataDiagCtrl',
				templateUrl: '/Frag/Variables.VariablesGetDataDiag',
				locals: { Variables : Variables, Tipo: Tipo },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.viewVariableDiag = (variable_id) => {
			$mdDialog.show({
				controller: 'Variables_VariableDiagCtrl',
				templateUrl: '/Frag/Variables.VariableDiag',
				locals: { variable_id : variable_id },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.viewIndicadorDiag = (indicador_id) => {
			$mdDialog.show({
				controller: 'Indicadores_IndicadorDiagCtrl',
				templateUrl: '/Frag/Indicadores.IndicadorDiag',
				locals: { indicador_id : indicador_id },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};

		Rs.Sentidos = {
			ASC: { desc: 'Mayor Mejor', icon: 'fa-arrow-circle-up' },
			RAN: { desc: 'Mantener en Rango', icon: 'fa-arrow-circle-right' },
			DES: { desc: 'Menor Mejor', icon: 'fa-arrow-circle-down' },
		};

		Rs.viewScorecardDiag = (scorecard_id) => {
			$mdDialog.show({
				controller: 'Scorecards_ScorecardDiagCtrl',
				templateUrl: '/Frag/Scorecards.ScorecardDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				onComplete: (scope, element) => {
					scope.getScorecard(scorecard_id, {});
				}
			});
		};

		Rs.viewVariableMenu = (ev, Variable, Periodo, Val, Fn) => {
			const position = $mdPanel.newPanelPosition().relativeTo(ev.target)
            .addPanelPosition(
                $mdPanel.xPosition.ALIGN_START,
                $mdPanel.yPosition.ALIGN_BOTTOMS 
            );

            $mdPanel.open({
                templateUrl: 'Frag/Indicadores.IndicadorDiag_ValorMenu',
                controller: Indicadores_IndicadorDiag_ValorMenuCtrl, 
                controllerAs: 'Ctrl',
                locals: { Periodo: Periodo, Variable: Variable, Val: Val, Fn: Fn },
                position: position,
                clickOutsideToClose: true,
                escapeToClose: true,
            }).then(a => {
                //console.log(a);
            });
		};

		Rs.changeIcon = (elm, prop) => {
			Rs.selectIconDiag().then(I => {
				if(!I) return;
				elm[prop] = I;
			});
		};


		Rs.queryElm = (searchText, accion) => {
			if(accion == 'Editor') 		var url = '/api/Entidades/editores-search/';
			return Rs.http(url, { searchText: searchText });
		};


		Rs.viewEditorDiag = (editor_id, Obj, Config) => {
			return $mdDialog.show({
				controller: 'Entidades_EditorDiagCtrl',
				templateUrl: '/Frag/Entidades.Entidades_EditorDiag',
				clickOutsideToClose: false, fullscreen: false, multiple: true,
				onComplete: (scope, element) => {
					scope.getEditor(editor_id, Obj, Config);
				}
			});
		};

		Rs.viewCargadorDiag = (cargador_id) => {
			return $mdDialog.show({
				controller: 'Entidades_CargadorDiagCtrl',
				templateUrl: '/Frag/Entidades.Entidades_CargadorDiag',
				clickOutsideToClose: false, fullscreen: false, multiple: true,
				onComplete: (scope, element) => {
					scope.getCargador(cargador_id);
				}
			});
		};

		Rs.viewVariableEditorDiag = (Ctrl) => {
			$mdDialog.show({
				controller: 'VariablesCtrl',
				templateUrl: '/Frag/Variables.VariableEditorDiag',
				clickOutsideToClose: false, fullscreen: true, multiple: true,
				scope: Ctrl, preserveScope: false,
			});
		}

		Rs.openApp = (A) => {
			//console.log('opening...', A);
			var url = Rs.Usuario.Url+"#/a/"+A.Slug;
			var w = screen.availWidth - 5; var h = screen.availHeight;
			//$window.open(url, 'popup', `width=${w},height=${h}`);
			
			if(!angular.isDefined(A.w) || A.w.closed) {
				A.w = window.open(url,"_blank",`menubar=0, scrollbars=0,width=${w},height=${h}`);
			};
			A.w.focus();
			
		};

		Rs.getProcesos = (Ctrl) => {
			if(!Rs.Storage.procesos_updated_at || !Rs.Storage.Procesos){
				return Rs.http('api/Procesos', {}).then(P => {
					Ctrl.Procesos = P;
					Rs.Storage.Procesos = P;
					Rs.Storage.procesos_updated_at = Rs.Usuario.procesos_updated_at;
				});
			}

			Ctrl.Procesos = Rs.Storage.Procesos;
			return Promise.resolve();
			
		}

		return {};
  }
]);
angular.module('appRoutes', [])
.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', 
	function($stateProvider, $urlRouterProvider, $httpProvider){
	
			$stateProvider
					.state('Login', {
						url: '/Login',
						templateUrl: '/Login',
						controller: 'LoginCtrl',
					})
					.state('Home', {
						url: '/Home',
						templateUrl: '/Home',
						controller: 'MainCtrl',
						resolve: {
							promiseObj:  function($rootScope, $localStorage, $http){
								var Rs = $rootScope;
								Rs.Storage = $localStorage;
								return $http.post('api/Usuario/check-token', { token: Rs.Storage.token });
							},
							promiseObj2:  function($http){
								return $http.post('api/Entidades/tipos-campo', {});
							},
							controller: function($rootScope, $localStorage, promiseObj,promiseObj2){
								var Rs = $rootScope;
								Rs.Usuario 		= promiseObj.data;
								Rs.TiposCampo 	= promiseObj2.data;
								$localStorage.token = Rs.Usuario.token;
							}
						},
					})
					.state('Home.Section', {
						url: '/:section',
						templateUrl: function (params) { return '/Home/'+params.section; },
					})
					.state('Home.Section.Subsection', {
						url: '/:subsection',
						templateUrl: function (params) { return '/Home/'+params.section+'/'+params.subsection; },
					})
					.state('App', { 
						url: '/a', templateUrl: '/a',
						resolve: {
							promiseObj:  function($rootScope, $localStorage, $http){
								var Rs = $rootScope;
								Rs.Storage = $localStorage;
								return $http.post('api/Usuario/check-token', { token: Rs.Storage.token });
							},
							controller: function($rootScope, $localStorage, promiseObj){
								var Rs = $rootScope;
								Rs.Usuario 		= promiseObj.data;
								$localStorage.token = Rs.Usuario.token;
							}
						},
						controller: 'MainCtrl'
					})
					.state('App.App', { url: '/:app_id' })
					.state('App.App.Page', { url: '/:page_id' })
					.state('Integration', { url: '/int', templateUrl: '/int' })
					.state('Integration.Integration', { url: '/:int_id' });

			$urlRouterProvider.otherwise('/Home');
			

			$httpProvider.interceptors.push(['$q', '$localStorage', 
				function ($q, $localStorage) {
					return {
						request: function (config) {
							config.headers = config.headers || {};
							if ($localStorage.token) {
								config.headers.token = $localStorage.token;
							}
							//console.log(config);
							//config.url = '/app/public/index.php' + config.url;
							return config;
						},
						response: function (res) {
							return res || $q.when(res);
						},
						responseError: function(rejection) {
						  // do something on error

						  if ([400, 401].indexOf(rejection.status) !== -1) {
							// Handle unauthenticated user.
							//location.reload();
							
							//var r = confirm("Su sesión expiró, por favor ingrese nuevamente");
							$localStorage.returnUrl = location.hash.substr(2);
							location.replace("/#/Login");
						  }

						  return $q.reject(rejection);
						}

					};
				}
			]);
	}
]);