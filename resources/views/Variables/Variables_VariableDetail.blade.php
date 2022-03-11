<div flex layout=column class="padding-5 overflow-y darkScroll">
		<div layout class="">
			<md-button class="md-icon-button no-margin no-padding s30 no-show-on-dialog" aria-label="b" ng-click="Storage.VariablesNav = !Storage.VariablesNav" style="margin-top: 2px !important">
				<md-icon md-svg-icon="md-bars" class=""></md-icon>
			</md-button>
			<md-input-container class="margin-bottom" flex>
				<label>Titulo</label>
				<input type="text" ng-model="VarSel.Variable" aria-label=s>
			</md-input-container>
			<md-input-container class="margin-bottom">
				<label>Tipo</label>
				<md-select ng-model="VarSel.Tipo" aria-label=s>
					<md-option ng-value="Op" ng-repeat="Op in ['Manual', 'Calculado de Entidad']">{{ Op }}</md-option>
				</md-select>
			</md-input-container>
			<div class="w15"></div>
			<md-input-container class="margin-bottom">
				<label>Tipo Dato</label>
				<md-select ng-model="VarSel.TipoDato" aria-label=s>
					<md-option ng-value="Op" ng-repeat="Op in tiposDatoVar">{{ Op }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class="margin-bottom">
				<label>Decimales</label>
				<md-select ng-model="VarSel.Decimales" aria-label=s>
					<md-option ng-value="Op" ng-repeat="Op in [0,1,2]">{{ Op }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class=" margin-bottom">
				<label>Frecuencia</label>
				<md-select ng-model="VarSel.Frecuencia" aria-label=s>
					<md-option ng-repeat="(k,F) in Frecuencias" ng-value="k">{{ F }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class="margin-bottom">
				<label>Acumulada</label>
				<md-select ng-model="VarSel.Acumulada" aria-label=s class="w80">
					<md-option ng-value="'Si'">Si</md-option>
					<md-option ng-value="'No'">No</md-option>
				</md-select>
			</md-input-container>

			<md-button class="md-icon-button no-margin no-padding focus-on-hover s30 no-show-on-dialog"
	    		ng-click="closeVariable()">
	    		<md-icon md-svg-icon="md-close"></md-icon>
	    		<md-tooltip md-direction=left>Cerrar Variable</md-tooltip>
	    	</md-button>

			<md-button class="md-icon-button no-margin show-only-on-dialog" aria-label="Button" ng-click="Cancel()">
				<md-icon md-svg-icon="md-close"></md-icon>
			</md-button>
		</div>
		
		<div layout>
			<md-input-container class=" margin-bottom" flex>
				<textarea ng-model="VarSel.Descripcion" rows=1 placeholder="Descripción"></textarea>
			</md-input-container>

			<md-input-container class="margin-bottom-5">
				<md-tooltip>Proceso</md-tooltip>
				<md-select ng-model="VarSel.proceso_id" md-no-float>
					<md-select-header class="">
						<input ng-model="ProcesoSearch" type="search" placeholder="Proceso" class="text-15px" 
							ng-keydown="stopEv($event)">
					</md-select-header>
					<md-option ng-repeat="P in Procesos | filter:{ Proceso: ProcesoSearch }" ng-value="P.id">{{ P.Proceso }}</md-option>
				</md-select>
			</md-input-container>
		</div>
		

		<div class="bg-white border border-radius text-14px" layout=column ng-show="VarSel.Tipo == 'Calculado de Entidad'">
			<div class="md-subheader padding-but-bottom">Configuración de Entidad</div>
			<div layout layout-wrap class="padding-5">

				<div class="border-radius margin-5 padding-5-10 mw200 bg-lightgrey-5 border Pointer" ng-click="seleccionarEntidadGrid()">
					<div class="text-clear" ng-show="VarSel.grid_id === null">Seleccionar Entidad - Grid</div>
					<div class="" ng-show="VarSel.grid_id !== null" layout=column ng-repeat="G in Grids | filter:{ id:VarSel.grid_id }:true">
						<div class="">{{ G.entidad.Nombre }}</div>
						<div class="text-clear">{{ G.Titulo }}</div>
					</div>
				</div>

				<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=20 ng-show="VarSel.grid_id !== null">
					<label>Columna Periodo</label>
					<md-select ng-model="VarSel.ColPeriodo">
						<md-option ng-value="Op.id" ng-repeat="Op in VarSel.grid.columnas | include:['Periodo']:'tipo_campo' ">
							<md-icon md-svg-icon="{{ TiposCampo[Op.tipo_campo].Icon }}" class="margin-right-5 s20"></md-icon>{{ Op.column_title }}
						</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=15 ng-show="VarSel.grid_id !== null">
					<label>Agrupador</label>
					<md-select ng-model="VarSel.Agrupador">
						<md-option ng-value="Op.id" ng-repeat="Op in agregators">{{ Op.Nombre }}</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="margin-bottom-5" flex=50 flex-gt-sm=15 ng-show="VarSel.grid_id !== null">
					<label>Columna</label>
					<md-select ng-model="VarSel.Col">
						<md-option ng-value="Op.id" ng-repeat="Op in VarSel.grid.columnas">
							<md-icon md-svg-icon="{{ TiposCampo[Op.campo.Tipo].Icon }}" class="margin-right-5 s20"></md-icon>{{ Op.column_title }}
						</md-option>
					</md-select>
				</md-input-container>

				<div flex=100 layout=column class="padding-5 margin-top-5" ng-show="VarSel.Filtros.length > 0">
					<div class="md-subheader border-bottom padding-bottom-5" >Condiciones</div>
					<div layout ng-repeat="(kR,R) in VarSel.Filtros" class="mh30 lh30 border-bottom">
						<md-icon md-svg-icon="{{ TiposCampo[R.tipo_campo].Icon }}" class="margin-right-5 s20"></md-icon>
						<div class="margin-right mw150" layout layout-align="start center">{{ R.column_title }}</div>
						@include('Entidades.Entidades_Restricciones_Inputs')
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="viewDistinctValues(R)"><md-icon md-svg-icon="md-list"><md-tooltip md-direction=left>Ver Valores</md-tooltip></md-icon></md-button>
						<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" ng-click="removeArrayElm(VarSel.Filtros, kR)"><md-icon md-svg-icon="md-close"><md-tooltip md-direction=left>Remover</md-tooltip></md-icon></md-button>
					</div>
				</div>
				<md-input-container flex=100 class="no-margin" layout ng-show="VarSel.grid_id !== null">
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
						<tr md-row class="" ng-repeat="A in Anios">
							<td md-cell class="w30 text-bold">{{ A }}</td>
							<td md-cell class="text-right mw50 Pointer md-cell-hover" ng-repeat="M in Meses"
								ng-click="editValor2($event, A+M[0])">{{ VarSel.valores[A+M[0]].val }}</td>
						</tr>
					</tbody>
				</table>
			</md-table-container>
		</div>

		<div layout=column ng-show="VarSel.Tipo == 'Manual'" class="margin-top-20 padding-left-5">
			<div class="md-subheader">Opciones de Diligenciamiento</div>
			<p class="margin-top margin-bottom text-clear text-14px">Los valores de esta variable pueden ser editados por los usuarios en estos dias a partir de la fecha de cierre.</p>
			<div layout>
				<md-input-container flex>
					<label>Días Desde (Por Defecto: {{ Configuracion.VARIABLES_DIAS_DESDE.Valor }})</label>
					<input type="number" ng-model="VarSel.DiasDesde" autocomplete="off">
				</md-input-container>

				<md-input-container flex>
					<label>Días Hasta (Por Defecto: {{ Configuracion.VARIABLES_DIAS_HASTA.Valor }})</label>
					<input type="number" ng-model="VarSel.DiasHasta" autocomplete="off">
				</md-input-container>
			</div>
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
	<md-button class="border bg-white mh30 h30 lh30 no-margin-left" ng-click="getVariableData([VarSel.id], VarSel.Tipo, VarSel.Frecuencia)"
		ng-show="VarSel.Tipo !== 'Manual'">
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