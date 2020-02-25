<div flex layout=column class="overflow-y hasScroll" ng-show="Modo == 'Año'">

	<md-table-container class="border-bottom">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
				<tr md-row class="">
					<th md-column></th>
					<th md-column md-numeric ng-repeat="M in Meses" class="mw45">{{ M[1] }}</th>
					<th md-column  md-numeric>Meta</th>
				</tr>
			</thead>
			<tbody md-body class="text-14px Pointer" >
				<tr md-row ng-repeat="N in Sco.nodos_flat" class="md-row-hover Pointer" ng-click="decideAction(N)">
					<td md-cell style="padding: 0 !important">
						<div class="w100p" layout>
							<div ng-style="{ width: 10 * N.Nivel }"></div>
							<md-icon md-font-icon="fa-chevron-right fa-fw s20 transition margin-right-5" ng-if="N.tipo == 'Nodo'"
								ng-class="{'fa-rotate-90':N.open}"></md-icon>
							<md-icon md-font-icon="fa-chart-line fa-fw s20 margin-right-5" ng-if="N.tipo == 'Indicador'"></md-icon>
							<div class="padding-5-0 mw160" flex>{{ N.Nodo }}</div>
						</div>
					</td>
					<td md-cell ng-repeat="M in Meses" class="scorecard_mescell">
						<div class="w100p" ng-if="N.tipo == 'Nodo'" ng-repeat="E in [ N.calc[Anio+M[0]] ] "
							ng-style="{ color: E['color'] }">

							<span hide ng-if="E['incalculables'] < N['nodos_cant']">{{ E['cump_val'] }}</span>
							<span ng-if="E['calculable']">{{ E['cump_val'] }}</span>
						</div>

						<div class="w100p" ng-if="N.tipo == 'Indicador'" ng-repeat="E in [ N.valores[Anio+M[0]] ] "
							ng-style="{ color: E['color'] }">
							{{ E['val'] }}
						</div>
					</td>
					<td md-cell>
						<div class="w100p" ng-if="N.tipo == 'Indicador'" ng-repeat="E in [ N.valores[Anio+'12'] ] ">
							{{ E.meta_val }}
							<md-icon class="s15 margin-left-5" md-font-icon="{{ Sentidos[N.elemento.Sentido].icon}} fa-fw">
								<md-tooltip md-direction=left>{{ Sentidos[N.elemento.Sentido].desc }}</md-tooltip>
							</md-icon>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>

	<div class="h50"></div>

</div>

<style type="text/css">
	.scorecard_mescell > div{
		font-size: 1.15em;
		font-weight: 400;
	}
</style>