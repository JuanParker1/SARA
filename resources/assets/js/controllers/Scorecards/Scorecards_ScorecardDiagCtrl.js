angular.module('Scorecards_ScorecardDiagCtrl', [])
.controller('Scorecards_ScorecardDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', '$localStorage',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, $localStorage) {

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
        Ctrl.Loading = true;


        //Sidenav
        Ctrl.sidenavSel = null;
        Ctrl.SidenavIcons = [
			['fa-filter', 	'Filtros'		,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};

		//Filtros
        Ctrl.filters = {
        	proceso_ruta: false
        };

		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		if(!$localStorage['ScorecardModo']) $localStorage['ScorecardModo'] = 'Año';
		Ctrl.Modo  = $localStorage['ScorecardModo'];
		Ctrl.Modos = {
			'Mes': ['Vista Mensual', 'md-calendar-event'],
			'Año': ['Vista Anual', 'md-calendar'],
		};
		Ctrl.changeModo = () => {
			Ctrl.Modo = (Ctrl.Modo == "Mes") ? 'Año' : 'Mes';
			$localStorage['ScorecardModo'] = Ctrl.Modo;
		};

		Ctrl.periodoAdd = (num) => {
			var m = angular.copy(Ctrl.Mes) + num;
			if(m == 0) { m = 12; Ctrl.anioAdd(-1); }
			if(m == 13){ m =  1; Ctrl.anioAdd( 1); }
			Ctrl.Mes = m;
		};

		Ctrl.anioAdd = (num) => {
			Ctrl.Anio = Ctrl.Anio + num;
			Ctrl.getScorecard(Ctrl.Sco.id);
		};

        Ctrl.Secciones = [];

        Ctrl.Periodo = moment().toDate();

		Ctrl.getScorecard = (scorecard_id, Config) => {
			if(!scorecard_id) return;
			Ctrl.Loading = true;

			

            return Rs.http('api/Scorecards/get-procesos', { id: scorecard_id }).then(Procesos => {
            	Ctrl.ProcesosFS = Rs.FsGet(Procesos, 'Ruta','Proceso', false, true);

            	if('proceso_id' in Config){
					if(Config.proceso_id !== null){
						var ProcesoSel = Procesos.find( p => p.id == Config.proceso_id );
						if(ProcesoSel){
							Ctrl.filters.proceso_ruta = ProcesoSel.Ruta;
						}
					}
				}

				Rs.http('api/Scorecards/get', { id: scorecard_id, Anio: Ctrl.Anio, filters: Ctrl.filters }, Ctrl, 'Sco').then(() => {
	            	Ctrl.Loading = false;

	            	Ctrl.SidenavIcons[0][2] = (typeof  Ctrl.filters.proceso_ruta === 'string');
	            });

            });

            
		};

		Ctrl.openFlatLevel = (N, ev) => {
			ev.stopPropagation();
			if(N.tipo !== 'Nodo') return Ctrl.decideAction(N);

			N.open = !N.open;

			//var cont = true;
			angular.forEach(Ctrl.Sco.nodos_flat, (nodo) => {

				var hijo = nodo.ruta.startsWith(N.ruta) && nodo.depth > N.depth;

				if(hijo){
					if(nodo.depth == N.depth + 1){ nodo.show = N.open; nodo.open = false; }
					else{
						nodo.show = nodo.open = false;
					}
				}
			});
		}

		Ctrl.decideAction = (N) => {
			if(N.tipo == 'Indicador'){
				Rs.viewIndicadorDiag(N.elemento.id);
			}else if(N.tipo == 'Variable'){
				Rs.viewVariableDiag(N.elemento.id);
			}
		};

		//Filtros
		Ctrl.lookupProceso = (F) => {
			Ctrl.filters.proceso_ruta = F.route;
		}

        //Ctrl.getScorecard();
	}
]);
