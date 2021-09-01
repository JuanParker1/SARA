<div class="bg-white border border-radius margin-top">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Subnodos ({{ NodoSel.subnodos.length }})</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addNodo(NodoSel)" hide>
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Subnodo</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
			</thead>
			<tbody md-body>
				<tr md-row class="" ng-repeat="N in NodoSel.subnodos" ng-class="{'bg-yellow': N.changed}">
					<td md-cell class="md-cell-compress" ng-click="openNodo(N)">{{ N.Nodo }}</td>
					<td md-cell class="md-cell-compress"></td>
					<td md-cell class="h30" layout>
						<span flex></span>
						<md-input-container class="no-margin w50  md-no-underline no-padding h30">
							<md-tooltip md-direction=left>Indice</md-tooltip>
							<input type="number" ng-model="N.Indice" aria-label="s" class="text-right" ng-change="N.changed = true">
						</md-input-container>
						<md-input-container class="no-margin w50  md-no-underline no-padding h30">
							<md-tooltip md-direction=left>Peso</md-tooltip>
							<input type="number" ng-model="N.peso" aria-label="s" class="text-right" ng-change="N.changed = true">
						</md-input-container>
					</td>
				</tr>
				<tr md-row class="">
					<td md-cell class="">
						<md-input-container class="no-margin md-no-underline w100p" md-no-float>
							<input ng-model="newNodoName" aria-label="s" class="" placeholder="Agregar Subnodo" autocomplete="off" enter-stroke="addNewNodo()">
						</md-input-container>
					</td>
					<td md-cell class="md-cell-compress"></td>
					<td md-cell class="md-cell-compress">
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>