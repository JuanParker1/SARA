<md-content flex id="Indicadores" layout ng-controller="IndicadoresCtrl" class="no-bg">
	
	<md-sidenav class="bg-white border-right w300" layout=column 
		md-is-open="Storage.IndicadoresNav"
		md-is-locked-open="$mdMedia('gt-xs') && Storage.IndicadoresNav">

		<div layout=column flex>

			<div layout class="padding-5 border-bottom"><md-icon md-font-icon="fa-chart-line fa-fw margin-right-5 fa-lg"></md-icon><div class="lh25 text-bold" flex>Indicadores</div></div>

			<md-progress-linear md-mode="indeterminate" ng-show="!IndicadoresLoaded"></md-progress-linear>

			<div class="overflow-y darkScroll" flex=40>

				<div ng-repeat="F in ProcesosFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
					md-ink-ripple layout ng-show="F.show">
					<div ng-style="{ width: (F.depth * 12) }"></div>
					<div ng-show="F.type == 'folder'" flex layout  class="Pointer">
						<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open }" ng-click="FsOpenFolder(ProcesosFS, F)"></md-icon>
						<div flex class="Pointer" style="padding: 5px 0" ng-click="openProceso(F.file)"
							ng-class="{ 'text-bold' : F.file.id == ProcesoSelId }">{{ F.name }}</div>
					</div>
					<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openProceso(F.file)" 
						ng-class="{ 'text-bold' : F.file.id == ProcesoSelId }">
						<div flex style="padding: 5px 0 5px 24px" layout>
							<div flex>{{ F.file.Proceso }}</div>
						</div>
					</div>
				</div>

				<div class="h30"></div>
			</div>

			<div layout class="border-top h30" layout-align="center center" ng-show="!IndicadoresCRUD.ops.loading">
				<div class="md-toolbar-searchbar" flex layout>
					<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
					<input flex type="search" placeholder="Buscar Indicador..." ng-model="filterIndicadores" class="no-padding" ng-change="searchIndicador()" ng-model-options="{ debounce : 500 }">
				</div>
				<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addIndicador()">
					<md-icon md-svg-icon="md-plus"></md-icon>
					<md-tooltip md-direction=left>Agregar Indicador</md-tooltip>
				</md-button>
			</div>

			<div class="overflow-y darkScroll border-top " flex layout=column style="padding: 3px">
			
				<md-subheader class="no-padding margin-top-5 margin-bottom-5 margin-left text-clear" 
					ng-repeat="P in Procesos | filter:{ id: ProcesoSelId }:true "
					ng-show="ProcesoSelId && (filterIndicadores == '')">{{ P.Proceso }}</md-subheader>

				<div ng-repeat="V in getIndicadoresFiltered() | orderBy:'Ruta'" class="padding-top-5 padding-bottom-5 padding-left Pointer" 
					ng-click="openIndicador(V)" ng-class="{ 'text-bold bg-lightgrey-5 border-radius': ( V.id == IndSel.id ) }">
					<div class="text-14px">{{ V.Indicador }}</div>
					<div class="text-clear text-13px" ng-hide="ProcesoSelId && (filterIndicadores == '')">{{ V.proceso.Proceso }}</div>
				</div>

				<div class="h50"></div>

			</div>

		</div>

	</md-sidenav>

	<div flex class="" layout=column ng-show="IndSel !== null">
		<div flex layout=column class="overflow-y darkScroll padding-5">
			<div layout class="">
				<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="Storage.IndicadoresNav = !Storage.IndicadoresNav" 
					style="margin-top: 2px !important">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-input-container class=" margin-bottom" flex>
					<input type="text" ng-model="IndSel.Indicador" aria-label=s placeholder="Indicador">
				</md-input-container>
				<md-input-container class=" margin-bottom">
					<label>Proceso</label>
					<md-select ng-model="IndSel.proceso_id" aria-label=s>
						<md-option ng-repeat="P in Procesos | filter:{ Proceso: ProcesoSearch }" ng-value="P.id">{{ P.Proceso }}</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class=" margin-bottom">
					<label md-direction="top">Tipo de Dato</label>
					<md-select ng-model="IndSel.TipoDato" aria-label=s>
						<md-option ng-repeat="Op in tiposDatoInd" ng-value="Op">{{ Op }}</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class=" margin-bottom w50">
					<label md-direction="top">Dec.</label>
					<input type="number" ng-model="IndSel.Decimales" aria-label=s min=0>
				</md-input-container>
				<md-input-container class=" margin-bottom">
					<label md-direction="top">Sentido</label>
					<md-select ng-model="IndSel.Sentido" aria-label=s>
						<md-option ng-value="'ASC'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-up"></md-icon></md-option>
						<md-option ng-value="'RAN'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-right"></md-icon></md-option>
						<md-option ng-value="'DES'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-down"></md-icon></md-option>
					</md-select>
				</md-input-container>

				<md-input-container class=" margin-bottom">
					<label>Análisis</label>
					<md-select ng-model="IndSel.FrecuenciaAnalisis" aria-label=s>
						<md-option ng-repeat="(k,F) in Frecuencias" ng-value="k">{{ F }}</md-option>
					</md-select>
				</md-input-container>

			</div>

			<div layout>
				<md-input-container flex class="margin-top-5 margin-bottom">
					<textarea ng-model="IndSel.Definicion" rows=1 placeholder="Definición"></textarea>
				</md-input-container>
			</div>

			

			<div flex layout layout-align="start start" class="overflow">
				<div layout=column class="mw350  margin-right">
					@include('Indicadores.Indicadores_Formula')
					@include('Indicadores.Indicadores_VariablesProceso')
					
				</div>
				@include('Indicadores.Indicadores_Metas')
				@include('Indicadores.Indicadores_Tableros')
			</div>
			
			<!-- include('Indicadores.Indicadores_Desagregacion') -->
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="openIndicador(IndSel)"><md-icon md-font-icon="fa-sync-alt margin-right fa-fw"></md-icon>Recargar Indicador</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="copyIndicador()"><md-icon md-font-icon="fa-copy margin-right fa-fw"></md-icon>Copiar Indicador</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="viewIndicadorDiag(IndSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateIndicador()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</md-content>