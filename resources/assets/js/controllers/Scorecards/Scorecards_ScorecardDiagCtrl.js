angular.module('Scorecards_ScorecardDiagCtrl', [])
.controller('Scorecards_ScorecardDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout',
	function($scope, $rootScope, $mdDialog, $filter, $timeout) {

		console.info('Scorecards_ScorecardDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
        Ctrl.viewVariableDiag = Rs.viewVariableDiag;
        Ctrl.viewIndicadorDiag = Rs.viewIndicadorDiag;
        Ctrl.Sentidos = Rs.Sentidos;
        Ctrl.periodDateLocale = Rs.periodDateLocale;

		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.Modo  = 'Mes';

		Ctrl.periodoAdd = (num) => {
			var m = angular.copy(Ctrl.Mes) + num;
			if(m == 0) { m = 12; Ctrl.anioAdd(-1); }
			if(m == 13){ m =  1; Ctrl.anioAdd( 1); }
			Ctrl.Mes = m;
		};

		Ctrl.anioAdd = (num) => {
			Ctrl.Anio += num;
			Ctrl.getScorecard();
		};

        Ctrl.Secciones = [];

        Ctrl.Periodo = moment().toDate();

		Ctrl.getScorecard = (scorecard_id) => {
			if(!scorecard_id) return;
            Rs.http('api/Indicadores/scorecard-get', { id: scorecard_id, Anio: Ctrl.Anio }, Ctrl, 'Sco').then(() => {
                Ctrl.Secciones = [{ Seccion: null, open: true, cards: $filter('filter')(Ctrl.Sco.cards,{ seccion_name: null }).length }]
                angular.forEach(Ctrl.Sco.Secciones, (s) => {
                	Ctrl.Secciones.push({ Seccion: s, open: true, cards: $filter('filter')(Ctrl.Sco.cards,{ seccion_name: s }).length }); 
                });
            });
		};

        //Ctrl.getScorecard();
	}
]);
