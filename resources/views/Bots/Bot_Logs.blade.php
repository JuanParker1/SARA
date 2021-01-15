<md-dialog flex=95 class="vh95" layout=column md-theme="Black">

	<div layout class="padding-left" layout-align="center center">
		<div class="md-subheader">{{ Bot.Nombre }}, Logs de Actividad</div>
		<md-datepicker ng-model="filters.Inicio"></md-datepicker>
		<md-datepicker ng-model="filters.Fin"></md-datepicker>
		<md-button class="md-icon-button" ng-click="getLogs()">
			<md-icon md-font-icon="fa-bolt"></md-icon>
		</md-button>
		<span flex></span>
		<md-button class="md-icon-button no-margin focus-on-hover" aria-label="Button" ng-click="Cancel()">
			<md-tooltip md-direction=left>Salir</md-tooltip>
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div flex layout=column class="overflow-y hasScroll">
		
		<md-table-container>
			<table md-table class="md-table-short" style="border: 1px solid #3e3e3e">
				<thead md-head>
					<tr md-row>
						<th md-column>Fecha</th>
						<th md-column>Paso</th>
						<th md-column>Estado</th>
						<th md-column>Mensaje</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="logrow_status_{{ L.Estado }}" ng-repeat="L in BotLogs" ng-click="L.open = !L.open">
						<td md-cell class="md-cell-compress">{{ L.created_at }}</td>
						<td md-cell class="md-cell-compress"><div layout class="w100p"><span class="text-clear">{{ L.paso.Tipo }}</span> {{ L.paso.Nombre }}</div></td>
						<td md-cell class="md-cell-compress">{{ L.Estado }}</td>
						<td md-cell class=""><div ng-class="{ 'md-truncate': !L.open }" class="w100p mxw700">{{ L.Mensaje }}</div></td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<div class="margin-top-20 text-center text-clear" ng-show="BotLogs.length == 0">Sin datos</div>

	</div>
	
	<style type="text/css">
		.logrow_status_Error{ background-color: #710000; }
	</style>

</md-dialog>