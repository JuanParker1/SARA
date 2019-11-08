<md-dialog class="vh95 well" aria-label="Campos">
	
	<div layout class="h30 padding-left bg-white">
		<div class="md-title lh30 no-margin" flex>{{ Entidad.Nombre }}</div>
		<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class="s20"></md-icon>
		</md-button>
	</div>

	<div layout class="border-bottom h40 bg-white" layout-align="center center">
		<div class="md-toolbar-searchbar" flex layout>
			<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
			<input flex type="search" placeholder="Campos Disponibles" ng-model="filterCamposDisponibles" class="no-padding text-13px">
		</div>
	</div>


	<div flex class="overflow-y darkScroll padding">
		<div ng-repeat="C in CamposCRUD.rows | filter:{ Visible: true } | filter:filterCamposDisponibles" layout class="bg-white border-radius border Pointer text-13px" >
			<div layout flex class="relative padding-5" md-ink-ripple ng-click="addColumna(C)">
				<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class="s20 margin-right-5"></md-icon>
				<div flex class="lh20">{{ C.Alias || C.Columna }}</div>
			</div>
			<md-button class="md-icon-button s30 no-margin no-padding" aria-label="b" ng-click="verCamposDiag(C.Op1, C.id)" ng-if="C.Tipo == 'Entidad'">
				<md-icon md-svg-icon="md-more-h"></md-icon>
			</md-button>
		</div>
		<div class="h30"></div>
	</div>

</md-dialog>