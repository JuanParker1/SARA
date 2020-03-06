<div flex id="IngresarDatos" layout=column ng-controller="IngresarDatosCtrl">
	
	<div layout class="border-bottom bg-theme padding-0-10 h42 text-14px" layout-align="center center">
		<h3 class="text-20px text-300 no-margin">Ingresar Datos</h3>
		<span flex></span>

		<div layout>
			<md-button ng-click="anioAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
			<div class="h30 lh30 Pointer">{{ Anio }}</div>
			<md-button ng-click="anioAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
		</div>

		<span flex></span>

		<div class="text-clear h40 lh40 margin-right-5">Tipo:</div>
		<md-select ng-model="tipoVariableSel" class="no-margin no-padding md-no-underline h30" style="transform: translateY(-2px);"
			ng-change="filterVariables()" aria-label=a>
			<md-option ng-value="{{ false }}">Todos</md-option>
			<md-option ng-repeat="(kT,T) in TiposVariables" ng-value="kT">{{ T.Nombre }}</md-option>
		</md-select>

		<div class="w10"></div>

		<div class="text-clear h40 lh40 margin-right-5">Proceso:</div>
		<md-select ng-model="ProcesoSel" class="no-margin no-padding md-no-underline h30" style="transform: translateY(-2px);"
			ng-change="filterVariables()" aria-label=a>
			<md-option ng-value="{{ false }}">Todos</md-option>
			<md-option ng-repeat="P in Usuario.Procesos" ng-value="P.id">{{ P.Proceso }}</md-option>
		</md-select>
		
		

	</div>

	<div flex layout class="bg-theme" ng-show="!Loading">
		
		<md-table-container flex class="">
			<table md-table class="md-table-short table-col-compress border-bottom">
				<thead md-head>
					<tr md-row class="">
						<th md-column>
							<md-input-container md-no-float class=" no-margin md-no-underline">
								<input type="text" ng-model="filterVariablesText" placeholder="Buscar..." class="text-300 text-14px" 
									ng-change="filterVariables()">
							</md-input-container>
						</th>
						<th md-column md-numeric ng-repeat="M in Meses" class="mw45">{{ M[1] }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody md-body class="text-14px" >
					<tr md-row ng-repeat="V in filteredVariables" class="md-row-hover">
						<td md-cell class="border-right">
							{{ V.Variable }}
							<md-button class="md-icon-button no-margin no-padding s25" ng-click="viewVariableDiag(V.id)">
								<md-icon md-font-icon="fa-external-link-alt"></md-icon>
							</md-button>
						</td>
						<td md-cell ng-repeat="(Periodo,VP) in V.valores" style="padding: 0 !important" class="md-cell-compress" ng-class="{ 'bg-lightgrey': VP['readonly'] }">
								
							<md-input-container class="no-margin no-padding md-no-underline mw80" 
								ng-class="{ 'bg-yellow': VP['edited'] }">
								<input type="text" ng-model="VP['new_Valor']" ng-change="markChanged(VP)" class="text-right border-right padding-right" ui-number-mask="0" 
									autocomplete="new-password" ng-readonly="VP['readonly']" aria-label=a>
							</md-input-container>
							
						</td>
						<td md-cell></td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

	</div>

	<div layout class="bg-theme border-top" ng-show="hasEdited">
		<span flex></span>
		<md-button class="md-raised md-primary" ng-click="saveVariables()">Guardar Cambios</md-button>
	</div>

</div>