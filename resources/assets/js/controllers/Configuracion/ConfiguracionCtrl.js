angular.module('ConfiguracionCtrl', [])
.controller('ConfiguracionCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 
	function($scope, $rootScope, $http, $injector, $mdDialog) {

		console.info('ConfiguracionCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Black';
		
		Rs.http('api/Main/get-configuracion', {}, Ctrl, 'Configuracion');

		Ctrl.markChanged = (Key) => {
			Ctrl.Configuracion[Key].changed = true;
		}

		Ctrl.saveConf = () => {
			Rs.http('api/Main/save-configuracion', { Conf: Ctrl.Configuracion }).then(() => {
				Rs.showToast('Configuración Guardada', 'Success');
			});
		}

	}
]);