angular.module('Integraciones_SOMACtrl', [])
.controller('Integraciones_SOMACtrl', ['$scope', '$rootScope', '$http',
	function($scope, $rootScope, $http) {

		console.info('Integraciones_SOMACtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		var Inicio = new Date(); Inicio.setDate( Inicio.getDate() - 3 );
		var Hoy = new Date();
		console.log(Inicio);

		Ctrl.filters = {
			Tipo: 'GCFR',
			Desde: Inicio
		};

		Ctrl.downloadFile = () => {

			$http.post('/api/Integraciones/soma', Ctrl.filters, { responseType: 'arraybuffer' }).then(function(r) {
        		var blob = new Blob([r.data], { type: "text/plain" });
		        var filename = moment(Ctrl.filters.Desde).format('YYYYMMDD') + '_' + Ctrl.filters.Tipo + '.txt';
		        //console.log(r.data, filename);
		        saveAs(blob, filename);
        	});
		};

		Ctrl.sendSoma = () => {

			$http.post('/api/Integraciones/soma-send', Ctrl.filters).then(function(r) {
        		console.log(r);
        	});

		}

		//Ctrl.downloadFile();
	}
]);