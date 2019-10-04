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

			<md-chips class="compact margin-bottom" ng-model="EditorSel.Secciones" placeholder="Secciones" md-enable-chip-edit></md-chips>
			
			<div class="bg-white border no-margin border-radius" layout=column>
				<div layout>
					<div flex layout class="Pointer" ng-click="showEditorCampos = !showEditorCampos">
						<md-icon md-font-icon="fa-chevron-right fa-fw s30" ng-class="{'fa-rotate-90': showEditorCampos}"></md-icon>
						<div flex class="md-subheader h30 lh30">Campos</div>
					</div>
					<md-button class="md-icon-button no-margin" aria-label="b" ng-click="autogetEditorCampos()">
						<md-tooltip md-direction="left">Importar Campos</md-tooltip>
						<md-icon md-font-icon="fa-bolt"></md-icon>
					</md-button>
					<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addEditorCampos()">
						<md-tooltip md-direction="left">Agregar Campos</md-tooltip>
						<md-icon md-font-icon="fa-plus"></md-icon>
					</md-button>
				</div>
				<md-table-container ng-show="showEditorCampos">
					<table md-table class="md-table-short table-col-compress">
						<thead md-head ng-show="EditoresCamposCRUD.rows.length > 0">
							<tr md-row>
								<th md-column>Seccion</th>
								<th md-column>Campo</th>
								<th md-column>Etiqueta</th>
								<th md-column>Ancho</th>
								<th md-column><md-icon md-font-icon="fa-eye"><md-tooltip>Visible</md-tooltip></md-icon></th>
								<th md-column>Opciones</th>
							</tr>
						</thead>
						<tbody md-body>
							<tr md-row class="" ng-repeat="C in EditoresCamposCRUD.rows" ng-class="{ 'bg-yellow': C.changed }">
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.seccion_id" aria-label=s ng-change="C.changed = true">
									  <md-option ng-value="null">Ninguna</md-option>
									  <md-option ng-value="k" ng-repeat="(k,S) in EditorSel.Secciones">{{ S }}</md-option>
									</md-select>
								</td>
								<td md-cell class="md-cell-compress">{{ CamposCRUD.one(C.campo_id).campo_title }}</td>
								<td md-cell class="md-cell-compress">
									<md-input-container class="no-margin no-padding w90">
										<input type="text" ng-model="C.Etiqueta" ng-change="C.changed = true" aria-label=t>
									</md-input-container>
								</td>
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.Ancho" aria-label=s ng-change="C.changed = true">
									  <md-option ng-value="N" ng-repeat="N in anchosCampo">{{ N }}%</md-option>
									</md-select>
								</td>
								
								<td md-cell class="md-cell-compress"><md-checkbox ng-model="C.Visible" aria-label="c" class="md-primary" ng-change="C.changed = true" style="transform: translateX(4px);"></md-checkbox></td>
								<td md-cell></td>
							</tr>
						</tbody>
					</table>
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

	<div layout=column class="padding" ng-show="EditorSel">
		
		<md-card class="no-margin" ng-style="{ width: EditorSel.Ancho }">
			<div class="padding text-bold text-clear" layout>{{ EditorSel.Titulo }}</div>
			<div layout=column class="">
				
				<div layout class="padding-0-10" layout-wrap>
					<div ng-repeat="C in EditoresCamposCRUD.rows | filter:{seccion_id:null, Visible:true}" ng-style="{ width: C.Ancho + '%' }" layout class="">

						<md-input-container class="margin-bottom" flex>
							<label>{{ C.Etiqueta || CamposCRUD.one(C.campo_id).campo_title }}</label><input type="text" value="&nbsp;" class="bg-lightgrey-5 border">
						</md-input-container>

					</div>
				</div>

				<div ng-repeat="(kS,S) in EditorSel.Secciones" layout=column>
					
					<div layout class="md-subheader padding-5">{{ S }}</div>
					<div layout class="padding-0-10" layout-wrap>
						<div ng-repeat="C in EditoresCamposCRUD.rows | filter:{seccion_id:kS, Visible:true}" ng-style="{ width: C.Ancho + '%' }" layout class="">

							<md-input-container class="margin-bottom" flex>
								<label>{{ C.Etiqueta || CamposCRUD.one(C.campo_id).campo_title }}</label><input type="text" value="&nbsp;" class="bg-lightgrey-5 border">
							</md-input-container>

						</div>
					</div>
				</div>

			</div>
			<div class="padding" layout><span flex></span>
				<md-button class="no-margin md-button md-raised md-primary" aria-label="b" disabled=true>Guardar</md-button>
			</div>
		</md-card>


	</div>

</div>