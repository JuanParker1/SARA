<div class="bg-white border border-radius margin-top">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Variables ({{ NodoSel.variables.length }})</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addVariable()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Variable</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="" ng-show="NodoSel.variables.length > 0">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
			</thead>
			<tbody md-body>
				<tr md-row class="" ng-repeat="C in NodoSel.variables | orderBy:'Indice'" ng-class="{'bg-yellow': C.changed}">
					<td md-cell class="md-cell-compress">
						<md-select class="w100p" ng-model="C.tipo" aria-label=s ng-change="C.elemento_id = null; C.changed = true">
							<md-option ng-value="'Variable'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</md-option>
							<md-option ng-value="'Variable'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Variable</md-option>
						</md-select>
					</td>
					<td md-cell class="md-cell-compress">
						<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Variable'" placeholder="Seleccione" ng-change="C.changed = true">
						  <md-option ng-value="Op.id" ng-repeat="Op in VariableesCRUD.rows">
						  	<span class="text-clear">{{ Op.proceso.Proceso }}&nbsp;&nbsp;</span>{{ Op.Variable }}</md-option>
						</md-select>
						<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Variable'" placeholder="Seleccione"  ng-change="C.changed = true">
						  <md-option ng-value="Op.id" ng-repeat="Op in VariablesCRUD.rows">{{ Op.Variable }}</md-option>
						</md-select>
					</td>
					<td md-cell class="h30" layout>
						<span flex></span>
						<md-input-container class="no-margin w50  md-no-underline no-padding h30">
							<md-tooltip md-direction=left>Indice</md-tooltip>
							<input type="number" ng-model="C.Indice" aria-label="s" class="text-right" ng-change="C.changed = true">
						</md-input-container>
						<md-input-container class="no-margin w50  md-no-underline no-padding h30">
							<md-tooltip md-direction=left>Peso</md-tooltip>
							<input type="number" ng-model="C.peso" aria-label="s" class="text-right" ng-change="C.changed = true">
						</md-input-container>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="delVariable(C)">
							<md-tooltip md-direction=left>Eliminar</md-tooltip>
							<md-icon md-svg-icon="md-close"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>