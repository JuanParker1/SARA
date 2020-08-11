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