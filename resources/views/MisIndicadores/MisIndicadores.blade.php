<div flex id="MisIndicadores" layout=column ng-controller="MisIndicadoresCtrl">
	
	<div layout class="border-bottom bg-theme padding-0-10 h42 text-14px" layout-align="center center">
		<h3 class="text-16px text-400 no-margin">Mís Indicadores ({{ filteredIndicadores.length }})</h3>
		<span flex></span>

		<div layout>
			<md-button ng-click="anioAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
			<div class="h30 lh30 Pointer">{{ Anio }}</div>
			<md-button ng-click="anioAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
		</div>

		<span flex></span>

		<div class="text-clear h40 lh40 margin-right-5">Proceso:</div>
		<md-select ng-model="ProcesoSel" class="no-margin no-padding md-no-underline h30" style="transform: translateY(-2px);"
			ng-change="filterIndicadores()" aria-label=a>
			<md-option ng-value="{{ false }}">Todos</md-option>
			<md-option ng-repeat="P in Usuario.Procesos" ng-value="P.id">{{ P.Proceso }}</md-option>
		</md-select>

	</div>

	<div flex layout class="bg-theme" ng-show="!Loading">
		
		<md-table-container flex class="overflow-y hasScroll">
			<table md-table class="md-table-short table-col-compress border-bottom">
				<thead md-head>
					<tr md-row class="">
						<th md-column>
							<md-input-container md-no-float class=" no-margin md-no-underline">
								<input type="text" ng-model="filterIndicadoresText" placeholder="Buscar..." class="text-400 text-14px" 
									ng-change="filterIndicadores()">
							</md-input-container>
						</th>
						<th md-column md-numeric ng-repeat="M in Meses" class="mw60">{{ M[1] }}</th>
						<th md-column md-numeric>Meta</th>
					</tr>
				</thead>
				<tbody md-body class="text-14px" >
					<tr md-row ng-repeat="I in filteredIndicadores" class="md-row-hover">
						<td md-cell class="">
							{{ I.Indicador }}
							<md-button class="md-icon-button no-margin no-padding s25" ng-click="viewIndicadorDiag(I.id)">
								<md-icon md-font-icon="fa-external-link-alt"></md-icon>
							</md-button>
						</td>
						<td md-cell ng-repeat="(Periodo,VI) in I.valores" class="md-cell-compress mw60">
							<div ng-style="{ color: VI.color }" class="w100p text-16px">{{ VI.val }}</div>
						</td>
						<td md-cell>
							{{   }}
						</td>
					</tr>
				</tbody>
			</table>

			<div class="h30"></div>

		</md-table-container>

	</div>

</div>