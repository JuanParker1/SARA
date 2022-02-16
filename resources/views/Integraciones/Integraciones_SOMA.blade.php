<div class="bg-black-3" flex layout=column md-theme="Black" ng-controller="Integraciones_SOMACtrl">
	<div class="md-title text-thin padding-but-bottom">Generador Archivos SOMA</div>

	<div flex layout>
		
		<md-card class="w230 margin padding overflow-y hasScroll" layout=column>

			<md-input-container>
				<label>Contrato</label>
				<md-select ng-model="filters.Tipo" aria-label=s class="no-margin">
					<md-option ng-value="'GCFR'">PGPs GC y FR</md-option>
					<md-option ng-value="'ONC'">PGP Oncológico</md-option>
				</md-select>
			</md-input-container>
			
			<md-input-container>
				<label>Fecha</label>
				<md-datepicker ng-model="filters.Desde" class="text-white" md-hide-icons="calendar"></md-datepicker>
			</md-input-container>
			
			<span flex></span>

			<md-button class="no-margin" ng-click="downloadFile()" ng-disabled="Loading">Descargar Archivo</md-button>
			<div class="h10"></div>
			<md-button class="no-margin md-raised bg-green" ng-click="sendSoma()" ng-disabled="Loading">Enviar Datos</md-button>

		</md-card>

		<md-card flex class="margin-but-left overflow-y hasScroll" ng-show="Report.length > 0">
			<md-table-container style="border-bottom: 1px solid #454545;">
				<table md-table class="md-table-short">
					<thead md-head>
						<tr md-row>
							<th md-column>Contrato</th>
							<th md-column>Fecha</th>
							<th md-column>Tiempo</th>
							<th md-column>Mensaje</th>
						</tr>
					</thead>
					<tbody md-body>
						<tr md-row  class="md-row-hover" ng-repeat="Day in Report">
							<td md-cell class="md-cell-compress">{{ Day.Contrato }}</td>
							<td md-cell class="md-cell-compress">{{ Day.Dia }}</td>
							<td md-cell class="md-cell-compress">{{ Day.Tiempo }} seg</td>
							<td md-cell class="">{{ Day.mensaje }}</td>
						</tr>
					</tbody>
				</table>
			</md-table-container>
		</md-card>


	</div>

</div>