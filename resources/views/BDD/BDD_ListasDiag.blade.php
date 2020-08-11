<md-dialog class="w100p mxw900" style="min-height: 80%" layout>

	<div flex=40 class="" layout=column>
		
		<div class="border-bottom h40 padding-left" layout>

			<md-select ng-model="Config.lista_id" class="md-no-underline no-margin text-14px"
				ng-change="getIndices()">
				<md-option ng-repeat="L in Listas" ng-value="L.id">{{ L.Nombre }}</md-option>
			</md-select>

			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 0;"></md-icon>
				<input flex type="search" placeholder="Buscar Lista..." ng-model="filterListas" class="no-padding" ng-change="searchEntidades()" ng-model-options="{ debounce : 500 }">
			</div>
		</div>

		<md-virtual-repeat-container flex layout=column class="overflow-y darkScroll">
			
			<div md-virtual-repeat="L in Indices | filter:filterListas" layout layout-align="center center" 
				class="relative padding-5-0 border-bottosm h30 Pointer" md-ink-ripple 
				ng-class="{ 'bg-lightgrey': ( L.IndiceCod == IndiceSel.IndiceCod ) }"
				ng-click="openIndice(L)">
				<div class="text-14px margin-0-5 text-bold">{{ L.IndiceCod }}</div>
				<div class="text-14px" flex>{{ L.IndiceDes }}</div>
			</div>

		</md-virtual-repeat-container>

	</div>

	<div flex class="well" layout=column>
		
		<div flex layout=column ng-show="IndiceSel">

			<div class="h40 border-bottom padding-left" layout layout-align="center center">
				<div class="md-title text-16px" flex>{{ IndiceSel.IndiceDes }} ({{ Detalles.length }})</div>
			</div>

			<div flex layout=column class="overflow-y">
				<md-card>
				<md-table-container>
					<table md-table class="md-table-short table-col-compress border-bottom">
						<thead md-head>
							<tr md-row>
								<th md-column>Codigo</th>
								<th md-column>Detalle</th>
							</tr>
						</thead>
						<tbody md-body>
							<tr md-row class="" ng-repeat="D in Detalles">
								<td md-cell class="md-cell-compress">{{ D.DetalleCod }}</td>
								<td md-cell class="">{{ D.DetalleDes }}</td>
							</tr>
						</tbody>
					</table>
				</md-table-container>
				</md-card>
				<div class="h50"></div>
			</div>

			<div class="border-top" layout>
				<div flex></div>
				<md-button class="md-raised md-primary" ng-click="selectLista()">Seleccionar Lista</md-button>
			</div>

		</div>

	</div>

	<md-button class="md-icon-button abs no-margin focus-on-hover" 
		style="right: 0; top: 0;" aria-label="Button" ng-click="Cancel()">
		<md-icon md-svg-icon="md-close"></md-icon>
	</md-button>

</md-dialog>