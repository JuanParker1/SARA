<div flex id="Variables" layout ng-controller="VariablesCtrl">
		
	<md-sidenav class="bg-white border-radius border margin-5 w350" layout=column 
		md-is-open="VariablesNav"
		md-is-locked-open="$mdMedia('gt-xs') && VariablesNav">
		
		<div layout class="border-bottom h30" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
				<input flex type="search" placeholder="Buscar Variable..." ng-model="filterVariables" class="no-padding" ng-change="searchVariable()" ng-model-options="{ debounce : 500 }">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addVariable()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Variable</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in VariablesFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout ng-click="FsOpenFolder(VariablesFS, F)" class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition" ng-class="{'fa-rotate-90':F.open}"></md-icon>
					<div flex style="padding: 5px 0">{{ F.name }}</div>
					<md-menu class="child">
						<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin margin-right-5 no-padding s20" aria-label="m">
							<md-icon md-svg-icon="md-more-h" class="s20"></md-icon>
						</md-button>
						<md-menu-content class="no-padding">
							<md-menu-item><md-button ng-click="getFolderVarData(F)"><md-icon md-font-icon="fa-cloud-download-alt margin-right fa-fw"></md-icon>Obtener Datos</md-button></md-menu-item>
							<md-menu-item hide><md-button ng-click="renameFolder(F)"><md-icon md-font-icon="fa-pencil-alt margin-right fa-fw"></md-icon>Renombrar Carpeta</md-button></md-menu-item>
						</md-menu-content>
					</md-menu>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openVariable(F.file)" 
					ng-class="{ 'text-bold' : F.file.id == VarSel.id }">
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Variable }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex layout=column ng-show="VarSel !== null" class="padding-right-5">

		<div flex layout=column class="padding-5 overflow-y darkScroll">
				<div layout class="">
					<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="VariablesNav = !VariablesNav" style="margin-top: 2px !important">
						<md-icon md-svg-icon="md-bars" class=""></md-icon>
					</md-button>
					<md-input-container class="no-margin-top margin-bottom" flex>
						<input type="text" ng-model="VarSel.Variable" aria-label=s>
					</md-input-container>
					<md-input-container class="no-margin-top margin-bottom">
						<md-select ng-model="VarSel.Tipo" aria-label=s>
							<md-option ng-value="Op" ng-repeat="Op in ['Valor Fijo', 'Calculado de Entidad']">{{ Op }}</md-option>
						</md-select>
					</md-input-container>
					<div class="w15"></div>
					<md-input-container class="no-margin-top margin-bottom">
						<md-select ng-model="VarSel.TipoDato" aria-label=s>
							<md-option ng-value="Op" ng-repeat="Op in tiposDatoVar">{{ Op }}</md-option>
						</md-select>
					</md-input-container>
					<md-input-container class="no-margin-top margin-bottom">
						<md-tooltip>Decimales</md-tooltip>
						<md-select ng-model="VarSel.Decimales" aria-label=s>
							<md-option ng-value="Op" ng-repeat="Op in [0,1,2]">{{ Op }}</md-option>
						</md-select>
					</md-input-container>
				</div>
				
				<md-input-container class="no-margin-top margin-bottom" md-no-float>
					<textarea ng-model="VarSel.Descripcion" rows=1 placeholder="Descripción"></textarea>
				</md-input-container>

				<md-input-container class="no-margin-top margin-bottom" md-no-float>
					<input ng-model="VarSel.Ruta"></textarea>
				</md-input-container>

				<div class="bg-white border border-radius text-14px" layout=column ng-show="VarSel.Tipo == 'Calculado de Entidad'">
					<div class="md-subheader padding-but-bottom">Configuración de Entidad</div>
					<div layout layout-wrap class="padding-5">
						<md-input-container class="margin-bottom-5 md-no-float" flex=50 flex-gt-sm=40 >
							<label>Entidad - Grid</label>
							<md-select ng-model="VarSel.grid_id">
								<md-option ng-value="Op.id" ng-repeat="Op in Grids">
									<span class="text-clear">{{ Op.entidad.Nombre }} - </span><span class="">{{ Op.Titulo }}</span>
								</md-option>
							</md-select>
						</md-input-container>
						<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=20>
							<label>Columna Periodo</label>
							<md-select ng-model="VarSel.ColPeriodo">
								<md-option ng-value="Op.id" ng-repeat="Op in VarSel.grid.columnas | include:['Periodo','Fecha','FechaHora']:'tipo_campo' ">
									<md-icon md-svg-icon="{{ TiposCampo[Op.tipo_campo].Icon }}" class="margin-right-5 s20"></md-icon>{{ Op.column_title }}
								</md-option>
							</md-select>
						</md-input-container>
						<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=20>
							<label>Agrupador</label>
							<md-select ng-model="VarSel.Agrupador">
								<md-option ng-value="Op.id" ng-repeat="Op in agregators">{{ Op.Nombre }}</md-option>
							</md-select>
						</md-input-container>
						<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=20>
							<label>Columna</label>
							<md-select ng-model="VarSel.Col">
								<md-option ng-value="Op.id" ng-repeat="Op in VarSel.grid.columnas">
									<md-icon md-svg-icon="{{ TiposCampo[Op.campo.Tipo].Icon }}" class="margin-right-5 s20"></md-icon>{{ Op.column_title }}
								</md-option>
							</md-select>
						</md-input-container>

						<div flex=100 layout=column class="padding-5 margin-top-5" ng-show="VarSel.Filtros.length > 0">
							<div class="md-subheader border-bottom padding-bottom-5" >Condiciones</div>
							<div layout ng-repeat="(kR,R) in VarSel.Filtros" class="h30 lh30 border-bottom">
								<md-icon md-svg-icon="{{ TiposCampo[R.tipo_campo].Icon }}" class="margin-right-5 s20"></md-icon>
								<div class="margin-right mw150">{{ R.column_title }}</div>
								@include('Entidades.Entidades_Restricciones_Inputs')
								<span flex></span>
								<md-button class="md-icon-button no-margin no-padding s30" ng-click="removeArrayElm(VarSel.Filtros, kR)"><md-icon md-svg-icon="md-close"></md-icon></md-button>
							</div>
						</div>
						<md-input-container flex=100 class="no-margin" layout>
							<md-select ng-model="newFiltro" placeholder="Agregar Filtro" ng-change="addFiltro()">
							  <md-option ng-value="Op" ng-repeat="Op in VarSel.grid.columnas">
							  		<md-icon md-svg-icon="{{ TiposCampo[Op.campo.Tipo].Icon }}" class="margin-right-5 s20"></md-icon>{{ Op.column_title }}
							  </md-option>
							</md-select>
						</md-input-container>
					</div>
				</div>

				<div class="bg-white border border-radius margin-top">
					<div class="md-subheader padding">Valores</div>
					<md-table-container class="">
						<table md-table class="md-table-short table-col-compress">
							<thead>
								<th class="text-left padding-left ">Año</th>
								<th ng-repeat="M in Meses" md-numeric class="text-right padding-right">{{ M[1] }}</th>
							</thead>
							<tbody md-body>
								<tr md-row class="" ng-repeat="A in [AnioActual-2, AnioActual-1, AnioActual]">
									<td md-cell class="w30 text-bold">{{ A }}</td>
									<td md-cell class="text-right mw50 Pointer md-cell-hover" ng-repeat="M in Meses"
										ng-click="editValor(A+M[0])">{{ VarSel.valores[A+M[0]].val }}</td>
								</tr>
							</tbody>
						</table>
					</md-table-container>
				</div>

				<div class="h50"></div>

		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="openVariable(VarSel)"><md-icon md-font-icon="fa-sync-alt margin-right fa-fw"></md-icon>Recargar Variable</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="copyVar()"><md-icon md-font-icon="fa-copy margin-right fa-fw"></md-icon>Copiar Variable</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="border bg-white mh30 h30 lh30 no-margin-left" ng-click="getVariableData([VarSel.id])">
				<md-icon md-font-icon="fa-cloud-download-alt" class="margin-right s20 fa-lg" style="transform: translateY(1px) translateX(-3px);"></md-icon>Obtener Datos
			</md-button>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="viewVariableDiag(VarSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateVariable()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>