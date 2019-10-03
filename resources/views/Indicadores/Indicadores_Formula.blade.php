<div class="bg-white border border-radius">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Fórmula: </div>
		<md-input-container class="no-margin no-padding md-no-underline" flex>
			<input type="text" ng-model="IndSel.Formula" aria-label=s class="text-bold">
		</md-input-container>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addVariable()">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Componente</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="">
		<table md-table class="md-table-short table-col-compress">
			<tbody md-body>
				<tr md-row class="" ng-repeat="V in IndicadoresVarsCRUD.rows" ng-class="{'bg-yellow': V.changed}">
					<td md-cell class="md-cell-compress text-bold" style="font-size: 1.2em;line-height: 28px;">{{ V.Letra }} =</td>
					<td md-cell class="md-cell-compress">
						<md-select ng-model="V.Tipo" aria-label=s ng-change="V.variable_id = null; V.changed = true">
							<md-option ng-value="'Variable'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</md-option>
							<md-option ng-value="'Indicador'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Indicador</md-option>
						</md-select>
					</td>
					<td md-cell class="md-cell-compress">
						<div class="w5"></div>
						<md-select ng-model="V.variable_id" aria-label=s ng-show="V.Tipo == 'Variable'"  placeholder="Seleccionar Variable" ng-change="V.changed = true">
							<md-option ng-value="Var.id" ng-repeat="Var in VariablesCRUD.rows">{{ Var.Variable }}</md-option>
						</md-select>
						<md-select ng-model="V.variable_id" aria-label=s ng-show="V.Tipo == 'Indicador'" placeholder="Seleccionar Indicador" ng-change="V.changed = true">
							<md-option ng-value="Ind.id" ng-repeat="Ind in IndicadoresCRUD.rows" ng-if="Ind.id !== IndSel.id">{{ Ind.Indicador }}</md-option>
						</md-select>
					</td>
					<td md-cell>
						<div layout ng-show="V.Tipo == 'Indicador'">
							<md-select ng-model="V.Op1" aria-label=s placeholder="Usar">
								<md-option ng-value="Op.id" ng-repeat="Op in OpsUsar">{{ Op.desc }}</md-option>
							</md-select>
						</div>
					</td>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="delVariable(V)">
							<md-icon md-svg-icon="md-close"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>