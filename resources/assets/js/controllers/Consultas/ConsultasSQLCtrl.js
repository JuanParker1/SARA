angular.module('ConsultasSQLCtrl', [])
.controller('ConsultasSQLCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('ConsultasSQLCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.FechaIni = moment().add(-4, 'days').format('YYYY-MM-DD');
		Ctrl.FechaFin = moment().format('YYYY-MM-DD');

		Ctrl.FechaAct = angular.copy(Ctrl.FechaIni);

		Ctrl.Consultas = [
			{ Nombre: 'Ejecución PGP', url: '/api/ConsultasSQL/pgp-nt' },
		];
		Ctrl.ConsultaSel = Ctrl.Consultas[0];

		Ctrl.Status = 'Stopped';

		Ctrl.Go = () => {
			Ctrl.Status = 'Playing';
			Ctrl.Step();
		};

		Ctrl.Pause = () => {
			Ctrl.Status = 'Paused';
		};

		Ctrl.Stop = () => {
			Ctrl.Status = 'Stopped';
			Ctrl.FechaAct = moment(angular.copy(Ctrl.FechaIni)).format('YYYY-MM-DD');
			Ctrl.Report = [];
		};

		Ctrl.Report = [];

		Ctrl.Step = () => {
			var startTime = performance.now();

			Rs.http(Ctrl.ConsultaSel.url, { Dia: Ctrl.FechaAct }).then(r => {
				
				if(Ctrl.Status == 'Playing'){

					var endTime = performance.now();
					var timeDiff = (endTime - startTime) / 1000; 
					var seconds = Math.round(timeDiff);
					
					Ctrl.Report.unshift({ Dia: Ctrl.FechaAct, Tiempo: seconds, mensaje: r.mensaje });

					if(Ctrl.FechaAct == Ctrl.FechaFin) return Ctrl.Pause();

					var NewDay = moment(Ctrl.FechaAct).add(1, 'day').format('YYYY-MM-DD');
					Ctrl.FechaAct =  NewDay;

					Ctrl.Step();
				}

			}).catch(r => {
				Ctrl.Status = 'Paused';

				var endTime = performance.now();
				var timeDiff = (endTime - startTime) / 1000; 
				var seconds = Math.round(timeDiff);
					
				Ctrl.Report.unshift({ Dia: Ctrl.FechaAct, Tiempo: seconds, mensaje: r.Msg });

			});
		}

		Ctrl.adjustToday = (Fecha) => {
			Ctrl.FechaAct = moment(Fecha).format('YYYY-MM-DD');
		};
		
	}
]);