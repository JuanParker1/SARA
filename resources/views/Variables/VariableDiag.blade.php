<md-dialog class="no-overflow bg-black-2 mh100m20 mw100m20" md-theme="Black">


	<div layout layout-align="center start" class="padding-left margin-bottom">
		<div layout=column class="lh40 margin-left-5">
			<div class="text-16px"><span>{{ Var.Variable }}</span></div>
			<div class="text-13px text-clear">{{ Var.Descripcion }}</div>
		</div>
		<div class="text-clear margin-left lh40">{{ Var.proceso.Proceso }}</div>
		<span flex></span>
		<md-button class="md-icon-button" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div flex layout=column class="overflow-y darkScroll">
		
		
		<md-table-container class="text-13px padding-right-20" style="">
			<table md-table class="md-table-short table-col-compress border-bottom">
				<thead md-head>
					<th md-column>
						<div layout=column style="height: 150px;padding-right: 50px;">
							<div layout>
								<md-button ng-click="anioAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
								<div class="h30 lh30">{{ Anio }}</div>
								<md-button ng-click="anioAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
							</div>
						</div>
					</th>
					<th md-column colspan=12>
						<div class="" layout layout-align="end">
							<nvd3 class="no-gridlines" options="grapOptions" data="graphData" api="graphApi"
								style="width: calc(100% + 95px);transform: translateX(40px);"
								config="{deepWatchData: false}"></nvd3>
						</div>
					</th>
					<tr md-row>
						<th md-column class="text-left padding-left">Año</th>
						<th md-column ng-repeat="M in Meses" md-numeric class="text-right padding-right">{{ M[1] }}</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="text-14px" style="color: #6ab8ff">
						<td md-cell class="text-bold mw70">{{ Anio }}</td>
						<td md-cell class="mw50 Pointer cell-hoverable" ng-repeat="M in Meses" ng-click="openMenuValores($event, Anio+M[0])">
							{{ Var.valores[Anio+M[0]].val }}</td>
					</tr>
					<tr md-row class="text-14px">
						<td md-cell class="text-bold mw70">{{ Anio - 1 }}</td>
						<td md-cell class="mw50 Pointer cell-hoverable" ng-repeat="M in Meses" ng-click="openMenuValores($event, (Anio-1)+M[0])">
							{{ Var.valores[(Anio-1)+M[0]].val }}
						</td>
					</tr>
					
					<tr md-row ng-show="Var.desagregables.length > 0"><td md-cell colspan=13></td></tr>
					<tr md-row ng-show="Var.desagregables.length > 0">
						<td md-cell colspan=13>
							<div class="padding-left" layout>
								<span class="lh30 margin-right">Desagregar Por:</span>
								<md-chips class="h30" ng-model="Var.desagregados" md-on-add="addedDesagregado($chip)" md-on-remove="removedDesagregado($chip)">
									
									 <md-autocomplete
										md-selected-item="selectedItem"
										md-search-text="searchText"
										md-items="item in Var.desagregables | filter:searchText "
										md-item-text="item.campo_title" 
										md-min-length=0 
										md-no-cache="true"
										placeholder="Agregar" 
										class="w50">
										<span md-highlight-text="searchText">{{ item.campo_title }}</span>
									</md-autocomplete>

									<md-chip-template>{{$chip.campo_title}}</md-chip-template>

								</md-chips>

								<md-button class="md-icon-button s30 no-margin no-padding" ng-show="Var.desagregados.length > 0" 
									ng-click="getDesagregatedData()">
									<md-tooltip md-direction=right>Desagregar</md-tooltip>
									<md-icon md-font-icon="fa-bolt"></md-icon>
								</md-button>
							</div>
						</td>
					</tr>

					<tr md-row ng-repeat="D in Desagregacion">
						<td md-cell>{{ D.Llave }}</td>
						<td md-cell ng-repeat="M in Meses">
							<div ng-repeat="Dato in [D.valores[Anio+M[0]]] ">{{ Dato.val }}</div>
						</td>
					</tr>

				</tbody>
			</table>
			<div class="h40"></div>
		</md-table-container>

	</div>

</md-dialog>

