angular.module('Integraciones_RUAFCtrl', [])
.controller('Integraciones_RUAFCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_RUAFCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/ruaf',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.regs + " Registros Cargados";
	        	Ctrl.Status = 'Ended';
	        }).catch((r) => {
	        	Ctrl.EndedMsg = 'Se presentó un error, por favor intente más tarde \n o notifique al área encargada.';
	        	Ctrl.Status = 'Error';
	        });
		}

		Ctrl.ReloadStatus = () => {
			Ctrl.Status = 'Iddle';
			Ctrl.EndedMsg = null;
		}
		
	}
]);