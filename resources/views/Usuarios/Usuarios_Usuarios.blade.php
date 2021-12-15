<md-progress-linear ng-show="UsuariosCRUD.ops.loading"></md-progress-linear>

<div layout layout-align="center center" class="h40 padding-0-10" ng-show="!UsuariosCRUD.ops.loading">
	<md-button class="md-icon-button no-margin focus-on-hover s30 no-padding" ng-click="usuariosFiltersSidenav = !usuariosFiltersSidenav">
		<md-icon md-font-icon="fa-filter" class=""></md-icon>
		<md-tooltip md-direction="left">Filtros</md-tooltip>
	</md-button>
	<div class="text-bold text-clear margin-0-10" hide-xs>Usuarios ({{ Usuarios.length | number }})</div>
	<md-input-container class="md-no-underline md-icon-float no-margin" md-no-float flex style="padding-left: 25px">
		<md-icon md-svg-icon="md-search" class="s20"></md-icon>
		<input type="search" placeholder="Buscar..." ng-model="filterRows" ng-model-options="{ debounce: 300 }">
	</md-input-container>
	<md-button class="md-raised md-primary no-margin mh30 h30 lh30" ng-click="addUsuario()">
		<md-icon md-svg-icon="md-plus"></md-icon> Agregar
	</md-button>
</div>

