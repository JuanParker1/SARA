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

		
	}
]);

