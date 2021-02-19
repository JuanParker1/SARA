<div layout=column 	ng-show="GridColumnasCRUD.rows.length > 0">

	<md-subheader class="no-padding margin-bottom-5 md-no-sticky Pointer" ng-click="GridSel.hideCols = !GridSel.hideCols">
		<md-icon md-font-icon="fa-chevron-right fa-fw s20" ng-class="{'fa-rotate-90': !GridSel.hideCols }"></md-icon>Columnas
	</md-subheader>

	<md-table-container class="bg-white border border-radius" as-sortable="dragListener2" ng-model="GridColumnasCRUD.rows" ng-hide="GridSel.hideCols">
		<table md-table class="md-table-short table-col-compress" md-row-select multiple ng-model="GridColumnasCRUD.ops.selected">
			<tbody md-body>
				<tr md-row class="" ng-repeat="Co in GridColumnasCRUD.rows" ng-class="{ 'bg-yellow': Co.changed }" as-sortable-item md-select="Co" md-select-id="id">
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button s30 no-padding drag-handle" aria-label="b" as-sortable-item-handle>
							<md-icon md-font-icon="fa-grip-lines" class="s30"></md-icon>
						</md-button>
					</td>
					<td md-cell class="">
						<div layout layout-align="start center" class="text-13px">
						<div class="text-clear" ng-repeat="E in Co.Ruta track by $index">
							<span class="">{{ getEntidad(E).Nombre  }}</span><md-icon md-font-icon="fa-chevron-right" class="s20 fa-fw text-12px"></md-icon>
						</div>
						<div flex layout>
							<md-icon md-svg-icon="{{ TiposCampo[Co.campo.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
							<div flex md-truncate>{{ Co.Cabecera || Co.campo.Alias || Co.campo.Columna }}</div>
						</div>
					</td>
					<td md-cell class="md-cell-compress">
						
						<md-button class="md-icon-button s20 focus-on-hover" aria-label="b" ng-click="editColumna(Co)">
							<md-icon md-font-icon="fa-edit fa-fw s20"></md-icon>
							<md-tooltip md-direction="left">Editar</md-tooltip>
						</md-button>

						<md-button class="md-icon-button s20 focus-on-hover" aria-label="b" ng-click="Co.Visible = !Co.Visible; markChanged(Co)">
							<md-icon md-font-icon="fa-eye fa-fw s20" ng-class="{ 'fa-eye-slash': !Co.Visible }"></md-icon>
							<md-tooltip md-direction="left">Mostrar / Ocultar</md-tooltip>
						</md-button>

						<md-button class="md-icon-button s20 focus-on-hover" aria-label="b" ng-click="addFiltro(Co)">
							<md-icon md-font-icon="fa-filter fa-fw s20"></md-icon>
							<md-tooltip md-direction="left">Agregar Filtro</md-tooltip>
						</md-button>

					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>

	<div layout ng-show="GridColumnasCRUD.ops.selected.length > 0">
		<md-button class="md-raised md-warn" aria-label="b" ng-click="removerColumnas()">
			<md-icon md-svg-icon="md-delete" class="s20 margin-right"></md-icon>Remover {{ GridColumnasCRUD.ops.selected.length }} Columnas
		</md-button>
	</div>

	<div class="h10"></div>

</div>