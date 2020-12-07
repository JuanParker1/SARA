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
        Ctrl.Procesos = null;

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
        	proceso_ruta: false,
        	cumplimiento: false
        };

        Ctrl.filtrosCumplimiento = [
			[ 'green',    'Verde', 	        '#40d802' ],
			[ 'yellow',   'Amarillo',       '#ffac00' ],
			[ 'red',      'Rojo',           '#ff2626' ],
			[ 'no_value', 'Sin Valor/Meta', '#797979' ],
        ];

		//Ctrl.Anio  = angular.copy(Rs.AnioActual);
		//Ctrl.Mes   = angular.copy(Rs.MesActual);
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

		//Periodo
        Ctrl.PeriodoDate = moment(((Rs.AnioActual*100)+Rs.MesActual), 'YYYYMM').toDate();
        Ctrl.MaxDate = moment().add(1, 'year').endOf("year").toDate();
        Ctrl.formatPeriodo = (date) => {
        	var m = moment(date);
      		return m.isValid() ? m.format('MMM YYYY') : '';
        };
        Ctrl.getPeriodoParts = () => {
        	var m = moment(Ctrl.PeriodoDate);
        	Ctrl.Periodo = m.format('YYYYMM');
        	Ctrl.Mes     = m.format('MM');
        	Ctrl.Anio    = Ctrl.PeriodoDate.getFullYear();
        }
        Ctrl.getPeriodoParts();


		Ctrl.periodoAdd = (num) => {
			Ctrl.PeriodoDate = moment(Ctrl.PeriodoDate).add(num, 'month').toDate();
			Ctrl.getPeriodoParts();
		};

		Ctrl.anioAdd = (num) => {
			Ctrl.PeriodoDate = moment(Ctrl.PeriodoDate).add(num, 'year').toDate();
			Ctrl.getPeriodoParts();
			Ctrl.getScorecard(Ctrl.Sco.id);
		};



        Ctrl.Secciones = [];
        

        Ctrl.getProcesos =  (scorecard_id, Config) => {
        	return Rs.http('api/Scorecards/get-procesos', { id: scorecard_id }, Ctrl, 'Procesos').then(() => {
        		
        		Ctrl.ProcesosFS = Rs.FsGet(Ctrl.Procesos, 'Ruta','Proceso', false, true);

        		if('proceso_id' in Config){
					if(Config.proceso_id !== null){
						Ctrl.ProcesoSel = Ctrl.Procesos.find( p => p.id == Config.proceso_id );
						if(Ctrl.ProcesoSel){
							Ctrl.filters.proceso_ruta = Ctrl.ProcesoSel.Ruta;
						}
					}
				}

        		return Ctrl.getScorecard(scorecard_id, Config);
        	});
        };

		Ctrl.getScorecard = (scorecard_id, Config) => {
			if(!scorecard_id) return;
			Ctrl.Loading = true;
			Ctrl.ProcesoSelName = '';

			if(!Ctrl.Procesos) return Ctrl.getProcesos(scorecard_id, Config);

			Ctrl.filters.Periodo = Ctrl.Periodo;

			Rs.http('api/Scorecards/get', { id: scorecard_id, Anio: Ctrl.Anio, filters: Ctrl.filters }, Ctrl, 'Sco').then(() => {
            	Ctrl.Loading = false;
            	Ctrl.SidenavIcons[0][2] = (typeof  Ctrl.filters.proceso_ruta === 'string');
            	
            	if(Ctrl.filters.proceso_ruta){
            		Ctrl.ProcesoSel = Ctrl.Procesos.find( p => p.Ruta == Ctrl.filters.proceso_ruta )
            		Ctrl.ProcesoSelName = Ctrl.filters.proceso_ruta.split('\\').pop();
            	}

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

		Ctrl.clearCache = () => {
			var nodos_ids = [];
			angular.forEach(Ctrl.Sco.nodos_flat, N => {
				if(N.tipo != 'Nodo') nodos_ids.push(N);
			});

			Rs.http('api/Scorecards/erase-cache', { Inds: nodos_ids }).then(() => {
				//Ctrl.openScorecard(Ctrl.ScoSel, Ctrl.NodoSel);
				Rs.showToast('Caché Borrada', 'Success');
			});
		}

        //Ctrl.getScorecard();
	}
]);
