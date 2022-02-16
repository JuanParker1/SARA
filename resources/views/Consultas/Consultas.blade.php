<div id=ConsultasSQL ng-controller="ConsultasSQLCtrl" layout=column flex md-theme="Black">
	


	<div layout layout-align="center center"
		class="text-white padding-0-20 bg-black-5 margin-but-bottom border-radius">

		<md-select hide ng-model="ConsultaSel" class="no-margin md-no-underline" aria-label=s>
			<md-option ng-repeat="Op in Consultas" ng-value="Op">{{ Op.Nombre }}</md-option>
		</md-select>

		<div class="md-title md-thin">{{ ConsultaSel.Nombre }}</div>

		<md-datepicker ng-model="FechaIni" class="text-white" ng-change="adjustToday(FechaIni)"></md-datepicker>
		<md-datepicker ng-model="FechaFin" class="text-white"></md-datepicker>
		
		<div class="md-title" style="line-height: 50px; margin-left: 20px;"
			ng-show="inArray(Status, ['Playing','Paused'])">{{ FechaAct }}

		</div>

		<md-progress-circular md-diameter="30" class="md-warn margin-left"
			ng-show="Status == 'Playing'"></md-progress-circular>

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

	<md-card flex class="bg-black-5 margin border-radius overflow-y hasScroll" 
		ng-show="Report.length > 0">
		
		<div class="md-subheader padding">Logs</div>

		<md-table-container style="border-bottom: 1px solid #454545;">
			<table md-table class="md-table-short">
				<thead md-head>
					<tr md-row>
						<th md-column></th>
						<th md-column>Dia</th>
						<th md-column>Tiempo</th>
						<th md-column>Mensaje</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row  class="md-row-hover" ng-repeat="Day in Report">
						<td md-cell class="md-cell-compress"></td>
						<td md-cell class="md-cell-compress">{{ Day.Dia }}</td>
						<td md-cell class="md-cell-compress">{{ Day.Tiempo }} seg</td>
						<td md-cell class="">{{ Day.mensaje }}</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<div class="h50"></div>

	</md-card>
	

	

	

</div>