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
        Ctrl.FsOpenFolder = Rs.FsOpenFolder;
        Ctrl.defaultFrecuencias = Object.keys(Rs.Frecuencias);

        //Sidenav
        Ctrl.sidenavSel = null; //FIX
        Ctrl.SidenavIcons = [
			['fa-filter', 	     					'Filtros'		,false],
			['fa-sign-in-alt fa-rotate-90 fa-lg', 	'Descargar'		,false],
		];
		Ctrl.openSidenavElm = (S) => {
			Ctrl.sidenavSel = (S[1] == Ctrl.sidenavSel) ? null : S[1];
		};

		//Filtros
        Ctrl.filters = {
        	proceso_ruta: false,
        	cumplimiento: false,
        	frecuencia_analisis: ['-1'],
        	see: 'Res'
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
        Ctrl.parsePeriodo = function(dateString) {
			var m = moment(dateString, 'MMM YYYY');
			return m.isValid() ? m.toDate() : new Date(NaN);
		};
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

            	//Ctrl.downloadIndicadores();

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

		Ctrl.checkFrecuenciaAnalisis = () => {
			if(Ctrl.filters.frecuencia_analisis.includes('-1')) Ctrl.filters.frecuencia_analisis = ['-1'];
		}


		//Descarga de Datos
		function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;        
        }

        function excelColName(n) {
			var ordA = 'a'.charCodeAt(0);
			var ordZ = 'z'.charCodeAt(0);
			var len = ordZ - ordA + 1;

			var s = "";
			while(n >= 0) {
				s = String.fromCharCode(n % len + ordA).toUpperCase() + s;
				n = Math.floor(n / len) - 1;
			}
			return s;
		}

		Ctrl.downloadIndicadores = () => {

	        var SheetData = [
	        	['Indicador', 'Proceso', 'Sentido', 'Periodo', 'Meta', 'Real', 'Cumplimiento', 'Peso']
	        ];

	        var Niveles = 0;
	        angular.forEach(Ctrl.Sco.nodos_flat, N => {
	        	if(N.tipo !== 'Nodo'){
	        		let RutaArr = N.ruta.split("\\");
	        		RutaArr.pop();

	        		N.ruta_arr = RutaArr;

	        		Niveles = Math.max(Niveles, RutaArr.length);
	        	}
	        });

	        //Agregar niveles a cabecera
	        for (var i = 1; i <= Niveles; i++) {
	        	SheetData[0].push('Nivel_'+i);
	        }


	        angular.forEach(Ctrl.Sco.nodos_flat, N => {
	        	if(N.tipo !== 'Nodo'){

	        		angular.forEach(N.valores, P => {
						let Fila = [
							N.Nodo,
							N.elemento.proceso.Proceso,
							N.elemento.Sentido,
							P.Periodo,
							P.meta_Valor,
							P.Valor,
							P.cump_porc,
							N.peso
						];

						angular.forEach(N.ruta_arr, RA => {
							Fila.push(RA);
						});

						SheetData.push(Fila);
	        		});
	        	}
	        });

			var wb = XLSX.utils.book_new();
	        wb.Props = {
                Title: "Datos Tablero de Mando "+ Ctrl.Sco.Titulo,
                CreatedDate: new Date()
	        };

			var ws = XLSX.utils.aoa_to_sheet(SheetData);
			var last_cell = excelColName(SheetData[0].length - 1) + (SheetData.length);
			ws['!autofilter'] = { ref: ('A1:'+last_cell) };
	        
	        XLSX.utils.book_append_sheet(wb, ws, "Datos");
	        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
	     
	        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), Ctrl.Sco.Titulo + '_Datos.xlsx');
		}

        //Ctrl.getScorecard();
	}
]);
