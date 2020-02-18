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

		<div layout class="padding bg-white border margin-but-top border-radius" layout layout-wrap ng-show="CamposCRUD.rows.length > 0">
			<md-input-container class="" flex=15>
				<label>Llave Primaria</label>
				<md-select ng-model="EntidadSel.campo_llaveprim" aria-label="s">
					<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">{{  C.Alias || C.Columna }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class="no-margin-bottom" flex=15>
				<label>Ordenar Por</label>
				<md-select ng-model="EntidadSel.campo_orderby" aria-label="s">
					<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">{{  C.Alias || C.Columna }}</md-option>
				</md-select>
			</md-input-container>
			<md-input-container class="no-margin-bottom" flex=10>
				<md-select ng-model="EntidadSel.campo_orderbydir" aria-label="s" class="w100p">
					<md-option ng-value="'ASC'"> <md-icon class="s20" md-font-icon="fa-fw fa-arrow-up">  </md-icon></md-option>
					<md-option ng-value="'DESC'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-down"></md-icon></md-option>
				</md-select>
			</md-input-container>
			<md-input-container class="no-margin-bottom" flex=10>
				<input type="number" min="1" ng-model="EntidadSel.max_rows" aria-label=s placeholder="Filas Máximas">
			</md-input-container>
			<md-input-container class="no-margin-bottom" flex=10>
				<input type="number" min="0" ng-model="EntidadSel.config.search_minlen" aria-label=s placeholder="Min. Letras">
			</md-input-container>
			<md-input-container class="no-margin-bottom" flex=10>
				<input type="number" min="0" ng-model="EntidadSel.config.search_elms"   aria-label=s placeholder="Resultados">
			</md-input-container>

			<span flex=30></span>

			<md-input-container class="" flex=50 flex-gt-xs=20 ng-repeat="D in [1,2,3,4,5]">
				<label>Descripción {{D}}</label>
				<md-select ng-model="EntidadSel.config['campo_desc'+D]">
					<md-option ng-value="null">Ninguna</md-option>
					<md-option ng-repeat="C in CamposCRUD.rows" ng-value="C.id">{{  C.Alias || C.Columna }}</md-option>
				</md-select>
			</md-input-container>

		</div>

		@include('Entidades.Entidades_Restricciones') 
		

		<span flex class="mh30"></span>

	<!--</div>-->
	</div>

	<div layout=column layout-gt-xs=row class="border-top seam-top bg-white">
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