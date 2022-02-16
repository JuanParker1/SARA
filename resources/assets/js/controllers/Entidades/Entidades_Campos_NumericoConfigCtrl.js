angular.module('Entidades_Campos_NumericoConfigCtrl', [])
.controller('Entidades_Campos_NumericoConfigCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'C',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, C) {

		console.info('Entidades_Campos_NumericoConfigCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.inArray  = Rs.inArray;
		Ctrl.C = angular.copy(C);

		var ConfigDefault = {
			use_alerts: false,
			alerts: [
				{ upto:  50, color: '#ff2626' },
				{ upto:  80, color: '#ffac00' },
				{ upto: 100, color: '#40d802' }
			]
		};

		//console.log(Rs.Usuario);

		Ctrl.C.Config = angular.extend({}, ConfigDefault, C.Config);

		Ctrl.reorderAlertas = () => {
			Ctrl.C.Config.alerts = $filter('orderBy')(Ctrl.C.Config.alerts, 'upto');
		};

		Ctrl.removeAlerta = (kA) => { Ctrl.C.Config.alerts.splice(kA,1); }

		Ctrl.addAlerta = () => {
			let MaxNum = 0;
			angular.forEach(Ctrl.C.Config.alerts, (A) => {
				MaxNum = Math.max(A.upto);
			});

			let RandColor = Math.floor(Math.random()*16777215).toString(16);

			Ctrl.C.Config.alerts.push({
				upto: (MaxNum+1),
				color: "#" + RandColor
			});
		};

		Ctrl.guardarConfig = () => {
			$mdDialog.hide(Ctrl.C);
		};

		Ctrl.reorderAlertas();

	}
]);