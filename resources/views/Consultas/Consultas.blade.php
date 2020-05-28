<div id=ConsultasSQL ng-controller="ConsultasSQLCtrl" layout=column md-theme="Black">
	


	<div layout class="text-white padding-0-10">

		<md-select ng-model="ConsultaSel" class="no-margin">
			<md-option ng-repeat="Op in Consultas" ng-value="Op">{{ Op.Nombre }}</md-option>
		</md-select>

		<md-datepicker ng-model="FechaIni" class="text-white"></md-datepicker>
		<md-datepicker ng-model="FechaFin" class="text-white"></md-datepicker>
		
		<div class="md-title" style="line-height: 50px; margin-left: 20px;">{{ FechaAct }}</div>

		<md-button ng-click="Go()" ng-show="inArray(Status, ['Stopped','Paused']) ">
			<md-icon md-font-icon="fa-play"></md-icon>
		</md-button>

		<md-button ng-click="Pause()" ng-show="Status == 'Playing'">
			<md-icon md-font-icon="fa-pause"></md-icon>
		</md-button>

		<md-button ng-click="Stop()" ng-show="Status == 'Paused'">
			<md-icon md-font-icon="fa-fast-backward"></md-icon>
		</md-button>

		<span flex></span>

		

	</div>

	<div flex layout>
		
		<md-table-container>
			<table md-table class="md-table-short">
				<thead md-head>
					<tr md-row>
						<th md-column class="">Dia</th>
						<th md-column>Tiempo</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row  class="md-row-hover" ng-repeat="Day in Report">
						<td md-cell class="md-cell-compress">{{ Day.Dia }}</td>
						<td md-cell class="md-cell-compress">{{ Day.Tiempo }} seg</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

	</div>
	

	

	

</div>