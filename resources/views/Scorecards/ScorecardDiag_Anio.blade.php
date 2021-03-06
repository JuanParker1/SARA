<div flex layout=column class="overflow hasScroll transition" ng-show="Modo == 'Año'" ng-class="{ 'opacity-0': Loading }">

	<md-table-container class="border-bottom" style="overflow: initial;">
		<table md-table class="md-table-short table-col-compress table-nowrap">
			<colgroup>
				<col span="2">
				<col  ng-repeat="M in Meses" ng-class="{ 'bg-black-5': M[0] == Mes }"><col>
				<col></col>
			</colgroup>
			<thead md-head>
				<tr md-row class="">
					<th md-column>
						<div layout><div class="ProcesoSel_pill">{{ ProcesoSelName }}</div></div>
					</th>
					<th md-column md-numeric>Meta</th>
					<th md-column md-numeric ng-repeat="M in Meses" class="mw45">{{ M[1] }}</th>
					<th md-column></th>
				</tr>
			</thead>
			<tbody md-body class="text-14px Pointer" >
				<tr md-row ng-repeat="N in Sco.nodos_flat_show" class="md-row-hover Pointer ng-hide" ng-click="decideAction(N)" 
					ng-show="N.show">
					<td md-cell style="padding: 0 !important">
						<div class="w100p Pointer" layout ng-click="openFlatLevel(N, $event)">
							<div ng-style="{ width: 10 * N.depth }"></div>
							<md-icon md-font-icon="fa-chevron-right fa-fw s20 transition margin-right-5" ng-if="N.tipo == 'Nodo'"
								ng-class="{'fa-rotate-90':N.open}"></md-icon>
							
							<md-icon md-font-icon="fa-chart-line  fa-fw s20 margin-right-5" ng-if="N.tipo == 'Indicador'"></md-icon>
							<md-icon md-font-icon="fa-superscript fa-fw s20 margin-right-5" ng-if="N.tipo == 'Variable'"></md-icon>

							<div class="padding-5-0 mw160" flex layout=column>
								<div>{{ N.Nodo }} <span ng-if="N.tipo == 'Nodo'" class="text-clear">&nbsp;{{ N.cant_indicadores + N.cant_variables }}</span></div>
								<div class="text-clear" ngs-if="N.tipo == 'Indicador'">{{ N.elemento.proceso.Proceso }}</div>
							</div>
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
					<td md-cell ng-repeat="M in Meses" class="scorecard_mescell">
						
						<div class="w100p" ng-if="N.tipo == 'Nodo'" ng-repeat="E in [ N.calc[Anio+M[0]] ] "
							ng-style="{ color: E['color'] }">
							<span ng-if="E['calculable']">{{ E['val'] }}</span>
						</div>

						<div class="w100p relative" ng-if="N.tipo == 'Indicador'" ng-repeat="E in [ N.valores[Anio+M[0]] ] "
							ng-style="{ color: E['color'] }">
							<md-icon ng-if="E.comentarios_total > 0" md-font-icon="fa-circle fa-fw text-7pt s10 text-clear"></md-icon>
							<span ng-if="filters.see == 'Res'">{{ E['val'] }}</span>
							<span ng-if="filters.see == 'Cump'">{{ E['cump_porc'] | percentage:1 }}</span>				
						</div>

						<div class="w100p" ng-if="N.tipo == 'Variable'" ng-repeat="E in [ N.valores[Anio+M[0]] ] ">
							{{ E['val'] }}
						</div>

					</td>
					<td md-cell><pre hide>{{ N.cump_porc_prom | json }}</pre></td>
					
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

	.ProcesoSel_pill{
		background: #3e3e3e;
		color: white;
		padding: 2px 10px;
		border-radius: 15px;
	}
</style>