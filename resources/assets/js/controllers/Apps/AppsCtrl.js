angular.module('AppsCtrl', [])
.controller('AppsCtrl', ['$scope', '$rootScope', '$injector', '$http',
	function($scope, $rootScope, $injector, $http) {

		console.info('AppsCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.AppsSidenav = true;

		Ctrl.AppsCRUD = $injector.get('CRUD').config({ base_url: '/api/App/apps' });

		Ctrl.AppsCRUD.get().then(() => {
			if(Ctrl.AppsCRUD.rows.length > 0){
				Ctrl.openApp(Ctrl.AppsCRUD.rows[0]);
			};
		});

		Ctrl.openApp = (A) => {
			Ctrl.AppSel = A;
		};

		Ctrl.updateApp = () => {
			Ctrl.AppsCRUD.update(Ctrl.AppSel);
		};

		Ctrl.changeIcon = () => {
			Rs.selectIconDiag().then(I => {
				if(!I) return;
				//console.log(I);
				Ctrl.AppSel.Icono = I;
			});
		};

		Ctrl.changeTextColor = () => {
			Ctrl.AppSel.textcolor = Rs.calcTextColor(Ctrl.AppSel.Color);
		};

		

	}
]);