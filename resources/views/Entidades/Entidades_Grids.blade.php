<div flex layout>
	
	<div layout=column class="border-right w200 bg-white">

		<div layout class="border-bottom h40" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Grids" ng-model="filterGrids" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addGrid()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Grid</md-tooltip>
			</md-button>
		</div>

		<div class="h30 lh30 padding-left border-bottom relative Pointer" md-ink-ripple layout
			ng-repeat="G in GridsCRUD.rows" ng-click="openGrid(G)"
			ng-class="{'bg-lightgrey-5': G.id == GridSel.id}">
			<md-icon md-font-icon="fa-table"></md-icon>
			<div flex>{{ G.Titulo }}</div>
		</div>

	</div>

	<div layout=column class="border-right w200  " ng-show="GridSel">
		<div layout class="border-bottom h40" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Campos Disponibles" ng-model="filterCamposDisponibles" class="no-padding text-13px">
			</div>
		</div>

		<div flex class="overflow-y darkScroll padding-5">
			<div ng-repeat="C in CamposCRUD.rows | filter:{ Visible: true } | filter:filterCamposDisponibles" layout class="bg-white border-radius border Pointer text-13px" >
				<div layout flex class="relative padding-5" md-ink-ripple ng-click="addColumna(C, [EntidadSel.id], [null])">
					<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
					<div flex class="lh20">{{ C.Alias !== null ? C.Alias : C.Columna }}</div>
				</div>
				<md-button class="md-icon-button s30 no-margin" aria-label="b" ng-click="verCamposDiag(C.Op1, [EntidadSel.id], [null,C.id])" ng-if="C.Tipo == 'Entidad'">
					<md-icon md-svg-icon="md-more-h"></md-icon>
				</md-button>
			</div>
			<div class="h30"></div>
		</div>

		
	</div>

	<div layout=column class="border-right" flex ng-show="GridSel">
		
		<div flex layout=column class="padding overflow-y darkScroll border-radius">

			<div layout class="border-bottom bg-white border-radius margin-bottom padding-but-top padding-top-5">
				<md-input-container class="no-margin-bottom" flex>
					<input type="text" ng-model="GridSel.Titulo" placeholder="Titulo">
				</md-input-container>
			</div>

			<md-subheader class="no-padding margin-bottom-5 md-no-sticky">Columnas</md-subheader>
			<div layout=column class="" as-sortable="dragListener2" ng-model="GridColumnasCRUD.rows">
				<div ng-repeat="Co in GridColumnasCRUD.rows" class="bg-white padding-5 border lh20" layout as-sortable-item
					ng-class="{ 'bg-yellow': Co.changed }">
					<md-button class="md-icon-button s20 no-margin margin-right-5 no-padding drag-handle" aria-label="b" as-sortable-item-handle>
						<md-icon md-font-icon="fa-grip-lines" class="s20"></md-icon>
					</md-button>

					<div class="text-clear" ng-repeat="E in Co.Ruta track by $index">
						<span>{{ getEntidad(E).Nombre  }}</span><md-icon md-font-icon="fa-chevron-right" class="s20 fa-fw"></md-icon>
					</div>

					<md-icon md-svg-icon="{{ TiposCampo[Co.campo.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
					<div flex>{{ Co.campo.Alias !== null ? Co.campo.Alias : Co.campo.Columna }}</div>
					<md-button class="md-icon-button s20 no-margin" aria-label="b" ng-click="removeColumna(Co)">
						<md-icon md-svg-icon="md-close" class="s20"></md-icon>
					</md-button>
				</div>
			</div>
			
			<div class="h30"></div>
		</div>


		<div layout class="border-top seam-top">
			<md-button class="md-primary" ng-click="testGrid(GridSel.id)">
				<md-icon md-font-icon="fa-flask" class="margin-right"></md-icon>Probar
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised" ng-click="updateGrid()">
				<md-icon md-svg-icon="md-save" class="margin-right"></md-icon>Guardar
			</md-button>
		</div>


	</div>

</div>