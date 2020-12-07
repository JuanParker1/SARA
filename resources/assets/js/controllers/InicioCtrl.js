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