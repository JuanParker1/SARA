<div layout class="padding-top-5 padding-right" layout-align="center center">
	<div class="md-title lh35 margin-left" flex> Proveedores de Listas</div>
	<md-button class="no-margin" ng-click="browseListas()" ng-show="ListasCRUD.rows.length > 0">
		<md-icon md-font-icon="fa-list-alt text-15px margin-right-5"></md-icon>Explorar Listas
	</md-button>
	<div class="w10"></div>
	<md-button ng-click="addLista()" class="no-margin md-raised h30 mh30 lh30">
		<md-icon md-svg-icon="md-plus" class="margin-right-5"></md-icon>Agregar Lista
	</md-button>
</div>

<md-card layout=column flex>
<md-table-container flex ng-show="ListasCRUD.rows.length > 0">
	<table md-table class="md-table-short table-col-compress border-bottom">
		<thead md-head>
			<tr md-row>
				<th md-column></th>
				<th md-column>Nombre</th>
				<th md-column>Tabla Indice</th>
				<th md-column>Códigos</th>
				<th md-column>Descripciónes</th>
				<th md-column>Tabla Detalles</th>
				<th md-column>Llave</th>
				<th md-column>Det. Códigos</th>
				<th md-column>Det. Descripciónes</th>
			</tr>
		</thead>
		<tbody md-body>
			<tr md-row class="" ng-repeat="L in ListasCRUD.rows">
				<td md-cell class="md-cell-compress">
					<md-button class="md-icon-button no-margin no-padding s30" ng-click="editLista(L)">
						<md-icon md-font-icon="fa-pencil-alt text-15px"></md-icon>
					</md-button>
				</td>
				<td md-cell class="md-cell-compress">{{ L.Nombre }}</td>
				<td md-cell class="md-cell-compress">{{ L.Indice }}</td>
				<td md-cell class="md-cell-compress">{{ L.IndiceCod }}</td>
				<td md-cell class="md-cell-compress">{{ L.IndiceDes }}</td>
				<td md-cell class="md-cell-compress">{{ L.Detalle }}</td>
				<td md-cell class="md-cell-compress">{{ L.Llave }}</td>
				<td md-cell class="md-cell-compress">{{ L.DetalleCod }}</td>
				<td md-cell class="">{{ L.DetalleDes }}</td>
			</tr>
		</tbody>
	</table>
</md-table-container>
</md-card>