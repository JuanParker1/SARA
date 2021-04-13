<md-table-container class="overflow-y hasScroll margin border border-radius">
	<table md-table class="md-table-short table-col-compress border-bottom">
		<thead md-head>
			<tr md-row class="">
				<th md-column>Participamos en {{ ProcesoSel.tableros.length }} Tableros</th>
				<th md-column></th>
			</tr>
		</thead>
		<tbody md-body class="" >
			<tr md-row ng-repeat="T in ProcesoSel.tableros | orderBy:'Titulo' " class="md-row-hover"  >
				<td md-cell class="">
					<div class="" layout layout-align="center center">
						<div class="margin-right-5 text-16px Pointer" ng-click="viewTableroDiag(T)">{{ T.Titulo }}</div>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="viewTableroDiag(T)">
							<md-icon md-font-icon="fa-external-link-alt"></md-icon>
						</md-button>
						<span flex></span>
					</div>
				</td>
				<td md-cell></td>
			</tr>
		</tbody>
	</table>
</md-table-container>

<md-table-container class="overflow-y hasScroll margin border border-radius">
	<table md-table class="md-table-short table-col-compress border-bottom">
		<thead md-head>
			<tr md-row class="">
				<th md-column>
					<div layout layout-align="center center">
						<div class="mw130">Contamos con {{ IndicadoresFiltrados.length  }} Indicadores</div>
						<md-input-container md-no-float class=" no-margin md-no-underline" flex>
							<input type="text" ng-model="filterIndicadores" placeholder="Buscar..." class="" autocomplete="off" ng-model-options="{ 'debounce': 150 }">
						</md-input-container>
					</div>
				</th>
				<th md-column md-numeric style="padding-right: 25px !important;">Meta</th>
				<th md-column md-numeric>{{ (AnioActual*100) + MesActual }}</th>
			</tr>
		</thead>
		<tbody md-body class="" >
			<tr md-row ng-repeat="I in IndicadoresFiltrados = (ProcesoSel.indicadores | filter:filterIndicadores)" class="md-row-hover"  >
				<td md-cell class="">
					<div class="" layout layout-align="center center">
						<div class="margin-right-5 text-16px Pointer" ng-click="viewIndicadorDiag(I.id)" md-truncate>{{ I.Indicador }}</div>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="viewIndicadorDiag(I.id)">
							<md-icon md-font-icon="fa-external-link-alt"></md-icon>
						</md-button>
						<span flex></span>
					</div>
				</td>
				<td md-cell ng-click="viewIndicadorDiag(I.id)">
					<div layout layout-align="center center" class="w100p">
						<div flex class="text-14px text-clear no-wrap" ng-repeat="IndVal in [I.valores[(AnioActual*100) + MesActual]]">
							{{ IndVal.meta_val }}
						</div>
						<md-icon class="fa-fw s30 no-margin text-16px" md-font-icon="{{Sentidos[I.Sentido].icon}}">
							<md-tooltip md-direction=right>{{ Sentidos[I.Sentido].desc }}</md-tooltip>
						</md-icon>
					</div>
					
				</td>
				<td md-cell class="Pointer" ng-click="viewIndicadorDiag(I.id)">
					<div class="text-16px " ng-repeat="IndVal in [I.valores[(AnioActual*100) + MesActual]]" style="color: {{ IndVal.color }}">
						{{ IndVal.val }}
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</md-table-container>

<div class="h40"></div>
