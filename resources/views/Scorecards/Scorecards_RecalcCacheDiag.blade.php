<md-dialog class="vh95" flex=100>

	<div layout layout-align="center center" class="">
		<div class="md-title text-14px padding-left" flex>Refrescar Caché: {{ ScoSel.Titulo }} ({{ Nodos.length }} Indicadores)</div>
		<md-button class="md-icon-button no-margin no-padding focus-on-hover s30" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div layout layout-align="center center" class="padding-0-10">
		
		<md-input-container class="margin-top margin-right">
			<label>Año</label>
			<md-select ng-model="Anio">
				<md-option ng-repeat="A in Anios" ng-value="A">{{ A }}</md-option>
			</md-select>
		</md-input-container>

		<div flex layout layout-align="center center" ng-if="Status == 'Iddle'">
			
			<md-button class="md-icon-button md-raised md-primary no-margin" ng-click="startRefresh()">
				<md-icon md-font-icon="fa-play fa-fw"></md-icon>
			</md-button>

			<span flex ></span>

		</div>

		<div flex layout layout-align="center center" ng-if="Status == 'Running'">
			
			<md-button class="md-icon-button md-raised no-margin" ng-click="stopRefresh()">
				<md-icon md-font-icon="fa-stop fa-fw"></md-icon>
			</md-button>

			<div class="md-title margin-0-10">{{ (CurrIndex/(Nodos.length - 1)) | percentage:1 }}</div>

			<md-progress-linear flex value="{{ (CurrIndex/(Nodos.length - 1))*100 }}"></md-progress-linear>
			
		</div>


	</div>

	<div layout=column flex class="overflow-y darkScroll">
		<md-table-container class="">
			<table md-table class="md-table-short table-col-compress" 
				md-row-select="true" ng-model="selectedRow">
				<thead md-head class="">
					<th></th>
					<th class="text-left">Proceso</th>
					<th class="text-left">Indicador</th>
					<th class="text-left">Actualizado</th>
					<th class="text-left">Nodo</th>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="N in Nodos" md-select="N" md-select-id="id" ng-class="{ 'bg-lightgreen': N.done }">
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="md-cell-compress">{{ N.elemento.proceso.Proceso }}</td>
						<td md-cell class="md-cell-compress">{{ N.elemento.Indicador }}</td>
						<td md-cell class="md-cell-compress">{{ N.elemento.updated_at }}</td>
						<td md-cell class="md-cell-compress">{{ N.ruta_fixed }}</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>
	</div>

</md-dialog>