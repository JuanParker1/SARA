<div class="bg-white border border-radius margin-top">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right" flex>Variables del Proceso</div>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right focus-on-hover" aria-label="b" ng-click="addVariable()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Variable</md-tooltip>
		</md-button>
	</div>
	<div layout=column>
		<div ng-repeat="Var in VariablesCRUD.rows | filter:{ proceso_id: ProcesoSelId }:true" layout class="border-top h30 padding-right padding-left text-14px" 
			layout-align="center center">
			<div flex>{{ Var.Variable }}</div>
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin focus-on-hover s30 no-padding" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content class="no-padding">
					<md-menu-item><md-button ng-click="addComponente({ Tipo: 'Variable', variable_id: Var.id })"><md-icon md-font-icon="fa-plus margin-right fa-fw"></md-icon>Agregar a Formula</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="openComponente({ Tipo: 'Variable', variable_id: Var.id })"><md-icon md-font-icon="fa-external-link-alt margin-right fa-fw"></md-icon>Editar Variable</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="convertIndicador(Var)"><md-icon md-font-icon="fa-chart-line margin-right fa-fw"></md-icon>Convertir en Indicador</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="deleteVariable(Var)" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Eliminar la Variable</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
		</div>
	</div>
</div>