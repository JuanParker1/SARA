<div class="bg-white border border-radius">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Fórmula: </div>
		<md-input-container class="no-margin no-padding md-no-underline" flex>
			<input type="text" ng-model="IndSel.Formula" aria-label=s class="text-bold">
		</md-input-container>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right focus-on-hover" aria-label="b" ng-click="searchComponente()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Componente</md-tooltip>
		</md-button>
	</div>
	<div layout=column>
		<div ng-repeat="V in IndicadoresVarsCRUD.rows" layout class="border-top h45 padding-0-5" layout-align="center center">

			<md-input-container class=" w30 no-margin md-no-underline" >
				<input type="text" ng-model="V.Letra" class="text-bold text-20px text-center" ng-blur="IndicadoresVarsCRUD.update(V)">
			</md-input-container>
			<div layout=column ng-repeat="Var in VariablesCRUD.rows | filter:{ id: V.variable_id }:true" flex ng-if="V.Tipo == 'Variable'">
				<div layout>{{ Var.Variable }}</div>
				<div class="text-clear text-14px">{{ Var.proceso.Proceso }}</div>
			</div>
			<div layout=column ng-repeat="Ind in IndicadoresCRUD.rows | filter:{ id: V.variable_id }:true" flex ng-if="V.Tipo == 'Indicador'">
				<div layout>{{ Ind.Indicador }} <div class="minipill margin-left-5">Indicador</div></div>
				<div class="text-clear text-14px">{{ Ind.proceso.Proceso }}</div>
			</div>
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin focus-on-hover" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content class="no-padding">
					<md-menu-item><md-button ng-click="openComponente(V)"><md-icon md-font-icon="fa-external-link-alt margin-right fa-fw"></md-icon>Editar {{ V.Tipo }}</md-button></md-menu-item>
					<md-menu-item ng-if="V.Tipo == 'Variable'"><md-button ng-click="convertIndicador(V.variable_id)"><md-icon md-font-icon="fa-chart-line margin-right fa-fw"></md-icon>Convertir en Indicador</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="deleteComponente(V)" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Remover {{ V.Tipo }}</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
		</div>
	</div>
</div>