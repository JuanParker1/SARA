angular.module('VariablesGetDataDiagCtrl', [])
.controller('VariablesGetDataDiagCtrl', ['$scope', '$rootScope', '$mdDialog', '$filter', '$timeout', 'Variables', 'Tipo',
	function($scope, $rootScope, $mdDialog, $filter, $timeout, Variables, Tipo) {

		console.info('VariablesGetDataDiagCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = () => { $mdDialog.cancel(); }

		Ctrl.Meses = Rs.Meses;
		Ctrl.inArray = Rs.inArray;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.PeriodoIni = moment().subtract(1, 'months').toDate();
		Ctrl.PeriodoFin = moment().subtract(1, 'months').toDate();
		Ctrl.Anios = [3,2,1,0].map((n) => { return Ctrl.Anio-n});

		
		Ctrl.overwriteValues = false;

		Ctrl.periodDateLocale = Rs.periodDateLocale;
		Ctrl.TipoVar = Tipo || 'Calculado de Entidad';
		
		Ctrl.getVariables = () => {
			Rs.http('api/Variables/get-variables', { ids: Variables, Tipo: Ctrl.TipoVar }, Ctrl, 'Variables').then(() => {
				Ctrl.selectedRows = Ctrl.Variables.map( v => v.id );
			});
		}

		

		Ctrl.calcPeriodos = () =>{
			var periodoAct = parseInt(moment(Ctrl.PeriodoIni).format('YMM'));
			var periodoLim = parseInt(moment(Ctrl.PeriodoFin).format('YMM'));
			Ctrl.Periodos = [ periodoAct ];
			while (periodoAct < periodoLim){
				var y = parseInt(periodoAct/100);
				var m = periodoAct - (y*100);

				if(m < 12){
					periodoAct = (y*100) + (m+1);
				}else{
					periodoAct = ((y+1)*100) + 1;
				}

				Ctrl.Periodos.push(periodoAct);
			};
		};

		Ctrl.cellSelected = (V,M) => {
			if(V){
				var Selected = Rs.inArray(V.id, Ctrl.selectedRows);
				if(!Selected) return false;
			};
			var PeriodoCell = Ctrl.Anio*100 + parseInt(M[0]);
			return Rs.inArray(PeriodoCell, Ctrl.Periodos);
		};

		Ctrl.eraseData = () => {
			angular.forEach(Ctrl.Variables, (v) => {
				if(Rs.inArray(v.id, Ctrl.selectedRows)){
					if(!angular.isDefined(v.newValores)) v.newValores = {};
					angular.forEach(Ctrl.Periodos, (P) => {
						v.valores[P]    = { val: null, Valor: null };
						v.newValores[P] = { val: null, Valor: null };
					});
				};
			});
		};

		Ctrl.startDownload = () => {
			Ctrl.VarIndex = 0;
			Ctrl.stepDownload();
		};

		Ctrl.stepDownload = () => {
			
			var Var = Ctrl.Variables[Ctrl.VarIndex];
			if(!angular.isDefined(Var)) return;
			console.log(Ctrl.VarIndex, Rs.inArray(Var.id, Ctrl.selectedRows));
			if(!Rs.inArray(Var.id, Ctrl.selectedRows)){
				Ctrl.VarIndex++; 
				return Ctrl.stepDownload();
			}else{
				Rs.http('api/Variables/calc-valores', { Var: Var, Periodos: Ctrl.Periodos }).then((r) => {
					Var.newValores = r;
					Ctrl.VarIndex++;
					Ctrl.stepDownload();
				});
			}

			
		};

		Ctrl.storeVars = () => {
			var Variables = Ctrl.Variables.filter((e) => {
				return Rs.inArray(e.id, Ctrl.selectedRows);
			});

			Rs.http('api/Variables/store-valores', { Variables: Variables, Periodos: Ctrl.Periodos, overwriteValues: Ctrl.overwriteValues }).then((r) => {
				angular.forEach(r, (v) => {
					var i = Rs.getIndex(Ctrl.Variables, v.id);
					Ctrl.Variables[i] = v;
				});
				//Var.newValores = r;
			});
		};

		Ctrl.getVariables();
		Ctrl.calcPeriodos();
	}
]);

