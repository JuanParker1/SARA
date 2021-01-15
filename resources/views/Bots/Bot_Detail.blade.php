<div layout>
	<md-button class="md-icon-button no-padding s30" aria-label="b" ng-click="BotsNav = !BotsNav" 
		style="margin: -4px 0 0 -4px;">
		<md-icon md-svg-icon="md-bars" class=""></md-icon>
	</md-button>
	<md-input-container flex>
		<input type="text" ng-model="BotSel.Nombre" placeholder="Bot">
	</md-input-container>

	<md-input-container>
		<label>Estado</label>
		<md-select ng-model="BotSel.Estado">
		  <md-option ng-value="Op" ng-repeat="Op in EstadosBots">{{ Op }}</md-option>
		</md-select>
	</md-input-container>
</div>

<div class="md-subheader margin-bottom">Periodicidad</div>
<div layout layout-wrap>
	<div flex=20 ng-repeat="Dia in DiasSemana">
		<md-checkbox ng-model="BotSel.config[Dia[0]]" aria-label="c">{{ Dia[1] }}</md-checkbox>
	</div>
	
</div>


<div layout>
	<div layout=column flex>
		<div layout ng-repeat="(kH,Hora) in BotSel.config.Horas track by $index" class="">
			<md-input-container class="no-margin" >
				<input type="time" ng-model="Hora" ng-change="setHour(Hora, kH)">
			</md-input-container>
			<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="removeHour(kH)">
				<md-icon md-svg-icon="md-close"></md-icon>
			</md-button>
		</div>
	</div>
	<div layout=column>
		<md-button class=" no-margin" aria-label="b" ng-click="addHour()">
			<md-icon md-svg-icon="md-plus" class="margin-right-5"></md-icon>Agregar Hora
		</md-button>
	</div>

</div>

<div class="md-subheader margin-top-20 margin-bottom-5">Variables</div>
<md-table-container ng-show="VariablesCRUD.rows.length > 0">
	<table md-table class="md-table-short border-radius" style="border: 1px solid #3e3e3e">
		<thead md-head>
			<tr md-row>
				<th md-column class="padding-left">Nombre</th>
				<th md-column class="padding-left">Valor</th>
				<th md-column></th>
			</tr>
		</thead>
		<tbody md-body>
			<tr md-row class="" ng-repeat="V in VariablesCRUD.rows">
				<td md-cell class="padding-left">
					<md-input-container class="no-margin md-no-underline w100p" md-no-float>
						<input type="text" ng-model="V.Nombre" placeholder="Nombre" ng-change="V.changed = true">
					</md-input-container>
				</td>
				<td md-cell style="padding-left: 10px;">
					<md-input-container class="no-margin md-no-underline w100p mw150" md-no-float>
						<input type="text" ng-model="V.Valor" placeholder="Valor" ng-change="V.changed = true">
					</md-input-container>
				</td>
				<td md-cell class="md-cell-compress no-padding">
					<md-button class="md-icon-button s30 no-margin no-padding focus-on-hover" aria-label="b" ng-click="delVariable(V)">
						<md-icon md-svg-icon="md-close"></md-icon>
					</md-button>
				</td>
			</tr>
		</tbody>
	</table>
</md-table-container>
<md-button class=" no-margin" aria-label="b" ng-click="addVariable()">
	<md-icon md-svg-icon="md-plus" class="margin-right-5"></md-icon>Agregar Variable
</md-button>




<div class="h50"></div>