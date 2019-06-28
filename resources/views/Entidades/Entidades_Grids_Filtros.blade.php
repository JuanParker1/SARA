<md-subheader class="no-padding margin-bottom-5 md-no-sticky Pointer" ng-click="GridSel.hideFiltros = !GridSel.hideFiltros">
	<md-icon md-font-icon="fa-chevron-right fa-fw s20" ng-class="{'fa-rotate-90': !GridSel.hideFiltros }"></md-icon>Filtros
</md-subheader>
	
<md-table-container class="bg-white border border-radius" as-sortable="dragListener3" ng-model="GridFiltrosCRUD.rows" ng-hide="GridSel.hideFiltros">
	<table md-table class="md-table-short table-col-compress" md-row-select multiple ng-model="GridFiltrosCRUD.ops.selected">
		<tbody md-body>
			<tr md-row class="" ng-repeat="R in GridFiltrosCRUD.rows" ng-class="{ 'bg-yellow': R.changed }" as-sortable-item md-select="R" md-select-id="id">
				<td md-cell class="md-cell-compress">
					<md-button class="md-icon-button s30 no-padding drag-handle" aria-label="b" as-sortable-item-handle>
						<md-icon md-font-icon="fa-grip-lines" class="s30"></md-icon>
					</md-button>
				</td>
				<td md-cell class="md-cell-compress ">
					<div layout class="w100p"><md-icon md-svg-icon="{{ TiposCampo[R.campo.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
					<span flex>{{ R.columna.Cabecera || R.campo.Alias || R.campo.Columna }}</span></div>
				</td>
				<td md-cell>
					@include('Entidades.Entidades_Grids_Filtros_Inputs')
				</td>
			</tr>
		</tbody>
	</table>
</md-table-container>

<div layout ng-show="GridFiltrosCRUD.ops.selected.length > 0">
	<md-button class="md-raised md-warn" aria-label="b" ng-click="GridFiltrosCRUD.deleteMultiple()">
		<md-icon md-svg-icon="md-delete" class="s20 margin-right"></md-icon>Remover {{ GridFiltrosCRUD.ops.selected.length }} Filtros
	</md-button>
</div>

<div class="h15"></div>