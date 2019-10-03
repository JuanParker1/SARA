<div flex layout ng-controller="Entidades_GridsCtrl">
	
	<div layout=column class="border-right w200 bg-white">

		<div layout class="border-bottom" layout-align="center center" style="height: 41px">
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
			ng-class="{'bg-lightgrey-5': G.id == GridSel.id}" md-truncate>
			<md-icon hide md-font-icon="fa-table"></md-icon>
			<div flex class="text-12px">{{ G.Titulo }}</div>
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
			<div class="bg-white border-radius border Pointer text-13px padding-5 text-center margin-bottom relative" md-ink-ripple ng-click="addAllColumnas(CamposCRUD.rows, [EntidadSel.id], [null])">Agregar Todos >></div>
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

			<div layout class="margin-bottom">
				<md-input-container class="no-margin" flex md-no-float>
					<input type="text" ng-model="GridSel.Titulo" placeholder="Titulo">
				</md-input-container>
			</div>

			@include('Entidades.Entidades_Grids_Columnas')
			@include('Entidades.Entidades_Grids_Filtros')
			
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