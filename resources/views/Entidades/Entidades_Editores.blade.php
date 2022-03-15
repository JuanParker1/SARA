<div flex layout ng-controller="Entidades_EditoresCtrl">
	
	<div layout=column class="border-right w200 bg-white" ng-show="EditoresSidenav">

		<div layout class="border-bottom" layout-align="center center" style="height: 41px">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Editores" ng-model="filterEditores" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addEditor()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Editor</md-tooltip>
			</md-button>
		</div>

		<div class="h30 lh30 padding-left border-bottom relative Pointer" md-ink-ripple layout
			ng-repeat="G in EditoresCRUD.rows | filter:filterEditores" ng-click="openEditor(G)"
			ng-class="{'bg-lightgrey-5': G.id == EditorSel.id}" md-truncate>
			<md-icon hide md-font-icon="fa-table"></md-icon>
			<div flex class="text-12px">{{ G.Titulo }}</div>
		</div>

	</div>

	<div layout=column class="border-right" flex ng-show="EditorSel">
		
		<div flex layout=column class="padding overflow-y darkScroll border-radius">

			<div layout class="no-margin-bottom margin-top">

				<md-button class="md-icon-button s30 no-margin" aria-label="b" ng-click="EditoresSidenav = !EditoresSidenav" 
					style="transform: translate(-3px, -17px);">
					<md-icon md-svg-icon="md-bars"></md-icon>
				</md-button>
				<md-input-container class="no-margin" flex >
					<input type="text" ng-model="EditorSel.Titulo" placeholder="Titulo">
				</md-input-container>
				<md-input-container class="no-margin w60" >
					<input type="number" ng-model="EditorSel.Ancho" placeholder="Ancho">
				</md-input-container>
			</div>

			<md-chips class="compact margin-bottom" 
				md-transform-chip="addSeccion($chip)" md-removable="false"
				ng-model="EditorSel.Secciones" placeholder="Secciones">
				<md-chip-template>
					<md-button ng-click="markSeccionOpen($chip, $event)" class="md-icon-button no-margin focus-on-hover s20 transition" style="transform: translateY(-3px);">
						<md-icon md-font-icon="fa-chevron-right" ng-class="{ 'fa-rotate-90': $chip.open }"></md-icon>
					</md-button>
					{{$chip.nombre}}
					<md-button ng-click="editSeccion($chip, $event)" class="md-icon-button no-margin focus-on-hover s20" style="transform: translateY(-3px);">
						<md-icon md-font-icon="fa-edit"></md-icon>
					</md-button>
					<md-button md-chip-remove class="md-icon-button no-margin focus-on-hover s20" style="transform: translateY(-1px);">
						<md-icon md-svg-icon="md-close" class="s20"></md-icon>
					</md-button>
				</md-chip-template>
			</md-chips>
			
			<div class="bg-white border no-margin border-radius" layout=column>
				<div layout>
					<div flex layout class="Pointer" ng-click="showEditorCampos = !showEditorCampos">
						<md-icon md-font-icon="fa-chevron-right fa-fw s30" ng-class="{'fa-rotate-90': showEditorCampos}"></md-icon>
						<div flex class="md-subheader h30 lh30">Campos</div>
					</div>
				</div>
				<md-table-container ng-show="showEditorCampos">
					<table md-table class="md-table-short table-col-compress border-bottom" md-row-select multiple ng-model="EditoresCamposSel">
						<thead md-head ng-show="EditoresCamposCRUD.rows.length > 0">
							<tr md-row>
								<th md-column></th>
								<th md-column ng-show="EditorSel.Secciones.length > 0">Seccion</th>
								<th md-column>Campo</th>
								<th md-column>Etiqueta</th>
								<th md-column>Ancho</th>
								<th md-column><md-icon md-font-icon="fa-eye"><md-tooltip md-direction=top>Visible</md-tooltip></md-icon></th>
								<th md-column><md-icon md-font-icon="fa-edit"><md-tooltip md-direction=top>Editable</md-tooltip></md-icon></th>
								<th md-column>Opciones</th>
							</tr>
						</thead>
						<tbody md-body as-sortable="dragEditorListener" ng-model="EditoresCamposCRUD.rows">
							<tr md-row class="" ng-repeat="C in EditoresCamposCRUD.rows" ng-class="{ 'bg-yellow': C.changed }" md-select="C" md-select-id="id" as-sortable-item>
								<td md-cell class="md-cell-compress">
									<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding drag-handle" aria-label="b" as-sortable-item-handle>
										<md-icon md-svg-icon="md-drag-handle"></md-icon>
									</md-button>
								</td>
								<td md-cell class="md-cell-compress" ng-show="EditorSel.Secciones.length > 0">
									<md-select class="w100p" ng-model="C.seccion_id" aria-label=s ng-change="C.changed = true">
									  <md-option ng-value="null">Ninguna</md-option>
									  <md-option ng-value="k" ng-repeat="(k,S) in EditorSel.Secciones">{{ S.nombre }}</md-option>
									</md-select>
								</td>
								<td md-cell class="md-cell-compress">
									<div layout style="display: flex">
										<md-icon md-svg-icon="{{ TiposCampo[CamposCRUD.one(C.campo_id).Tipo].Icon }}" class="s15 margin-right-5"></md-icon>
										<div flex>{{ CamposCRUD.one(C.campo_id).campo_title }}</div>
									</div>
								</td>
								<td md-cell class="md-cell-compress">
									<md-input-container class="no-margin no-padding w100">
										<input type="text" ng-model="C.Etiqueta" ng-change="C.changed = true" aria-label=t>
									</md-input-container>
								</td>
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.Ancho" aria-label=s ng-change="C.changed = true">
									  <md-option ng-value="N" ng-repeat="N in anchosCampo">{{ N }}%</md-option>
									</md-select>
								</td>
								
								<td md-cell class="md-cell-compress"><md-checkbox ng-model="C.Visible" aria-label="c" class="md-primary" ng-change="C.changed = true; C.Editable = C.Visible" style="transform: translateX(4px);"></md-checkbox></td>
								<td md-cell class="md-cell-compress"><md-checkbox ng-model="C.Editable" aria-label="c" class="md-primary" ng-change="C.changed = true" ng-disabled="!C.Visible" style="transform: translateX(4px);"></md-checkbox></td>
								<td md-cell class="">
									{{ C.seccion_id }}
								</td>
							</tr>
						</tbody>
					</table>
					<div layout class="padding">

						<md-button class="border no-margin margin-right" ng-click="setSeccion()" 
							ng-show="EditoresCamposSel.length > 1 && EditorSel.Secciones.length > 0">
							<md-icon md-font-icon="fa-indent"></md-icon>
							Sección
						</md-button>

						<md-button class="border no-margin margin-right" ng-click="alinearCampos()" 
							ng-show="EditoresCamposSel.length > 1">
							<md-icon md-font-icon="fa-arrows-alt-h"></md-icon>
							Alinear
						</md-button>

						<md-button class="md-warn md-raised no-margin margin-right" aria-label="b" ng-click="removeEditorCampos()" ng-show="EditoresCamposSel.length > 0">
							<md-icon md-font-icon="fa-trash"></md-icon>
							Remover {{ EditoresCamposSel.length }}
						</md-button>

						<md-button class="border no-margin margin-right" ng-click="autogetEditorCampos()" ng-show="EditoresCamposSel.length == 0">
							<md-icon md-font-icon="fa-bolt fa-fw"></md-icon>Agregar Campos
						</md-button>
					</div>
				</md-table-container>
				
			</div>

			<div class="h30"></div>
		</div>


		<div layout class="border-top seam-top">
			
			<span flex></span>
			<md-button class="md-primary md-raised" ng-click="updateEditor()">
				<md-icon md-svg-icon="md-save" class="margin-right"></md-icon>Guardar
			</md-button>
		</div>


	</div>

	<div layout ng-show="EditorSel">

		<div layout=column class="w40 bg-white">
			
			<md-button class="no-margin md-icon-button s40 focus-on-hover transition" ng-repeat="I in editoresSubnavs" 
				ng-click="setEditoresSubnav(I[0])" ng-class="{ 'opacity-90': I[0] === editoresSubnav }">
				<md-icon md-font-icon="{{ I[1] }} fa-fw text-18px"></md-icon>
				<md-tooltip md-direction=left>{{ I[2] }}</md-tooltip>
			</md-button>

		</div>

		@include('Entidades.Entidades_Editores_Previsualizacion')
		@include('Entidades.Entidades_Editores_Validaciones')

	</div>

</div>