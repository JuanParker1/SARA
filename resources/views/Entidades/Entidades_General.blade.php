<div layout=column flex ng-if="EntidadSel">
	
	<div flex layout=column class="overflow-y darkScroll">
	<!--<div flex layout=column class="wu800">-->

		<div class="margin">
			<div layout>
					
				<md-input-container flex class="margin-bottom-5">
					<input type="text" ng-model="EntidadSel.Nombre" placeholder="Nombre" required>
				</md-input-container>

				<md-input-container class="margin-bottom-5">
					<label>Tipo</label>
					<md-select ng-model="EntidadSel.Tipo" required aria-label=s>
						<md-option ng-repeat="T in ['Tabla','Vista']" ng-value="T">{{ T }}</md-option>
					</md-select>
				</md-input-container>

				<md-input-container flex=20 class="margin-bottom-5">
					<label>{{ EntidadSel.Tipo }}</label>
					<input type="text" ng-model="EntidadSel.Tabla" required>
				</md-input-container>

				<md-input-container class="margin-bottom-5">
					<label>Proceso</label>
					<md-select ng-model="EntidadSel.proceso_id">
						<md-select-header class="demo-select-header">
							<input ng-model="ProcesoSearch" type="search" placeholder="Buscar.." class="text-15px" 
								ng-keydown="stopEv($event)">
						</md-select-header>
						<md-option ng-repeat="P in Procesos | filter:{ Proceso: ProcesoSearch }" ng-value="P.id">{{ P.Proceso }}</md-option>
					</md-select>
				</md-input-container>

			</div>
		</div>

		@include('Entidades.Entidades_Campos') 

		<div class="bg-white border margin-but-top border-radius" layout=column ng-show="CamposCRUD.rows.length > 0">
			<md-subheader class="no-padding margin md-no-sticky">Configuración</md-subheader>
			<div layout layout-wrap class="padding-but-top">
				<md-input-container class="no-margin-bottom w180">
					<label>Llave Primaria</label>
					<md-select ng-model="EntidadSel.campo_llaveprim" aria-label="s">
						<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">
							<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class=""></md-icon>
							{{  C.Alias || C.Columna }}
						</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="no-margin-bottom w180">
					<label>Ordenar Por</label>
					<md-select ng-model="EntidadSel.campo_orderby" aria-label="s">
						<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">
							<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class=""></md-icon>
							{{  C.Alias || C.Columna }}
						</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="no-margin-bottom">
					<md-select ng-model="EntidadSel.campo_orderbydir" aria-label="s" class="">
						<md-option ng-value="'ASC'"> <md-icon class="s20" md-font-icon="fa-fw fa-arrow-up">  </md-icon></md-option>
						<md-option ng-value="'DESC'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-down"></md-icon></md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="no-margin-bottom w120">
					<input type="number" min="1" ng-model="EntidadSel.max_rows" aria-label=s placeholder="Filas Máximas">
				</md-input-container>
			</div>
		</div>

		<div class="bg-white border margin-but-top border-radius" layout=column>
			<md-subheader class="no-padding margin md-no-sticky Pointer" ng-click="showTarjetaBusqueda = !showTarjetaBusqueda">
				<md-icon md-font-icon="fa-chevron-right fa-fw s20" ng-class="{'fa-rotate-90': showTarjetaBusqueda}"></md-icon>
				<span style="transform: translateY(1px);display: inline-block;">Tarjeta de Búsqueda</span>
				<md-icon md-svg-icon="md-info-outline" class="s15">
					<md-tooltip md-direction=right>Usada cuando esta entidad se use dentro de otra entidad</md-tooltip>
				</md-icon>
			</md-subheader>
			<div layout=column layout-gt-xs=row class="padding-but-top" ng-show="showTarjetaBusqueda">
				
				<div layout layout-gt-xs=column class="margin-right">
					<md-input-container class="no-margin-bottom w80">
						<input type="number" min="0" ng-model="EntidadSel.config.search_minlen" aria-label=s placeholder="Min. Letras">
						<md-tooltip md-direction=right>Mínimo de letras que se deben escribir para iniciar la búsqueda</md-tooltip>
					</md-input-container>
					<md-input-container class="no-margin-bottom w80">
						<input type="number" min="0" ng-model="EntidadSel.config.search_elms"   aria-label=s placeholder="Resultados">
						<md-tooltip md-direction=right>Número de resultados de la búsqueda</md-tooltip>
					</md-input-container>
				</div>

				<div class="bg-lightgrey-5 border border-radius padding-5" flex layout layout-wrap>
					<md-input-container class="no-margin-bottom" flex=50  ng-class="{ 'text-clear': EntidadSel.config['campo_desc'+k] == null }"
						ng-repeat="k in [1,2,3,4]">
						<label>Descripción {{k}}</label>
						<md-select ng-model="EntidadSel.config['campo_desc'+k]">
							<md-option ng-value="null">Ninguna</md-option>
							<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">
								<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class=""></md-icon>
								{{  C.Alias || C.Columna }}
							</md-option>
						</md-select>
					</md-input-container>
					<md-input-container class="no-margin-bottom" flex=100 ng-class="{ 'text-clear': EntidadSel.config.campo_desc5 == null }" ng-show="EntidadSel.config.campo_desc4 != null">
						<label>Descripción 5</label>
						<md-select ng-model="EntidadSel.config.campo_desc5">
							<md-option ng-value="null">Ninguna</md-option>
							<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">
								<md-icon md-svg-icon="{{ TiposCampo[C.Tipo].Icon }}" class=""></md-icon>
								{{  C.Alias || C.Columna }}
							</md-option>
						</md-select>
					</md-input-container>
				</div>

				
			</div>
		</div>

		@include('Entidades.Entidades_Restricciones') 

		<span flex class="mh30"></span>

	</div>

	<div layout=column layout-gt-xs=row layout-align="center center" class="border-top seam-top bg-white">

		<md-menu>
			<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin s40" aria-label="m">
				<md-icon md-svg-icon="md-more-v"></md-icon>
			</md-button>
			<md-menu-content>
				<md-menu-item><md-button ng-click="seeCreateStatement()"><md-icon md-font-icon="fa-terminal margin-right fa-fw"></md-icon>Ver Instrucción SQL</md-button></md-menu-item>
			</md-menu-content>
		</md-menu>

		<md-button class="md-warn md-raised" aria-label="b" ng-click="removeCampos()"
			ng-show="camposSel.length > 0">
			<md-icon md-font-icon="fa-trash"></md-icon>
			Remover {{ camposSel.length }} {{ camposSel.length > 1 ? 'Campos' : 'Campo' }}
		</md-button>

		<span flex></span>
		<md-button class="md-primary" aria-label="b" ng-click="getCamposAuto()">
			<md-icon md-font-icon="fa-bolt margin-right-5"></md-icon>Obtener Campos
		</md-button>
		<md-button class="md-primary md-raised" ng-click="updateEntidad()">
			<md-icon md-svg-icon="md-save" class="margin-right"></md-icon>Guardar
		</md-button>
	</div>

</div>