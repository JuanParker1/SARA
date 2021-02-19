<div class="bg-white text-black padding-top padding-bottom border-radius" layout=column md-whiteframe=2>
	
	<div class="margin-bottom padding-0-10" layout=column>
		<div class="text-13px text-clear padding-bottom-5">{{ Ctrl.Variable.Variable }}</div>
		
		<div class="">{{ Ctrl.PeriodoDesc }}</div>
	</div>

	<div class="Pointer h40 relative padding-left" md-ink-ripple layout layout-align="center center" ng-click="Ctrl.viewVariableDiag(Ctrl.Variable.id)">
		<md-icon md-font-icon="fa-chart-line fa-fw fa-ls margin-right"></md-icon><div flex>Ver Gráfico</div>
	</div>
	<div class="Pointer h40 relative padding-left" md-ink-ripple layout layout-align="center center" hide>
		<md-icon md-font-icon="fa-table fa-fw fa-ls margin-right"></md-icon><div flex>Ver Detalle Datos</div>
	</div>

	<div layout class="margin-top-20 padding-0-10">
		<md-input-container class="no-margin md-icon-float w90">
			<label>Valor</label>
			<md-icon md-svg-src="md-edit" style="transform: translate(2px, -10px);"></md-icon>
			<input ng-model="Ctrl.Valor" ng-change="Ctrl.changed = true" type="number" ng-readonly="!Ctrl.editable" ng-disabled="!Ctrl.editable"></input>
		</md-input-container>
		<span flex></span>
		<md-button ng-show="Ctrl.changed && Ctrl.editable" class="md-raised md-primary no-margin" ng-click="Ctrl.updateValor()">Guardar</md-button>
	</div>
	<div class="text-green padding-0-10 text-13px" ng-if="Ctrl.Variable.Tipo == 'Calculado de Entidad'">Calculado Automáticamente</div>

	

</div>