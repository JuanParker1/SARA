angular.module('Entidades_CargadorDiagCtrl', [])
.controller('Entidades_CargadorDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Upload',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Upload) {

		console.info('Entidades_CargadorDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); };
		Ctrl.ExcludeTipos = ['Sin Valor','Variable de Sistema'];
		Ctrl.Etapa = 'PreLoad';
		Ctrl.pag_pages = 50;
		Ctrl.pag_from  = 0;
		Ctrl.pag_to    = null;
		//Ctrl.Etapa = 'TestLoad';

		var load_data_raw = [];
		Ctrl.load_data_len = 0;

		Ctrl.pag_go = (i) => {
			var from = (Ctrl.pag_from + (Ctrl.pag_pages*i) );
			if(from < 0 || from >= Ctrl.load_data_len) return false;
			Ctrl.pag_from = from;
			Ctrl.pag_to = Math.min((Ctrl.pag_from + Ctrl.pag_pages), (Ctrl.load_data_len));
			Ctrl.load_data = load_data_raw.slice(Ctrl.pag_from, Ctrl.pag_to);
		};

		Ctrl.ConfTipoArchivo = {
			csv: ['text/*'],
		};

		Ctrl.getCargador = (cargador_id) => {
			Rs.http('api/Entidades/cargador-get', { cargador_id: cargador_id }, Ctrl, 'Cargador').then(() => {
				
			});
		};

		Ctrl.upload = (file) => {
			if(!file) return;

			Ctrl.Etapa = 'Loading';

			Upload.upload({
	            url: 'api/Entidades/cargador-upload',
	            data: {file: file, 'cargador_id': Ctrl.Cargador.id }
	        }).then((r) => {
	            Ctrl.Etapa = 'TestLoad';
	            Ctrl.Entidad   = r.data.entidad;
	            load_data_raw = r.data.load_data;

	            Ctrl.pag_from      = 0;
	            Ctrl.load_data_len = load_data_raw.length;
	            Ctrl.pag_go(0);

	        }, (r) => {
	        	Rs.showToast('Ocurrió un error, por favor reintente','Error');
	            Ctrl.Etapa = 'PreLoad';
	        }, (evt) => {
	            //var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
	            //console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
	        });
		};

		Ctrl.sendData = () => {
			Ctrl.Etapa = 'Loading';
			Rs.http('api/Entidades/cargador-insert', { Cargador: Ctrl.Cargador, Entidad: Ctrl.Entidad, load_data: load_data_raw }).then(() => {
				Rs.showToast('Se cargaron '+Ctrl.load_data_len+' registros','Success',7500,'bottom right');
				Ctrl.Etapa = 'PreLoad';
			}, () => {
				Rs.showToast('Ocurrió un error, por favor reintente','Error');
				Ctrl.Etapa = 'TestLoad';
			});
		};

	}
]);