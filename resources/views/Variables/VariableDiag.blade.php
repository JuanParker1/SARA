<md-dialog class="no-overflow bg-black-2 vh100" md-theme="Black" flex=100>


	<div layout layout-align="center center" class="padding-left margin-bottom">
		<div flex layout=column>
			<div class="text-16px"><span>{{ Var.Variable }}</span></div>
			<div class="text-13px text-clear">{{ Var.Descripcion }}</div>
		</div>
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
					<tr md-row class="text-14px md-row-hover" style="color: #6ab8ff">
						<td md-cell class="text-bold mw70">{{ Anio }}</td>
						<td md-cell class="mw50 Pointer " ng-repeat="M in Meses" ng-click="">{{ Var.valores[Anio+M[0]].val }}</td>
					</tr>
					<tr md-row class="text-14px md-row-hover">
						<td md-cell class="text-bold mw70">{{ Anio - 1 }}</td>
						<td md-cell class="mw50 Pointer " ng-repeat="M in Meses" ng-click="">{{ Var.valores[(Anio-1)+M[0]].val }}</td>
					</tr>

					<tr md-row class="Pointer" ng-click="viewRelatedVariables = !viewRelatedVariables">
						<td md-cell class="text-clear"><md-icon md-font-icon="fa-fw fa-chevron-right" ng-class="{'fa-rotate-90':viewRelatedVariables}"></md-icon>
							Variables Relacionadas</td><td md-cell colspan=12></td>
					</tr>

					<tr md-row class="md-row-hover Pointer" ng-repeat="R in Var.related_variables | switch:viewRelatedVariables" ng-click="viewVariableDiag(R.id)">
						<td md-cell class=""><div layout layout-align="center center">
							<div flex class="padding-5-0">{{ R.Variable }}</div>
							<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" >
								<md-icon md-font-icon="fa-external-link-alt fa-fw"></md-icon>
								<md-tooltip md-direction="left">Ver Variable</md-tooltip>
							</md-button>
						</div></td>
						<td md-cell class="" ng-repeat="M in Meses">{{ R.valores[Anio+M[0]].val }}</td>
					</tr>

				</tbody>
			</table>
		</md-table-container>

	</div>

</md-dialog>

