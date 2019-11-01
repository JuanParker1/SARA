<md-subheader class="no-padding margin-bottom-5 md-no-sticky Pointer" ng-click="GridSel.hideBotones = !GridSel.hideBotones">
	<md-icon md-font-icon="fa-chevron-right fa-fw s20" ng-class="{'fa-rotate-90': !GridSel.hideBotones }"></md-icon>Botones
</md-subheader>

<div class="bg-white border border-radius margin-bottom-5" ng-hide="GridSel.hideBotones">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Botones Principales</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right-5" aria-label="b" ng-click="addButton('main_buttons', { icono: 'fa-plus', texto: 'Nuevo', accion: 'Editor (Crear)', accion_element: '', accion_element_id: null })">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Botón</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="" ng-show="GridSel.Config.main_buttons.length > 0">
		<table md-table class="md-table-short table-col-compress">
			<tbody md-body>
				<tr md-row class="" ng-repeat="(iB,B) in GridSel.Config.main_buttons">
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="changeIcon(B, 'icono')">
							<md-icon md-font-icon="{{ B.icono }} fa-fw"></md-icon>
						</md-button>
					</td>
					<td md-cell class="md-cell-compress no-padding">
						<md-input-container class="w80 no-padding">
							<input type="text" ng-model="B.texto" aria-label="t">
						</md-input-container>
					</td>
					<td md-cell class="md-cell-compress ">
						<md-select ng-model="B.accion" aria-label="s">
							<md-option ng-repeat="Op in ['Editor (Crear)']" ng-value="Op">{{ Op }}</md-option>
						</md-select>
					</td>
					<td md-cell class="" layout>

						<md-autocomplete flex
							md-selected-item="selectedItem"
							md-search-text="B.accion_element"
							md-selected-item-change="selectElm(item, B)"
							md-items="item in queryElm(B.accion_element, B.accion)"
							md-item-text="item.display"
							md-min-length="0"
							placeholder="Buscar elemento" class="h30">
							<md-item-template>
								<span md-highlight-text="B.accion_element" md-highlight-flags="^i">{{item.display}}</span>
							</md-item-template>
							<md-not-found>No encontrado</md-not-found>
						</md-autocomplete>

						<md-button class="md-icon-button" aria-label="b" ng-click="configEditor(B)">
							<md-icon md-svg-icon="md-settings" class="s20"></md-icon>
							<md-tooltip md-direction=left>Configuración</md-tooltip>
						</md-button>

					</td>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button s30 no-padding focus-on-hover" aria-label="b" ng-click="removeButton('main_buttons', iB)">
							<md-icon md-font-icon="fa-times"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>


<div class="bg-white border border-radius" ng-hide="GridSel.hideBotones">
	<div class="h30" layout layout-align="center center">
		<div class="md-subheader margin-left margin-right">Botones de Fila</div>
		<span flex></span>
		<md-button class="md-icon-button no-margin no-padding s30 margin-right-5" aria-label="b" ng-click="addButton('row_buttons', { icono: 'fa-pencil-alt', texto: 'Editar', accion: 'Editor (Editar)', accion_element: '', accion_element_id: null })">
			<md-icon md-svg-icon="md-plus"></md-icon>
			<md-tooltip md-direction="left">Agregar Botón</md-tooltip>
		</md-button>
	</div>
	<md-table-container class="" ng-show="GridSel.Config.row_buttons.length > 0">
		<table md-table class="md-table-short table-col-compress">
			<tbody md-body>
				<tr md-row class="" ng-repeat="(iB,B) in GridSel.Config.row_buttons">
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="changeIcon(B, 'icono')">
							<md-icon md-font-icon="{{ B.icono }} fa-fw"></md-icon>
						</md-button>
					</td>
					<td md-cell class="md-cell-compress no-padding">
						<md-input-container class="w80 no-padding">
							<input type="text" ng-model="B.texto" aria-label="t">
						</md-input-container>
					</td>
					<td md-cell class="md-cell-compress ">
						<md-select ng-model="B.accion" aria-label="s">
							<md-option ng-repeat="Op in ['Editor (Crear)', 'Editor (Editar)']" ng-value="Op">{{ Op }}</md-option>
						</md-select>
					</td>
					<td md-cell class="">
						<md-autocomplete 
							md-selected-item="selectedItem"
							md-search-text="B.accion_element"
							md-selected-item-change="selectElm(item, B)"
							md-items="item in queryElm(B.accion_element, B.accion)"
							md-item-text="item.display"
							md-min-length="0"
							placeholder="Buscar elemento" class="h30">
							<md-item-template>
								<span md-highlight-text="B.accion_element" md-highlight-flags="^i">{{item.display}}</span>
							</md-item-template>
							<md-not-found>No encontrado</md-not-found>
						</md-autocomplete>
					</td>
					<td md-cell class="md-cell-compress">
						<md-button class="md-icon-button s30 no-padding focus-on-hover" aria-label="b" ng-click="removeButton('row_buttons', iB)">
							<md-icon md-font-icon="fa-times"></md-icon>
						</md-button>
					</td>
				</tr>
			</tbody>
		</table>
	</md-table-container>
</div>

<div class="h10"></div>