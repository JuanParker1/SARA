<div flex id="Procesos" layout ng-controller="ProcesosCtrl" class="relative">
	

	<md-sidenav class="bg-white w300 border-right" layout=column 
		md-is-open="ProcesosNav"
		md-is-locked-open="$mdMedia('gt-xs') && ProcesosNav">
		
		<div layout class="border-bottom h30" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout hide>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
				<input flex type="search" placeholder="Buscar Proceso..." ng-model="filterProcesos" class="no-padding" ng-change="searchProceso()" ng-model-options="{ debounce : 500 }">
			</div>
			<div class="padding-left text-clear">Procesos</div>
			<span flex></span>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="createEmpresa()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Empresa</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in ProcesosFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout  class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open}" ng-click="FsOpenFolder(ProcesosFS, F)"></md-icon>
					<div flex class="Pointer" style="padding: 5px 0" ng-click="lookupProceso(F)">{{ F.name }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openProceso(F.file)" 
					ng-class="{ 'text-bold' : F.file.id == FuncionSel.id }">
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Proceso }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex class="" layout=column ng-show="ProcesoSel">

		<div layout class="padding-top-5 padding-left-5 h60">

			<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="ProcesosNav = !ProcesosNav" 
				style="margin-top: 2px !important">
				<md-icon md-svg-icon="md-bars" class=""></md-icon>
			</md-button>

			<md-input-container class=" no-margin-bottom md-title" flex>
				<input type="text" ng-model="ProcesoSel.Proceso" aria-label=s placeholder="Titulo">
			</md-input-container>
			<md-input-container class="w70">
				<input type="text" ng-model="ProcesoSel.CDC" aria-label=s placeholder="CDC">
			</md-input-container>
			<md-input-container class=" no-margin-bottom">
				<label>Tipo</label>
				<md-select ng-model="ProcesoSel.Tipo" aria-label=s>
					<md-option ng-repeat="Op in TiposProcesos" ng-value="Op.id">{{ Op.Nombre }}</md-option>
				</md-select>
			</md-input-container>
			<div class="text-clear" style="margin: 27px 7px 0;">de</div>
			<md-input-container class="no-margin-bottom">
				<md-tooltip md-direction="top">Padre</md-tooltip>
				<md-select ng-model="ProcesoSel.padre_id" aria-label=s>
					<md-option ng-value='null'>Empresa</md-option>
					<md-option ng-repeat="P in Procesos" ng-value="P.id" ng-show="P.id != ProcesoSel.id">{{ P.Proceso }}</md-option>
				</md-select>
			</md-input-container>
		</div>

		
		<div flex layout>
			<div flex class="overflow-y hasScroll">
				@include('Procesos.Procesos_Integrantes')

				<div layout=column class="margin-left-5 border border-radius bg-theme">
					<div class="md-subheader margin">Subprocesos</div>
					<div ng-repeat="P in Procesos | filter:{ padre_id: ProcesoSel.id }:true" class="padding-5-10 border-top Pointer text-13px" 
						ng-click="openProceso(P)">{{ P.Proceso }}</div>
				</div>

				<div class="h30"></div>

			</div>
			<div flex class="overflow-y hasScroll">
				<div layout=column class="margin-left-5 margin-right-5 border border-radius bg-theme">
					<div layout class="margin">
						<div class="md-subheader">Indicadores ({{ IndicadoresCRUD.rows.length }})</div>
					</div>
					<div ng-repeat="I in IndicadoresCRUD.rows" class="padding-left border-top text-13px" layout layout-align="center center">
						<div class="" flex>{{ I.Indicador }}</div>
						<md-button class="md-icon-button s30 no-margin no-padding" ng-click="viewIndicadorDiag(I.id)">
							<md-icon md-font-icon="fa-external-link-alt fa-fw"></md-icon>
						</md-button>
					</div>
				</div>

				<div class="h30"></div>

			</div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-button class="md-raised mh30 h30 lh30" ng-click="createSubproceso()">
				<md-icon md-svg-icon="md-plus" class="margin-right-5 s20"></md-icon>Crear Subproceso
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateProceso()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>