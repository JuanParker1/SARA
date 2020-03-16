<div class="bg-white border border-radius margin-top">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Indicadores y Variables ({{ NodoSel.indicadores.length }})</div>
		<span flex></span>
		
		<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="addVariable()">
			<md-icon md-font-icon="fa-fw fa-superscript "></md-icon>
			<md-tooltip md-direction="left">Agregar Variable</md-tooltip>
		</md-button>

		<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addIndicador()">
			<md-icon md-font-icon="fa-fw fa-chart-line "></md-icon>
			<md-tooltip md-direction="left">Agregar Indicador</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="" ng-show="NodoSel.indicadores.length > 0">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
			</thead>
			<tbody md-body as-sortable=dragListener2 ng-model="NodoSel.indicadores">
				<tr md-row class="" ng-repeat="C in NodoSel.indicadores" ng-class="{'bg-yellow': C.changed}" as-sortable-item>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding drag-handle" aria-label="b" as-sortable-item-handle>
							<md-icon md-svg-icon="md-drag-handle"></md-icon>
						</md-button>
					</td>
					<td md-cell class="md-cell-compress">
						<md-select class="w100p" ng-model="C.tipo" aria-label=s ng-change="C.elemento_id = null; C.changed = true">
							<md-option ng-value="'Variable'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</md-option>
							<md-option ng-value="'Indicador'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Indicador</md-option>
						</md-select>
					</td>
					<td md-cell class="md-cell-compress">
						<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Indicador'" placeholder="Seleccione" ng-change="C.changed = true">
						  <md-option ng-value="Op.id" ng-repeat="Op in IndicadoresCRUD.rows">
						  	<span class="text-clear">{{ Op.proceso.Proceso }}&nbsp;&nbsp;</span>{{ Op.Indicador }}</md-option>
						</md-select>
						<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Variable'" placeholder="Seleccione"  ng-change="C.changed = true">
						  <md-option ng-value="Op.id" ng-repeat="Op in VariablesCRUD.rows">
						  	<span class="text-clear">{{ Op.proceso.Proceso }}&nbsp;&nbsp;</span>{{ Op.Variable }}
						  </md-option>
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
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="delIndicador(C)">
							<md-tooltip md-direction=left>Eliminar</md-tooltip>
							<md-icon md-svg-icon="md-close"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>