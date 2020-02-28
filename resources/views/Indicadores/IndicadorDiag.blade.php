<md-dialog class="vh100 no-overflow bg-black-2" md-theme="Black" flex=100>


	<div layout layout-align="center center" class="padding-0-10">
		<div flex layout=column>
			<div class="text-16px"><span>{{ Ind.Indicador }}</span>
				<md-icon class="margin-left Pointer" md-font-icon="{{Sentidos[Ind.Sentido].icon}}">
					<md-tooltip md-direction=right>{{ Sentidos[Ind.Sentido].desc }}</md-tooltip>
				</md-icon>
			</div>
			<div class="text-13px text-clear">{{ Ind.Definicion }}</div>
		</div>

		<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="showComments = !showComments">
			<md-tooltip md-direction=left>Comentarios</md-tooltip>
			<md-icon md-svg-icon="md-insert-comment"></md-icon>
		</md-button>
		
		<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div flex layout>

		<div flex layout=column class="overflow-y hasScroll padding-top-20">
			
			<md-table-container class="padding-right-20 margin-right-5 border-bottom">
				<table md-table class="md-table-short table-col-compress">
					<thead md-head>
						<tr md-row>
							<th md-column>
								<div layout=column style="height: 150px;padding-right: 50px;">
									<div layout>
										<md-button ng-click="anioAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
										<div class="h30 lh30">{{ Anio }}</div>
										<md-button ng-click="anioAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
									</div>
									<md-switch class="margin-top-5" ng-model="modoComparativo" aria-label="a" ng-change="updateChart()">Ver Comparativo</md-switch>
								</div>
							</th>
							<th md-column colspan=12>
								<div class="" layout layout-align="end">
									<nvd3 class="no-gridlines" options="grapOptions" data="graphData" api="graphApi"
										style="width: calc(100% + 135px);transform: translateX(30px);"
										config="{deepWatchData: false}"></nvd3>
								</div>
							</th>
						</tr>
						<tr md-row class="">
							<th md-column></th>
							<th md-column md-numeric ng-repeat="M in Meses" class="mw45">{{ M[1] }}</th>
						</tr>
					</thead>
					<tbody md-body class="text-14px">
						<tr md-row class="text-16px md-row-hover">
							<td md-cell class="w235">Resultado</td><td md-cell class="text-bold" ng-repeat="V in Ind.valores" ><span ng-style="{ color: V.color }">{{ V.val }}</span></td>
						</tr>
						<tr md-row class="md-row-hover">
							<td md-cell class="">Meta</td><td md-cell class="" ng-repeat="V in Ind.valores">{{ V.meta_val }}</td>
						</tr>
						
						<tr md-row class="md-row-hover" ng-show="modoComparativo">
							<td md-cell class="">Resultado {{ Anio-1 }}</td><td md-cell class="" ng-repeat="V in Ind.valores"><span ng-style="{ color: V.anioAnt_color }">{{ V.anioAnt_val }}</span></td>
						</tr>
						<tr md-row class="md-row-hover" ng-show="modoComparativo">
							<td md-cell class="">Meta {{ Anio-1 }}</td><td md-cell class="" ng-repeat="V in Ind.valores">{{ V.anioAnt_meta_val }}</td>
						</tr>

						<tr md-row class="md-row-hover" ng-show="modoComparativo">
							<td md-cell class="">Variación Mensual</td><td md-cell class="" ng-repeat="V in Ind.valores">{{ V.varMoM_val }}</td>
						</tr>
						<tr md-row class="md-row-hover" ng-show="modoComparativo">
							<td md-cell class="">Variación Interanual</td><td md-cell class="" ng-repeat="V in Ind.valores">{{ V.varYoY_val }}</td>
						</tr>

						<tr md-row class="md-row-hover">
							<td md-cell>Formula: {{ Ind.Formula }}</td>
							<td md-cell colspan=12></td>
						</tr>
						<tr md-row class="md-row-hover Pointer" ng-repeat="comp in Ind.variables" ng-click="viewCompDiag(comp)">
							<td md-cell><div layout layout-align="center center">
								<div flex class="padding-5-0"><b>{{ comp.Letra }}:</b> {{ comp.variable_name }}</div>
								<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" >
									<md-icon md-font-icon="fa-external-link-alt fa-fw"></md-icon>
									<md-tooltip md-direction="left">Ver {{ comp.Tipo }}</md-tooltip>
								</md-button>
							</div>
							</td>
							<td md-cell ng-repeat="M in Meses">{{ comp.valores[Anio+M[0]].val }}</td>
						</tr>
					</tbody>
				</table>
			</md-table-container>

			<div class="h50"></div>

		</div>

		<!--<div class="w240" layout=column style="border-left: 1px solid #404040">
			AAAA
		</div>-->

		
		  <md-sidenav md-is-locked-open="false" md-is-open="showComments"  class="md-sidenav-right w350 text-white" layout=column>
		  		<h3 class="margin md-subhead">Comentarios</h3>
				<div class="padding-0-10" flex>
					<div ng-repeat="C in Comentarios" class="comment">{{ C }}</div>
				</div>
				<div layout>
					<md-input-container flex class="">
						<textarea ng-model="a" rows="2"></textarea>
					</md-input-container>
				</div>
		  </md-sidenav>

	</div>
	<style type="text/css">
		.comment{
			background: #303030;
			font-size: 15px;
			margin-bottom: 9px;
			padding: 9px 10px;
			border-radius: 4px;
		}
	</style>
</md-dialog>

