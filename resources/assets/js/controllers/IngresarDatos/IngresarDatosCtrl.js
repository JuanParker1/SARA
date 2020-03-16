angular.module('IngresarDatosCtrl', [])
.controller('IngresarDatosCtrl', ['$scope', '$rootScope', '$injector', '$filter',
	function($scope, $rootScope, $injector, $filter) {

		console.info('IngresarDatosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';
		

		Ctrl.ProcesoSel = false;
		Ctrl.Anio  = angular.copy(Rs.AnioActual);
		Ctrl.Mes   = angular.copy(Rs.MesActual);
		Ctrl.filterVariablesText = '';
		Ctrl.tipoVariableSel = 'Manual';
		Ctrl.TiposVariables = {
			'Manual': { Nombre: 'Manuales' },
			'Valor Fijo': { Nombre: 'Valores Fijos' },
			'Calculado de Entidad': { Nombre: 'Automáticas' },
		};
		Ctrl.Loading = true;

		Ctrl.anioAdd = (num) => {Ctrl.Anio = Ctrl.Anio + num; Ctrl.getVariables(); };
		var Variables = [];

		Ctrl.getVariables = () => {
			Ctrl.Loading = true;
			Ctrl.hasEdited = false;
			Rs.http('api/Variables/get-usuario', { Usuario: Rs.Usuario, Anio: Ctrl.Anio }).then((r) => {
				Variables = r;

				var PeriodoAct = (Rs.AnioActual*100) + Rs.MesActual;
				var PeriodoAnt = parseInt(moment().add(-1, 'month').format('YYYYMM'));

				Variables.forEach(V => {
					//console.log(V.valores);

					Rs.Meses.forEach(M => {
						var Periodo = Ctrl.Anio + M[0];
						if(!V.valores[Periodo]){
							V.valores[Periodo] = { 'val': null, 'Valor': null, 'new_Valor': null, 'edited': false, 'readonly': false };
						}else{
							V.valores[Periodo]['new_Valor'] = V.valores[Periodo]['Valor'];
							V.valores[Periodo]['edited'] = false;
							V.valores[Periodo]['readonly'] = (Periodo < PeriodoAnt);
						};

						if(V.Tipo == 'Manual') V.valores[Periodo]['readonly'] = false;
						
						//if(Periodo >= PeriodoAct) V.valores[Periodo]['readonly'] = true;
					});
				});

				Ctrl.filterVariables();
			});
		};

		Ctrl.getVariables();

		Ctrl.filteredVariables = [];
		Ctrl.filterVariables = () => {
			var Vars = angular.copy(Variables);
			
			if(Ctrl.tipoVariableSel){
				Vars = $filter('filter')(Vars, { Tipo: Ctrl.tipoVariableSel }, true);
			}

			if(Ctrl.ProcesoSel){ 
				Vars = $filter('filter')(Vars, { proceso_id: Ctrl.ProcesoSel }, true);
			}

			if(Ctrl.filterVariablesText.trim() !== ''){
				Vars = $filter('filter')(Vars, Ctrl.filterVariablesText);
			}

			Ctrl.filteredVariables = Vars;
			Ctrl.Loading = false;
		}

		Ctrl.hasEdited = false;
		Ctrl.markChanged = (VP) => {
			VP.edited = true;
			Ctrl.hasEdited = true;
		}

		Ctrl.saveVariables = () => {
			var VariablesValores = [];

			Ctrl.filteredVariables.forEach(V => {

				Rs.Meses.forEach(M => {
					var Periodo = Ctrl.Anio + M[0];
					var VP = V.valores[Periodo];
					if(VP.edited){
						VariablesValores.push({
							variable_id: V.id,
							Periodo: parseInt(Periodo),
							Valor: VP.new_Valor,
							usuario_id: Rs.Usuario.id
						});
					}
				});

			});

			Rs.http('api/Variables/store-all', { VariablesValores: VariablesValores }).then(() => {
				Ctrl.getVariables();
			});
		};

	}
]);