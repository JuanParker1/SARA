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
	
	<md-table-container class="" ng-if="NodoSel.indicadores.length > 0" >
		<table md-table class="md-table-short table-col-compress" md-row-select multiple ng-model="NodosSelected">
			<thead md-head>
			</thead>
			<tbody md-body as-sortable=dragListener2 ng-model="NodoSel.indicadores">
				<tr md-row class="" ng-repeat="C in NodoSel.indicadores" ng-class="{'bg-yellow': C.changed}" as-sortable-item
					md-select="C" md-select-id="id">
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding drag-handle" aria-label="b" as-sortable-item-handle>
							<md-icon md-svg-icon="md-drag-handle"></md-icon>
						</md-button>
					</td>
					<td md-cell class="md-cell-compress">
						<div class="w100p" layout>
							<div class="w100p" ng-if="C.tipo == 'Variable'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</div>
							<div class="w100p" ng-if="C.tipo == 'Indicador'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Indicador</div>
						</div>
					</td>
					<td md-cell class="md-cell-compress">
						<div class="w100p" ng-if="C.tipo == 'Indicador'"><span class="text-clear">{{ C.elemento.proceso.Proceso }}&nbsp;&nbsp;</span>{{ C.elemento.Indicador }}</div>
						<div class="w100p" ng-if="C.tipo == 'Variable'"> <span class="text-clear">{{ C.elemento.proceso.Proceso }}&nbsp;&nbsp;</span>{{ C.elemento.Variable }}</div>
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
						
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
	<div layout class="padding-5" ng-show="NodosSelected.length > 0">
		<md-button class="md-raised no-margin" ng-click="moveNodosInd()">Mover {{ NodosSelected.length }}</md-button>
		<div class="w10"></div>
		<md-button class="md-raised md-warn no-margin" ng-click="deleteNodosInd()">Eliminar {{ NodosSelected.length }}</md-button>
	</div>
</div>

<!--
<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="delIndicador(C)">
	<md-tooltip md-direction=left>Eliminar</md-tooltip>
	<md-icon md-svg-icon="md-close"></md-icon>
</md-button>
-->