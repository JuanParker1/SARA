<md-dialog class="no-overflow bg-black-2 mh100m20 mw100m20" md-theme="Black" layout>

	@include('Indicadores.IndicadorDiag_Sidenav')

	<div layout=column flex>
		
		<div layout layout-align="center center" class="padding-left">
			<div layout=column>
				<div class="text-16px"><span>{{ Ind.Indicador }}</span>
					<md-icon class="margin-left Pointer" md-font-icon="{{Sentidos[Ind.Sentido].icon}}">
						<md-tooltip md-direction=right>{{ Sentidos[Ind.Sentido].desc }}</md-tooltip>
					</md-icon>
				</div>
				<div class="text-13px text-clear">{{ Ind.Definicion }}</div>
			</div>
			<div class="text-clear margin-left">{{ Ind.proceso.Proceso }}</div>
			<span flex></span>
			<div class="Pointer padding-right" layout layout-align="center center" hide>
				<div class="s25 bg-lightgrey border-rounded margin-right-5 border" 
					style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
				<div class="text-16px" hide-xs>{{ Usuario.Nombres }}</div>
			</div>
			
			<md-button class="md-icon-button no-margin focus-on-hover" aria-label="Button" ng-click="Cancel()" 
				>
				<md-tooltip md-direction=left>Salir</md-tooltip>
				<md-icon md-svg-icon="md-close"></md-icon>
			</md-button>
		</div>

		<div flex layout>

			<div flex layout=column class="overflow-y hasScroll padding-top-20">
				
				<md-table-container class="border-bottom">
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
										<md-switch class="margin-5-0" ng-model="modoComparativo" aria-label="a" ng-change="updateChart()">Ver Comparativo</md-switch>
										<md-switch hide class="margin-5-0" ng-model="showSidenav"     aria-label="a">Ver Mejoramiento</md-switch>
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
							<tr md-row class="md-row-hover Pointer" ng-repeat="comp in Ind.variables" >
								<td md-cell><div layout layout-align="center center">
									<div flex class="padding-5-0"><b>{{ comp.Letra }}:</b> {{ comp.variable_name }}</div>
									<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="viewCompDiag(comp)">
										<md-icon md-font-icon="fa-external-link-alt fa-fw"></md-icon>
										<md-tooltip md-direction="left">Ver {{ comp.Tipo }}</md-tooltip>
									</md-button>
								</div>
								</td>
								<td md-cell ng-repeat="M in Meses">{{ comp.valores[Anio+M[0]].val }}</td>
							</tr>

							<tr md-row><td md-cell colspan=13></td></tr>

							<tr md-row>
								<td md-cell>Mejoramiento</td>
								<td md-cell ng-repeat="M in Meses" ng-click="openSidenavElm(['','Mejoramiento'])">
									<md-icon md-font-icon="fa-comment fa-fw fa-lg" class="Pointer"
										ng-if="(ComentariosCRUD.rows | filter:{ Grupo:'Comentario', Op1: Anio+M[0] }).length > 0">
										<md-tooltip>{{ (ComentariosCRUD.rows | filter:{ Grupo:'Comentario', Op1: Anio+M[0] }).length }} Comentarios</md-tooltip>		
									</md-icon>
									<md-icon md-font-icon="fa-clipboard-list fa-fw fa-lg" class="Pointer"
										ng-if="(ComentariosCRUD.rows | filter:{ Grupo:'Accion', Op1: Anio+M[0] }).length > 0">
										<md-tooltip>{{ (ComentariosCRUD.rows | filter:{ Grupo:'Accion', Op1: Anio+M[0] }).length }} Acciones</md-tooltip>		
									</md-icon>
								</td>
							</tr>

							<tr md-row ng-show="Ind.desagregables.length > 0 || Ind.desagregados.length > 0">
								<td md-cell colspan=13></td></tr>

							<tr md-row ng-show="Ind.desagregados.length > 0">
								<td md-cell colspan=13>
									<div class="padding-5-10">Datos Desagregados - {{ viewDesagregacionVal }}</div>
								</td>
							</tr>

							<tr md-row ng-repeat="D in Desagregacion.valores">
								<td md-cell class="md-cell-compress">{{ D.Llave }}</td>
								<td md-cell ng-repeat="(Periodo, DM) in D.valores">
									<div layout=column>
										<div ng-style="{ color: DM.color }" ng-show="inArray(viewDesagregacionVal, ['All', 'IndVal'])" class="h20">{{ DM.val }}</div>
										<div ng-repeat="Variable in DM.comps_vals track by $index" class="h20"
											ng-show="inArray(viewDesagregacionVal, ['All', $index])">{{ Variable }}</div>
									</div>
								</td>
							</tr>



						</tbody>
					</table>

					<div class="h40"></div>
				</md-table-container>

				

			</div>

		</div>

	</div>

</md-dialog>