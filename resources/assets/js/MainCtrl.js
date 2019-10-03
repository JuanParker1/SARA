angular.module('MainCtrl', [])
.controller('MainCtrl', ['$rootScope', 'appFunctions', '$http', '$mdDialog', '$mdSidenav', '$mdToast', '$q', '$state', '$location', '$localStorage', '$mdMedia', 
	function($rootScope, appFunctions, $http, $mdDialog, $mdSidenav, $mdToast, $q, $state, $location, $localStorage, $mdMedia) {

		console.info('MainCtrl');
		var Rs = $rootScope;

		Rs.ToogleSidebar = function(nav_id){ $mdSidenav(nav_id).toggle(); }
		Rs.CloseSidebar = function(nav_id){  $mdSidenav(nav_id).close();  }
		Rs.OpenSidebar = function(nav_id){ 	 $mdSidenav(nav_id).open();   }

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

		Rs.AnioActual = new Date().getFullYear();
		Rs.MesActual  = parseInt(moment().subtract(5,'d').format('MM'));
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

		Rs.periodDateLocale = {
			formatDate: (date) => {
				if(typeof date == 'undefined' || date === null || isNaN(date.getTime()) ){ return null; }else{
					return moment(date).format('YMM');
				}
			}
		};

		Rs.formatVal = (d, TipoDato, Decimales) => {
			if(TipoDato == 'Porcentaje') return d3.format('.'+Decimales+'%')(d);
            if(TipoDato == 'Moneda')     return d3.format('$,.'+Decimales)(d);
            return d3.format(',.'+Decimales)(d);
		};

		Rs.getVariableData = (Variables) => {
			$mdDialog.show({
				controller: 'VariablesGetDataDiagCtrl',
				templateUrl: '/Frag/Variables.VariablesGetDataDiag',
				locals: { Variables : Variables },
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
				locals: { scorecard_id : scorecard_id },
				clickOutsideToClose: false, fullscreen: true, multiple: true,
			});
		};
	}
]);

