angular.module('InicioCtrl', [])
.controller('InicioCtrl', ['$scope', '$rootScope', '$filter',
	function($scope, $rootScope, $filter) {

		console.info('InicioCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;


		//Rs.mainTheme = 'Snow_White';
		Rs.mainTheme = 'Black';
		Rs.InicioSidenavOpen = true;

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
			{ Titulo: 'Tableros',    Value: 'Tablero', 		Icono: 'fa-th-large' },
			{ Titulo: 'Indicadores', Value: 'Indicador', 	Icono: 'fa-chart-line' },
			{ Titulo: 'Variables',   Value: 'Variable', 	Icono: 'fa-superscript' },
			{ Titulo: 'Reportes',    Value: 'Reporte', 	    Icono: 'fa-table' },
			{ Titulo: 'Procesos',    Value: 'Proceso', 	    Icono: 'fa-cube' },
			{ Titulo: 'Funciones',   Value: 'Funcion', 	    Icono: 'fa-cube' },
		];
		Ctrl.searchGroupSel = 0;

		Ctrl.mainSearch = () => {
			//Ctrl.searchResults = null;
			if(Ctrl.searchText.trim() == ''){
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
			if(R.Tipo == 'Tablero')   return Rs.viewScorecardDiag(R.id);
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