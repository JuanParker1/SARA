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

		Rs.Frecuencias = {
			0: 'Diario',
			1: 'Mensual',
			2: 'Bimestral',
			3: 'Trimestral',
			4: 'Cuatrimestral',
			6: 'Semestral',
			12: 'Anual'
		};

		Rs.parsePeriodo = function(dateString, format = 'MMM YYYY') {
			if(!dateString) return null;
			var m = moment(dateString, format);
			var date = m.isValid() ? m.toDate() : new Date(NaN);
			console.log('parsePeriodo', date);
			return date;
		};
        Rs.formatPeriodo = (date, format = 'MMM YYYY') => {
        	if(!date) return null;
        	var m = moment(date);
      		return m.isValid() ? m.format(format) : '';
        };

		if (window.self != window.top) {
			$(document.body).addClass("in-iframe");
		}
		
	}
]);

