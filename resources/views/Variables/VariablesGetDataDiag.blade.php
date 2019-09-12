<md-dialog flex=100 class="vh90 no-overflow margin-right-20 margin-left-20">

	<md-toolbar class="md-short bg-white border-bottom" md-theme="Snow_White">
		<div class="md-toolbar-tools">
			<h2><b>Actualizar Variables</b></h2>
			<div class="w20"></div>
			<md-datepicker class="compact lh30 h30 margin-right" ng-model="PeriodoIni" md-max-date="PeriodoFin" md-mode="month" md-date-locale="periodDateLocale" md-hide-icons="calendar" aria-label="f" ng-change="calcPeriodos()"></md-datepicker>
			<md-datepicker class="compact lh30 h30 margin-right" ng-model="PeriodoFin" md-min-date="PeriodoIni" md-mode="month" md-date-locale="periodDateLocale" md-hide-icons="calendar" aria-label="f" ng-change="calcPeriodos()"></md-datepicker>

			<md-button class="md-icon-button" aria-label="Button" ng-click="getCurrentData()" hide>
				<md-icon md-font-icon="fa-bolt"></md-icon>
			</md-button>
			<span flex></span>
			<md-select ng-model="Anio" aria-label="a">
			  <md-option ng-value="A" ng-repeat="A in Anios">{{ A }}</md-option>
			</md-select>
			<md-button class="md-icon-button" aria-label="Button" ng-click="Cancel()">
				<md-icon md-svg-icon="md-close"></md-icon>
			</md-button>
		</div>
	</md-toolbar>

	<div flex layout=column class="overflow-y hasScroll">
		<md-table-container class="border-bottom">
			<table md-table class="md-table-short table-col-compress" md-row-select="true" multiple ng-model="selectedRows">
				<thead md-head class="">
					<th class="text-left h30">Variable</th>
					<th ng-repeat="M in Meses" md-numeric class="text-right padding-right" ng-class="{ 'bg-lightgrey' : cellSelected(false,M) }">{{ M[1] }}</th>
					<th></th>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="V in Variables" md-select="V.id" md-select-id="id" ng-class="{ 'text-clear': !inArray(V.id, selectedRows) }">
						<td md-cell class="">{{ V.Variable }}</td>
						<td md-cell class="text-right mw50" ng-repeat="M in Meses" ng-class="{ 'bg-lightgrey' : cellSelected(V,M) }">
							<div layout=column>
								<div>{{ V.valores[Anio+M[0]].val }}</div>
								<div class="text-green text-bold">{{ V.newValores[Anio+M[0]].val }}</div>
							</div>
						</td>
						<td md-cell style="padding-right: 6px !important;"></td>
					</tr>
				</tbody>
			</table>
		</md-table-container>
	</div>
	<div layout class="border-top padding-5" layout-align="center center">
		<div class="w5"></div>
		<md-checkbox ng-model="overwriteValues" aria-label="c" class="no-margin md-primary">Sobreescribir Valores</md-checkbox>
		<div class="w10"></div>
		<md-button aria-label="b" class=" no-margin" ng-click="eraseData()">
			<md-icon md-font-icon="fa-times" class="margin-right-5"></md-icon>Borrar Datos
		</md-button>
		<span flex></span>
		<md-button aria-label="b" class=" md-raised no-margin" ng-click="startDownload()">
			<md-icon md-font-icon="fa-cloud-download-alt" class="margin-right-5"></md-icon>Obtener Datos
		</md-button>
		<div class="w10"></div>
		<md-button aria-label="b" class="md-primary md-raised no-margin" ng-click="storeVars()">
			<md-icon md-font-icon="fa-box-open" class="margin-right-5"></md-icon>Guardar Datos
		</md-button>
	</div>

</md-dialog>