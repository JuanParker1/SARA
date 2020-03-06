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

		Ctrl.openPage = (P) => {
			P.loaded = true;
			if(P.Tipo == 'Scorecard'){ Ctrl.ops.Color = '#2d2d2d'; Ctrl.ops.textcolor = 'white' }
			else{ Ctrl.ops.Color = Ctrl.AppSel.Color; Ctrl.ops.textcolor = Ctrl.AppSel.textcolor };
			Ctrl.ops.general_class = 'app_text_'+Ctrl.ops.textcolor+' app_nav_'+Ctrl.AppSel.Navegacion;
			Ctrl.PageSel = P;
		};

		Ctrl.getIframeUrl = (url) => {
			return $sce.trustAsResourceUrl(url);
		};

		Ctrl.$on("$stateChangeSuccess", () => {
			var app_id = $location.path().split('/')[2];
			if(!app_id || app_id == '') return;
			Rs.http('/api/App/app-get', { app_id: app_id }).then((r) => {
				Ctrl.AppSel = r.App;
				document.title = 'SARA - '+Ctrl.AppSel.Titulo;
				Ctrl.openPage(Ctrl.AppSel.pages[0]);
			});
		});



	}
]);