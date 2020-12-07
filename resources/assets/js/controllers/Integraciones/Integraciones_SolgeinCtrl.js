angular.module('Integraciones_SolgeinCtrl', [])
.controller('Integraciones_SolgeinCtrl', ['$scope', '$rootScope', '$http', 'Upload', 
	function($scope, $rootScope, $http, Upload) {

		console.info('Integraciones_SolgeinCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Status = 'Iddle';
		//Ctrl.Status = 'Error';
		//Ctrl.EndedMsg = "445 Variables identificadas \n 0 Variables cargadas";

		Ctrl.uploadFile = (file) => {
			if(!file) return false;

			Ctrl.Status = 'Uploading';

			Upload.upload({
	            url: '/api/Integraciones/solgein',
	            data: {file: file}
	        }).then((r) => {
	        	Ctrl.EndedMsg = r.data.variables + " Variables identificadas \n "+ r.data.variables_cargadas +" Valores cargados \n " + r.data.metas_cargadas + " Metas Cargadas";
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