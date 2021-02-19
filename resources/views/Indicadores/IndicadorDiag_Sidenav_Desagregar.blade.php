<div flex layout=column ng-show="sidenavSel == 'Desagregar Datos'">
	<div class="padding-5" layout>
		<h3 class="no-margin md-subhead" flex>Desagregar Datos</h3>
	</div>
		
	<div class="padding-5 overflow-y hasScroll" flex layout=column>


		<md-chips class="" ng-model="Ind.desagregados"
			md-on-remove="removedDesagregado($chip)"
			readonly=true md-removable=true
			ng-show="Ind.desagregados.length > 0">
			<md-chip-template>{{$chip.campo_title}}</md-chip-template>
		</md-chips>
		
		<md-select ng-model="newChip" placeholder="Agregar Campo" ng-change="addDesagregado()" 
			ng-show="Ind.desagregables.length > 0" class="margin-10-0">
			<md-option ng-repeat="C in Ind.desagregables" ng-value="C">{{ C.campo_title }}</md-option>
		</md-select>

		<div flex></div>

		<md-button class="margin no-padding md-raised md-danger" ng-show="Ind.desagregados.length > 0" 
			ng-click="getDesagregatedData($event)">
			<md-icon md-font-icon="fa-list margin-right"></md-icon>Desagregar Datos
		</md-button>

		<div layout layout-align="center center" class="margin-10-0" ng-show="Ind.desagregados.length > 0">
			<div class="text-clear h30 lh30 margin-0-10">Ver:</div>
			<md-select ng-model="viewDesagregacionVal" class="no-margin" aria-label=s>
				<md-option value='All'>Todos</md-option>
				<md-option value='IndVal'>Resultado</md-option>
				<md-option ng-repeat="(kV, V) in Ind.variables" ng-value='{{ kV }}'>{{ V.variable_name }}</md-option>
			</md-select>
		</div>

	</div>

</div>