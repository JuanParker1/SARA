angular.module('Integraciones_SOMACtrl', [])
.controller('Integraciones_SOMACtrl', ['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http) {

		console.info('Integraciones_SOMACtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		var Inicio = new Date(); Inicio.setDate( Inicio.getDate() - 4 );
		var Hoy = new Date();
		Ctrl.Report = [];
		Ctrl.Loading = false;

		Ctrl.filters = {
			Tipo: 'GCFR',
			Desde: Inicio
		};

		Ctrl.downloadFile = () => {

			var startTime = performance.now();
			Ctrl.Loading = true;

			$http.post('/api/Integraciones/soma', Ctrl.filters, { responseType: 'arraybuffer' }).then(function(r) {
        		var blob = new Blob([r.data], { type: "text/plain" });
		        var filename = moment(Ctrl.filters.Desde).format('YYYYMMDD') + '_' + Ctrl.filters.Tipo + '.txt';
		        //console.log(r.data, filename);
		        saveAs(blob, filename);
		        Ctrl.Loading = false;
		        var endTime = performance.now();
				var seconds = Math.round((endTime - startTime) / 1000);
					
				Ctrl.Report.push({ Contrato: Ctrl.filters.Tipo, Dia: moment(Ctrl.filters.Desde).format('YYYY-MM-DD'), Tiempo: seconds, mensaje: `Archivo: "${filename}" generado.` });

        	}).catch(r => {
        		Ctrl.Loading = false;
		        var endTime = performance.now();
				var seconds = Math.round((endTime - startTime) / 1000);
				Ctrl.Report.push({ Contrato: Ctrl.filters.Tipo, Dia: moment(Ctrl.filters.Desde).format('YYYY-MM-DD'), Tiempo: seconds, mensaje: `Error al generar el Archivo` });
        	});
		};

		Ctrl.sendSoma = () => {

			var startTime = performance.now();
			Ctrl.Loading = true;

			$http.post('/api/Integraciones/soma-send', Ctrl.filters).then(function(r) {
        		Ctrl.Loading = false;
		        var endTime = performance.now();
				var seconds = Math.round((endTime - startTime) / 1000);
				Ctrl.Report.push({ Contrato: Ctrl.filters.Tipo, Dia: moment(Ctrl.filters.Desde).format('YYYY-MM-DD'), Tiempo: seconds, mensaje: `Datos Enviados.` });
        	}).catch(r => {
        		Ctrl.Loading = false;
		        var endTime = performance.now();
				var seconds = Math.round((endTime - startTime) / 1000);
				Ctrl.Report.push({ Contrato: Ctrl.filters.Tipo, Dia: moment(Ctrl.filters.Desde).format('YYYY-MM-DD'), Tiempo: seconds, mensaje: `Error: ${r.data.Msg}` });
        	});

		}

		//Ctrl.downloadFile();
	}
]);