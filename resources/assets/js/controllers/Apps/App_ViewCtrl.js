angular.module('App_ViewCtrl', [])
.controller('App_ViewCtrl', ['$scope', '$rootScope', '$http', '$location', '$sce', '$filter',
	function($scope, $rootScope, $http, $location, $sce, $filter) {

		console.info('App_ViewCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		
		Ctrl.ops = {
			general_class: ''
		};
		Ctrl.PageSel = null;
		Ctrl.Modo  = 'Mes';
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);

		Ctrl.openPage = (P) => {
			Ctrl.PageSel = P;
		};

		Ctrl.getIframeUrl = (url) => {
			return $sce.trustAsResourceUrl(url);
		};

		Ctrl.$on("$stateChangeSuccess", () => {
			var app_id = $location.path().split('/')[2];
			if(!app_id || app_id == '') return;
			$http.post('/api/App/app-get', { app_id: app_id }).then((r) => {
				Ctrl.AppSel = r.data.App;
				Ctrl.ops.general_class = 'app_text_'+Ctrl.AppSel.textcolor;
				Ctrl.openPage(Ctrl.AppSel.pages[0]);
			});
		});

		$http.post('api/Indicadores/scorecard-get', { id: 1, Anio: Ctrl.Anio }).then((r) => {
			Ctrl.Sco = r.data;
		    Ctrl.Secciones = [{ Seccion: null, open: true, cards: $filter('filter')(Ctrl.Sco.cards,{ seccion_name: null }).length }]
		    angular.forEach(Ctrl.Sco.Secciones, (s) => {
		    	Ctrl.Secciones.push({ Seccion: s, open: true, cards: $filter('filter')(Ctrl.Sco.cards,{ seccion_name: s }).length }); 
		    });
		});

	}
]);