<div flex layout>
	
	<md-sidenav layout=column class="w250 bg-lightgrey-5 padding-left padding-bottom overflow-y" 
		ng-show="!UsuariosCRUD.ops.loading"
		md-is-open="usuariosFiltersSidenav"
		md-is-locked-open="$mdMedia('gt-xs') && usuariosFiltersSidenav">

		<md-input-container class="margin-bottom-5">
			<label>Estado</label>
			<md-select ng-model="usuariosFilters.estado" aria-label=s class="no-margin md-no-underline" >
				<md-option ng-value="'A'">Activos</md-option>
				<md-option ng-value="'I'">Inactivos</md-option>
			</md-select>
		</md-input-container>

		<md-input-container class="no-margin-bottom">
			<label>Asignación</label>
			<md-select ng-model="usuariosFilters.asignacion" aria-label=s class="no-margin md-no-underline" ng-change="usuariosFilters.asignacion_id = 1">
				<md-option ng-value="''">Cualquiera</md-option>
				<md-option ng-value="'Unnasigned'">Sin Asignar</md-option>
				<md-option ng-value="'Asigned'">Asignados</md-option>
				<md-option ng-value="'Proceso'">Proceso</md-option>
				<md-option ng-value="'Perfil'">Perfil</md-option>
			</md-select>
		</md-input-container>

		<md-select ng-show="usuariosFilters.asignacion == 'Perfil'" ng-model="usuariosFilters.asignacion_id" aria-label=s class="no-margin md-no-underline" >
			<md-option ng-repeat="P in PerfilesCRUD.rows" ng-value="P.id">{{ P.Perfil }}</md-option>
		</md-select>

		<div ng-show="usuariosFilters.asignacion == 'Proceso'" layout=column class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in ProcesosFS" class="mh25 borders-bottom relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout  class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open}" ng-click="FsOpenFolder(ProcesosFS, F)"></md-icon>
					<div flex class="Pointer" style="padding: 5px 0" ng-click="usuariosFilters.asignacion_id = F.file.id"
						ng-class="{ 'text-bold' : F.file.id == usuariosFilters.asignacion_id }">{{ F.name }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="usuariosFilters.asignacion_id = F.file.id" 
					ng-class="{ 'text-bold' : F.file.id == usuariosFilters.asignacion_id }">
					<div flex style="padding: 5px 0 5px 24px" layout>
						<div flex>{{ F.file.Proceso }}</div>
					</div>
				</div>
			</div>

		</div>
		
		<span flex></span>

		<md-button class="bg-ocean md-raised no-margin" ng-click="getUsuarios()">
			Filtrar
		</md-button>

	</md-sidenav>

	<md-card flex class="border-radius margin-but-top overflow-y hasScroll" md-virtual-repeat-container 
		ng-show="!UsuariosCRUD.ops.loading && Usuarios.length > 0">
		<md-table-container class="border-bottom" >
			<table md-table class="md-table-short table-col-compress">
				<thead md-head md-order="orderBy">
					<tr md-row>
						<th md-column></th>
						<th md-column md-order-by="Nombres">Nombre</th>
						<th md-column md-order-by="Email">Email</th>
						<th md-column md-order-by="Documento">Documento</th>
						<th md-column md-order-by="Celular">Celular</th>
						<th md-column ng-repeat="(kOp, Op) in Configuracion.USUARIOS_OPS.Valor" md-order-by="{{ 'Op'+(kOp+1) }}">{{ Op }}</th>
						<th md-column>Asignación</th>
						<th md-column md-order-by="created_at">Creado</th>
						<th md-column md-order-by="last_login">Último Ingreso</th>
						<th md-column md-order-by="deleted_at" ng-show="usuariosFilters.estado == 'I'">Eliminado</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="" md-virtual-repeat="U in Usuarios | filter:filterRows | orderBy:orderBy">
						<td md-cell class="w110" style="padding-left: 0 !important;">
							<div layout layout-align="center center" class="w110">
								<md-button class="md-icon-button no-margin focus-on-hover s30 no-padding" ng-click="editUsuario(U)">
									<md-icon md-font-icon="fa-pencil-alt" class=""></md-icon>
									<md-tooltip md-direction="left">Editar</md-tooltip>
								</md-button>
								<md-button class="md-icon-button no-margin focus-on-hover s30 no-padding" ng-click="changePassword(U)">
									<md-icon md-font-icon="fa-key" class=""></md-icon>
									<md-tooltip md-direction="right">Cambiar Contraseña</md-tooltip>
								</md-button>
								<div class="s30 bg-lightgrey border-rounded margin-right-5 border Pointer" 
									style="background-image: url({{ U.avatar }}); background-size: cover; background-position: top center;"
									ng-click="changeAvatar(U)"></div>
							</div>
						</td>
						<td md-cell class="md-cell-compress mw200">{{ U.Nombres }}</td>
						<td md-cell class="md-cell-compress">{{ U.Email }}</td>
						<td md-cell class="md-cell-compress">{{ U.Documento }}</td>
						<td md-cell class="">{{ U.Celular }}</td>
						<td md-cell class="md-cell-compress" ng-show="Configuracion.USUARIOS_OPS.Valor.length >= 1">{{ U.Op1 }}</td>
						<td md-cell class="md-cell-compress" ng-show="Configuracion.USUARIOS_OPS.Valor.length >= 2">{{ U.Op2 }}</td>
						<td md-cell class="md-cell-compress" ng-show="Configuracion.USUARIOS_OPS.Valor.length >= 3">{{ U.Op3 }}</td>
						<td md-cell class="md-cell-compress" ng-show="Configuracion.USUARIOS_OPS.Valor.length >= 4">{{ U.Op4 }}</td>
						<td md-cell class="md-cell-compress" ng-show="Configuracion.USUARIOS_OPS.Valor.length >= 5">{{ U.Op5 }}</td>
						<td md-cell class="md-cell-compress">
							<div layout=column class="w100p">
								<div ng-repeat="A in U.asignacion">{{ A.proceso.Proceso }} <span class="text-clear">{{ A.perfil.Perfil }}</span></div>
							</div>
						</td>
						<td md-cell class="md-cell-compress">{{ U.created_at }}</td>
						<td md-cell class="md-cell-compress">{{ U.last_login }}</td>
						<td md-cell class="md-cell-compress" ng-show="usuariosFilters.estado == 'I'">{{ U.deleted_at }}</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>
	</md-card>

</div>

