<div flex id="Variables" layout ng-controller="VariablesCtrl">
		
	<md-sidenav class="bg-white border-right w350" layout=column 
		md-is-open="Storage.VariablesNav"
		md-is-locked-open="$mdMedia('gt-xs') && Storage.VariablesNav">

		<div layout=column flex>

			<div layout class="padding-5 border-bottom"><md-icon md-font-icon="fa-superscript fa-fw margin-right-5 fa-lg"></md-icon><div class="lh25 text-bold" flex>Variables</div></div>

			<md-progress-linear md-mode="indeterminate" ng-show="VariablesCRUD.ops.loading"></md-progress-linear>

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
					<md-menu class="child">
						<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin margin-right-5 no-padding s20" aria-label="m">
							<md-icon md-svg-icon="md-more-h" class="s20"></md-icon>
						</md-button>
						<md-menu-content class="no-padding">
							<md-menu-item><md-button ng-click="getFolderVarData(F)"><md-icon md-font-icon="fa-cloud-download-alt margin-right fa-fw"></md-icon>Obtener Datos</md-button></md-menu-item>
							<md-menu-item><md-button ng-click="addVariable(F.route)"><md-icon md-font-icon="fa-plus margin-right fa-fw"></md-icon>Agregar Variable</md-button></md-menu-item>
						</md-menu-content>
					</md-menu>
				</div>

				<div class="h30"></div>
			</div>

			<div layout class="border-top h30" layout-align="center center" ng-show="!VariablesCRUD.ops.loading">
				<div class="md-toolbar-searchbar" flex layout>
					<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
					<input flex type="search" placeholder="Buscar Variable..." ng-model="filterVariables" class="no-padding" ng-change="searchVariable()" ng-model-options="{ debounce : 500 }">
				</div>
				<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addVariable()">
					<md-icon md-svg-icon="md-plus"></md-icon>
					<md-tooltip md-direction=left>Agregar Variable</md-tooltip>
				</md-button>
			</div>

			<div class="overflow-y darkScroll border-top " flex layout=column>
			
				<md-subheader class="no-padding margin-top margin-left text-clear" 
					ng-repeat="P in Procesos | filter:{ id: ProcesoSelId }:true "
					ng-show="ProcesoSelId && (filterVariables == '')">{{ P.Proceso }}</md-subheader>

				<div ng-repeat="V in getVariablesFiltered() | orderBy:'Variable'" class="padding-top padding-left Pointer" 
					ng-click="openVariable(V)"
					ng-class="{ 'text-bold': ( V.id == VarSel.id ) }">
					<div class="text-14px">{{ V.Variable }}</div>
				</div>

				<div class="h50"></div>

			</div>

		</div>

	</md-sidenav>

	<div flex layout=column ng-show="VarSel !== null" class="padding-right-5">

		@include('Variables.Variables_VariableDetail')

	</div>

</div>