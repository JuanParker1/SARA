angular.module('Entidades_CargadorDiagCtrl', [])
.controller('Entidades_CargadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Upload',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Upload) {

		console.info('Entidades_CargadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.Etapa = 'PreLoad';

		Ctrl.TipoArchivo = {
			csv: [],
		};

		Ctrl.getCargador = (cargador_id) => {
			Rs.http('api/Entidades/cargador-get', { cargador_id: cargador_id }, Ctrl, 'Cargador').then(() => {
				
			});
		};

		Ctrl.upload = (file) => {
			if(!file) return;
			Upload.upload({
	            url: 'api/Entidades/cargador-upload',
	            data: {file: file, 'cargador_id': Ctrl.Cargador.id }
	        }).then(function (resp) {
	            Ctrl.Etapa = 'TestLoad';
	            Ctrl.Entidad   = resp.data.entidad;
	            Ctrl.load_data = resp.data.load_data;
	        }, function (resp) {
	            console.log('Error status: ' + resp.status);
	        }, function (evt) {
	            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
	            console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
	        });
		};

	}
]);