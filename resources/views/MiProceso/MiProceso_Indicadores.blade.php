<md-table-container class="overflow-y hasScroll margin border border-radius">
	<table md-table class="md-table-short table-col-compress border-bottom">
		<thead md-head>
			<tr md-row class="">
				<th md-column>
					<md-input-container md-no-float class=" no-margin md-no-underline">
						<input type="text" ng-model="filterIndicadores" placeholder="Buscar..." class="" autocomplete="off">
					</md-input-container>
				</th>
				<th md-column hide>{{ (AnioActual*100) + MesActual }}</th>
			</tr>
		</thead>
		<tbody md-body class="" >
			<tr md-row><td md-cell class="text-bold">&nbsp;&nbsp;&nbsp;Indicadores Propios ({{ ProcesoSel.indicadores.length }})</td></tr>
			<tr md-row ng-repeat="I in ProcesoSel.indicadores | filter:filterIndicadores" class="md-row-hover">
				<td md-cell class="">
					<div class="" layout layout-align="center center">
						<md-icon class="fa-fw s30 no-margin text-16px" md-font-icon="{{Sentidos[I.Sentido].icon}}">
							<md-tooltip md-direction=right>{{ Sentidos[I.Sentido].desc }}</md-tooltip>
						</md-icon>
						<md-icon md-font-icon="fa-chart-line fa-fw s30 no-margin" hide></md-icon>
						<div class="margin-right-5 text-16px Pointer">{{ I.Indicador }}</div>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="viewIndicadorDiag(I.id)">
							<md-icon md-font-icon="fa-external-link-alt"></md-icon>
						</md-button>
						<span flex></span>
					</div>
				</td>
			</tr>
			<tr md-row><td md-cell></td></tr>
			<tr md-row><td md-cell class="text-bold">&nbsp;&nbsp;&nbsp;Indicadores de Subprocesos ({{ ProcesoSel.indicadores_subprocesos.length }})</td></tr>
			<tr md-row ng-repeat="I in ProcesoSel.indicadores_subprocesos | filter:filterIndicadores" class="md-row-hover">
				<td md-cell class="">
					<div class="" layout layout-align="center center">
						<md-icon class="fa-fw s30 no-margin text-16px" md-font-icon="{{Sentidos[I.Sentido].icon}}">
							<md-tooltip md-direction=right>{{ Sentidos[I.Sentido].desc }}</md-tooltip>
						</md-icon>
						<md-icon md-font-icon="fa-chart-line fa-fw s30 no-margin" hide></md-icon>
						<div class="margin-right-5 Pointer padding-5-0" layout=column>
							<div class="text-16px">{{ I.Indicador }}</div>
							<div class="text-clear">{{ I.proceso.Proceso }}</div>
						</div>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="viewIndicadorDiag(I.id)">
							<md-icon md-font-icon="fa-external-link-alt"></md-icon>
						</md-button>
						<span flex></span>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</md-table-container>

<div class="h40"></div>
