<md-progress-linear ng-show="UsuariosCRUD.ops.loading"></md-progress-linear>

<div layout layout-align="center center" class="h40 padding-0-10" ng-show="!UsuariosCRUD.ops.loading">
	<div class="text-bold text-clear">Usuarios ({{ UsuariosCRUD.rows.length | number }})</div>
	<div class="w10"></div>
	<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
		<md-icon md-svg-icon="md-search" class="s20"></md-icon>
		<input type="search" placeholder="Buscar..." ng-model="filterRows" ng-model-options="{ debounce: 300 }">
	</md-input-container>
	<md-button class="md-raised md-primary no-margin mh30 h30 lh30">
		<md-icon md-svg-icon="md-plus"></md-icon> Agregar
	</md-button>
</div>

<md-card flex class="border-radius margin-but-top overflow-y hasScroll" md-virtual-repeat-container 
	ng-show="!UsuariosCRUD.ops.loading && UsuariosCRUD.rows.length > 0">
	<md-table-container class="border-bottom" >
		<table md-table class="md-table-short table-col-compress">
			<thead md-head md-order="orderBy">
				<tr md-row>
					<th md-column></th>
					<th md-column md-order-by="Nombres">Nombre</th>
					<th md-column md-order-by="Email">Email</th>
					<th md-column md-order-by="Documento">Documento</th>
					<th md-column md-order-by="created_at">Creado</th>
					<th md-column md-order-by="last_login">Último Ingreso</th>
				</tr>
			</thead>
			<tbody md-body>
				<tr md-row class="" md-virtual-repeat="U in UsuariosCRUD.rows | filter:filterRows | orderBy:orderBy">
					<td md-cell class="md-cell-compress">
						<div class="s30 bg-lightgrey border-rounded margin-right-5 border" 
							style="background-image: url({{ Usuario.avatar }}); background-size: cover; background-position: top center;"></div>
					</td>
					<td md-cell class="md-cell-compress mw200">{{ U.Nombres }}</td>
					<td md-cell class="md-cell-compress">{{ U.Email }}</td>
					<td md-cell class="">{{ U.Documento }}</td>
					<td md-cell class="md-cell-compress">{{ U.created_at }}</td>
					<td md-cell class="md-cell-compress"></td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</md-card>