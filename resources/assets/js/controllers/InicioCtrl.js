angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', '$filter', '$mdMedia', '$window',
	function($scope, $rootScope, $filter, $mdMedia, $window) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.showOtherApps = false;

		//Rs.mainTheme = 'Snow_White';
		Rs.mainTheme = 'Black';
		if(!('InicioSidenav' in Rs.Storage)) Rs.Storage.InicioSidenav = $mdMedia('min-width: 750px');

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
				Ctrl.openUrl("#/a/" + R.Slug);
				//href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_blank"
			};
			if(R.Tipo == 'Indicador') return Rs.viewIndicadorDiag(R.id);
			if(R.Tipo == 'Variable')  return Rs.viewVariableDiag(R.id);
		};

		Ctrl.openUrl = (Url, target = '_blank', reload_favorites = false) => {
			//window.open(Url,'popup','width=1220,height=700');
			$window.open(Url, target);
			if(reload_favorites){
				$window.setTimeout(() => {
					Ctrl.getFavorites();
				}, 1000);
			}
		};

		Ctrl.makeFavorite = async (App,make) => {
			let A = Rs.Usuario.Apps.find(da_app => da_app.id == App.id);
			App.favorito = make;
			A.favorito = make;
			await Rs.http('api/App/favorito', { usuario_id: Rs.Usuario.id, app_id: A.id, favorito: make });
			Ctrl.countFavorites(false);
		};

		Ctrl.countFavorites = (firstLoad) => {
			Ctrl.cantFavorites = Rs.Usuario.Apps.filter(A => A.favorito).length;
			if(firstLoad){
				Ctrl.showOtherApps = Ctrl.cantFavorites == 0;
			}else{
				if(Ctrl.cantFavorites == 0) Ctrl.showOtherApps = true;
			}
		};

		Ctrl.countFavorites(true);

		Ctrl.getFavorites = () => {
			Rs.http('api/Main/get-favorites', {}).then(r => {
				Ctrl.Recientes = r.Recientes;
			});
		};

		Ctrl.getFavorites();
	}
]);