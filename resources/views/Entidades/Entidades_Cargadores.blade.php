<div flex layout ng-controller="Entidades_CargadoresCtrl">
	
	<div layout=column class="border-right w200 bg-white" ng-show="CargadoresSidenav">

		<div layout class="border-bottom" layout-align="center center" style="height: 41px">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Cargadores" ng-model="filterCargadores" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addCargador()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Cargador</md-tooltip>
			</md-button>
		</div>

		<div class="h30 lh30 padding-left border-bottom relative Pointer" md-ink-ripple layout
			ng-repeat="G in CargadoresCRUD.rows | filter:filterCargadores" ng-click="openCargador(G)"
			ng-class="{'bg-lightgrey-5': G.id == CargadorSel.id}" md-truncate>
			<md-icon hide md-font-icon="fa-table"></md-icon>
			<div flex class="text-12px">{{ G.Titulo }}</div>
		</div>

	</div>


	<div layout=column class="border-right" flex ng-show="CargadorSel">
		
		<div flex layout=column class="padding overflow-y darkScroll border-radius">

			<div layout class="no-margin-bottom margin-top">

				<md-button class="md-icon-button s30 no-margin " aria-label="b" ng-click="CargadoresSidenav = !CargadoresSidenav" 
					style="transform: translate(-3px, -17px);">
					<md-icon md-svg-icon="md-bars"></md-icon>
				</md-button>
				<md-input-container class="no-margin no-padding" flex >
					<input type="text" ng-model="CargadorSel.Titulo" placeholder="Titulo" class="no-padding">
				</md-input-container>

				<md-select ng-model="CargadorSel.Config.tipo_archivo" class="no-margin" aria-label="s">
					<md-option ng-value="kOp" ng-repeat="(kOp,Op) in TiposArchivo">
						<md-icon md-font-icon="{{ Op[1] }} fa-fw" style="transform: translateY(3px);"></md-icon>{{ Op[0] }}
					</md-option>
				</md-select>

				<div layout ng-if="CargadorSel.Config.tipo_archivo == 'csv'">
					
					<md-input-container class="w80 no-margin no-padding">
						<input type="text" ng-model="CargadorSel.Config.delimiter" placeholder="Separador">
					</md-input-container>

				</div>

				<md-switch ng-model="CargadorSel.Config.with_headers" aria-label="s" class="no-margin padding-0-10 md-primary">
					{{ CargadorSel.Config.with_headers ? 'Con' : 'Sin' }} Cabeceras
				</md-switch>

			</div>

			<div class="h10"></div>

			<div class="bg-white border no-margin border-radius" layout=column>
				<md-table-container class="">
					<table md-table class="md-table-short table-col-compress">
						<thead md-head>
							<tr md-row>
								<th md-column></th>
								<th md-column>Campo</th>
								<th md-column><md-icon md-font-icon="fa-asterisk">	 <md-tooltip md-direction=up>Requerido</md-tooltip></md-icon></th>
								<th md-column><md-icon md-font-icon="fa-fingerprint"><md-tooltip md-direction=up>Unico</md-tooltip></md-icon></th>
								<th md-column>Valor</th>
								<th md-column></th>
							</tr>
						</thead>
						<tbody md-body ng-repeat="R in CargadorSel.Config.campos">
							<tr md-row class="" ng-repeat="C in [CamposCRUD.one(R.campo_id)]">
								<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-key fa-fw" ng-if="C.id == EntidadSel.campo_llaveprim"></md-icon></td>
								<td md-cell class="md-cell-compress">
									<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class="s15"></md-icon>
									<div style="display:inline">{{ C.campo_title }}</div>
								</td>
								<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-check fa-fw" ng-if="C.Requerido"></md-icon></td>
								<td md-cell class="md-cell-compress"><md-icon md-font-icon="fa-check fa-fw" ng-if="C.Unico"></md-icon></td>
								<td md-cell class="md-cell-compress">
									<md-select ng-model="R.tipo_valor" class="w100p" aria-label="s">
										<md-option ng-repeat="Op in TiposValor" ng-value="Op">{{ Op }}</md-option>
									</md-select>
								</td>
								<td md-cell>
									<div layout ng-if="R.tipo_valor == 'Columna'">
										<md-input-container class="no-margin no-padding w70" md-no-float>
											<input type="number" ng-model="R.Defecto" placeholder="Columna" min="1" class="no-padding">
										</md-input-container>

										<md-input-container class="no-margin no-padding w120" md-no-float ng-if="inArray(C.Tipo, ['Fecha','Hora','fechaHora'])">
											<input type="text" ng-model="R.formato" placeholder="Formato" class="no-padding">
										</md-input-container>
									</div>

									<md-select ng-model="R.Defecto" aria-label=s ng-if="R.tipo_valor == 'Variable de Sistema'">
										<md-option ng-repeat="Op in VariablesSistema" ng-value="Op">{{ Op }}</md-option>
									</md-select>

								</td>
							</tr>
						</tbody>
					</table>
				</md-table-container>
			</div>



			
		</div>


		<div layout class="border-top seam-top" layout-align="center center">

			<md-button class="md-icon-button no-margin s40" aria-label="b" ng-click="viewCargadorDiag(CargadorSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
			</md-button>

			<span flex></span>
			<md-button class="md-primary md-raised" ng-click="updateCargador()">
				<md-icon md-svg-icon="md-save" class="margin-right"></md-icon>Guardar
			</md-button>
		</div>


	</div>

</div>