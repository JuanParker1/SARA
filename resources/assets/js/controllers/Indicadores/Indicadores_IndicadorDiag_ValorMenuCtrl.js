function Indicadores_IndicadorDiag_ValorMenuCtrl(mdPanelRef, Periodo, Variable, Val, Fn, $rootScope, $http){

	var Ctrl = this;
	var Rs = $rootScope;

	Ctrl.viewVariableDiag = (variable_id) => {
		mdPanelRef.close();
		Rs.viewVariableDiag(variable_id);
	};

	Ctrl.Periodo = Periodo;
	Ctrl.PeriodoDesc = Rs.Meses[parseInt(Periodo.substr(-2)) - 1][1] +' '+ parseInt(Periodo/100);
	Ctrl.Variable = Variable;


	Ctrl.Val = Val;
	Ctrl.Valor = Val.Valor;
	Ctrl.changed = false;
	Ctrl.editable = false;

	if(Rs.Usuario.isGod) Ctrl.editable = true;

	if(Variable.Tipo == 'Manual' && Periodo >= Rs.PeriodoActual) Ctrl.editable = true;

	Ctrl.updateValor = () => {

		var VariableValor = {
			variable_id: Variable.id,
			Periodo: Periodo,
			Valor: Ctrl.Valor
		};

		$http.post('api/Variables/update-valor', VariableValor).then(() => {
			mdPanelRef.close();
			if(Fn) Fn();
		});
	};

}