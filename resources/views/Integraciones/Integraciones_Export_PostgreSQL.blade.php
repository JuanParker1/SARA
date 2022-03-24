<div flex layout=column ng-controller="Integraciones_Export_PostgreSQLCtrl" md-theme="Black">
	
	<div layout layout-align="center center" class="padding-left">
		<div flex class="md-title text-thin">Exportar a PostgreSQL</div>
		<md-button class="md-raised" aria-label="Button" ng-click="runExport()">
			<md-icon md-font-icon="fa-bolt"></md-icon>
			Ejecutar
		</md-button>
	</div>

	<div flex layout=column class="padding overflow-y hasScroll">
		
		<div layout layout-wrap>
			<md-input-container class="w100">
				<label>Database</label>
				<input type="text" ng-model="filters.Database">
			</md-input-container>

			<md-input-container class="w110">
				<label>Database Op</label>
				<md-select ng-model="filters.DatabaseOp">
					<md-option ng-value="'create'">Crear</md-option>
					<md-option ng-value="'drop'">Recrear</md-option>
					<md-option ng-value="'none'">Ninguno</md-option>
				</md-select>
			</md-input-container>

			<md-input-container class="w100">
				<label>Schema</label>
				<input type="text" ng-model="filters.Schema">
			</md-input-container>
		</div>

		<md-table-container class="">
			<table md-table class="md-table-short table-col-compress">
				<thead md-head>
					<th md-column class="">
						<md-checkbox ng-model="allSelected" ng-change="markAll('selected', allSelected)" aria-label=c></md-checkbox>
					</th>
					<th md-column class="text-left">Entidad</th>
					<th md-column class="">
						<md-checkbox ng-model="allEstructura" ng-change="markAll('estructura', allEstructura)" aria-label=c></md-checkbox>
						Estructura
					</th>
					<th md-column class="">
						<md-checkbox ng-model="allDatos" ng-change="markAll('datos', allDatos)" aria-label=c></md-checkbox>
						Datos
					</th>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="E in EntidadesCRUD.rows" ng-class="{ 'opacity-20': !E.selected }">
						<td md-cell class="md-cell-compress">
							<md-checkbox ng-model="E.selected" aria-label=c></md-checkbox>
						</td>
						<td md-cell class="">{{ E.Nombre }} - {{ E.id }}</td>
						<td md-cell class="md-cell-compress text-left">
							<div class="w100">
								<md-checkbox ng-model="E.estructura" ng-disabled="!E.selected" aria-label=c></md-checkbox>
							</div>
						</td>
						<td md-cell class="md-cell-compress">
							<div class="w100">
								<md-checkbox ng-model="E.datos" ng-disabled="!E.selected" aria-label=c></md-checkbox>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<div class="h40"></div>
	</div>

</div>