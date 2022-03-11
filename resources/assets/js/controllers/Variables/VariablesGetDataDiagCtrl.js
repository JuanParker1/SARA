angular.module('VariablesGetDataDiagCtrl', [])
.controller('VariablesGetDataDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Variables', 'Tipo', 'Frecuencia',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Variables, Tipo, Frecuencia) {

		console.info('VariablesGetDataDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }
		Ctrl.loading = false;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.PeriodoIni = moment().subtract(1, 'months').toDate();
		Ctrl.PeriodoFin = moment().subtract(1, 'months').toDate();
		Ctrl.Frecuencias = Rs.Frecuencias;
		Ctrl.overwriteValues = true;

		Ctrl.allSelected = true;
		Ctrl.downloadStatus = 'iddle';

		Ctrl.periodDateLocale = Rs.periodDateLocale;
		Ctrl.TipoVar = Tipo || 'Calculado de Entidad';
		Ctrl.Frecuencia = Frecuencia || 1;
		Ctrl.OrderOps = [
			[ ["Ruta","Variable"], "Por Proceso" ],
			[ ["Variable","Ruta"], "Alfabéticamente" ],
		];
		Ctrl.OrderBy = Ctrl.OrderOps[0][0];

		
		Ctrl.getVariables = () => {

			if(Ctrl.loading || Ctrl.downloadStatus != 'iddle') return;

			Ctrl.loading = true;
			Rs.http('api/Variables/get-variables', { 
					ids: Variables, 
					Tipo: Ctrl.TipoVar, 
					Frecuencia: Ctrl.Frecuencia,
					Anio: Ctrl.Anio
				}, Ctrl, 'Variables').then(() => {

				Ctrl.orderVars();

				Ctrl.markAll(true);

				angular.forEach(Ctrl.Variables, v => {
					v.status = 'iddle';
				});

				Ctrl.Meses = Rs.Meses.map(m => ({
					MesNo:    parseInt(m[0]),
					MesCorto: m[1],
					Periodo:  parseInt(Ctrl.Anio + m[0]),
					selected: false
				}));

				Ctrl.calcPeriodos();

				Ctrl.loading = false;

			});
		}

		Ctrl.orderVars = () => {
			Ctrl.Variables = $filter('orderBy')(Ctrl.Variables, Ctrl.OrderBy);
		};

		Ctrl.markAll = (val) => {
			Ctrl.allSelected = val;

			angular.forEach(Ctrl.Variables, v => {
				v.selected = val;
			});
		};

		Ctrl.calcPeriodos = () =>{

			let periodoAct = Ctrl.getPeriodo(Ctrl.PeriodoIni);
			let periodoLim = Ctrl.getPeriodo(Ctrl.PeriodoFin);

			angular.forEach(Ctrl.Meses, m => {
				m.selected = (m.Periodo >= periodoAct) && (m.Periodo <= periodoLim);
			});

			let Periodos = [ periodoAct ]
			while (periodoAct < periodoLim){
				var y = parseInt(periodoAct/100);
				var m = periodoAct - (y*100);
				if(m < 12){
					periodoAct = (y*100) + (m+1);
				}else{
					periodoAct = ((y+1)*100) + 1;
				}

				Periodos.push(periodoAct);
			};
			
			Ctrl.Periodos = Periodos;

			
		};

		Ctrl.getPeriodo = (Fecha) => {
			if(!Fecha) return null;
			return (Fecha.getFullYear() * 100) + (Fecha.getMonth() + 1);
		}

		Ctrl.selectPeriodo = (M) => {
			let PeriodoIni = Ctrl.getPeriodo(Ctrl.PeriodoIni);
			let PeriodoFin = Ctrl.getPeriodo(Ctrl.PeriodoFin);
			
			if(M.Periodo > PeriodoFin){
				Ctrl.PeriodoFin = new Date(Ctrl.Anio, M.MesNo-1);
				Ctrl.calcPeriodos();
			}else if(M.Periodo < PeriodoIni){
				Ctrl.PeriodoIni = new Date(Ctrl.Anio, M.MesNo-1);
				Ctrl.calcPeriodos();
			}else if(M.Periodo >= PeriodoIni && M.Periodo < PeriodoFin){
				Ctrl.PeriodoFin = new Date(Ctrl.Anio, M.MesNo-1);
				Ctrl.calcPeriodos();
			}
		}

		Ctrl.changeAnio = (add) => {
			if(Ctrl.loading || Ctrl.downloadStatus != 'iddle') return;
			Ctrl.Anio += add;
			Ctrl.getVariables();
		}

		Ctrl.eraseData = () => {

			Rs.confirmDelete({
				Title: '¿Borrar los datos existentes?',
				Detail: 'Esta acción no se puede deshacer',
				ConfirmText: 'Borrar',
			}).then(r => {
				if(!r) return;

				var Variables = Ctrl.Variables.filter((e) => {
					return e.selected;
				});

				Rs.http('api/Variables/clear-valores', { Variables: Variables, Periodos: Ctrl.Periodos, Anio: Ctrl.Anio }).then((r) => {
					angular.forEach(r, (v) => {
						var i = Rs.getIndex(Ctrl.Variables, v.id);
						Ctrl.Variables[i].valores = v.valores;
						Ctrl.Variables[i].newValores = null;
						Ctrl.Variables[i].status = 'iddle';
					});
					Rs.showToast('Datos Borrados');
					//Var.newValores = r;
				});

				/*angular.forEach(Ctrl.Variables, (v) => {
					if(Rs.inArray(v.id, Ctrl.selectedRows)){
						if(!angular.isDefined(v.newValores)) v.newValores = {};
						angular.forEach(Ctrl.Periodos, (P) => {
							v.valores[P]    = { val: null, Valor: null };
							v.newValores[P] = { val: null, Valor: null };
						});
					};
				});*/
			});

			
		};

		Ctrl.startDownload = () => {
			if(Ctrl.loading || Ctrl.downloadStatus != 'iddle') return;
			Ctrl.downloadStatus = 'running';
			Ctrl.VarIndex = 0;
			Ctrl.stepDownload();
		};

		Ctrl.stepDownload = () => {
			
			if(Ctrl.downloadStatus !== 'running') return;

			var Var = Ctrl.Variables[Ctrl.VarIndex];
			if(!angular.isDefined(Var)) {
				Ctrl.downloadStatus = 'iddle';
				return;
			}

			if(!Var.selected){
				Ctrl.VarIndex++; 
				return Ctrl.stepDownload();
			}else{
				Var.status = 'downloading';
	
				Rs.http('api/Variables/calc-valores', { Var: Var, Periodos: Ctrl.Periodos }).then((r) => {
					Var.newValores = r;
					Var.status = 'done';
					Ctrl.VarIndex++;
					Ctrl.stepDownload();
				}).catch(err => {
					Var.status = 'error';
					Ctrl.pauseDownload();
				});
			}
		};

		Ctrl.pauseDownload  = () => { Ctrl.downloadStatus = 'paused'; };
		Ctrl.resumeDownload = () => { Ctrl.downloadStatus = 'running'; Ctrl.stepDownload(); };
		Ctrl.stopDownload   = () => { Ctrl.downloadStatus = 'iddle'; };

		Ctrl.storeVars = () => {
			var Variables = Ctrl.Variables.filter((e) => {
				return e.selected;
			});

			Rs.http('api/Variables/store-valores', { Variables: Variables, Periodos: Ctrl.Periodos, overwriteValues: Ctrl.overwriteValues, Anio: Ctrl.Anio }).then((r) => {
				angular.forEach(r, (v) => {
					var i = Rs.getIndex(Ctrl.Variables, v.id);
					v.status = 'iddle';
					v.selected = true;
					Ctrl.Variables[i].valores = v.valores;
					Ctrl.Variables[i].newValores = null;
					Ctrl.Variables[i].status = 'iddle';
				});
				Rs.showToast('Datos Guardados', 'Success');
				//Var.newValores = r;
			});
		};

		Ctrl.getVariables();
		
	}
]);

