<div flex id="Entidades" layout ng-controller="EntidadesCtrl">

	<md-sidenav class="w300 no-margin bg-white border-right no-overflow" layout=column
		md-is-locked-open="Storage.EntidadSidenav">
	<div flex class="w300" layout=column>
		
		<div layout class="border-bottom padding-left" layout-align="center center" style="height: 41px">
			<md-select ng-model="BddSel" flex class="md-no-underline no-margin" aria-label="s" ng-change="getEntidades()">
			  <md-option ng-repeat="Opt in Bdds" ng-value="Opt">
			  	<md-icon md-font-icon="fa-database"></md-icon>{{ Opt.Nombre }}
			  </md-option>
			</md-select>
		</div>

		<md-progress-linear md-mode="indeterminate" ng-show="EntidadesCRUD.ops.loading"></md-progress-linear>

		<div class="overflow-y darkScroll padding-top-5" flex=40>

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

		<div layout class="border-top" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 4px 5px 0 5px;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterEntidades" class="no-padding" ng-change="searchEntidades()" ng-model-options="{ debounce : 500 }">
			</div>
			<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="addEntidad()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Entidad</md-tooltip>
			</md-button>
		</div>

		<div class="overflow-y darkScroll border-top " flex layout=column>
			
			<md-subheader class="no-padding margin-top margin-left text-clear" 
				ng-repeat="P in Procesos | filter:{ id: ProcesoSelId }:true "
				ng-show="ProcesoSelId && (filterEntidades == '')">{{ P.Proceso }}</md-subheader>

			<div ng-repeat="E in getEntidadesFiltered() | orderBy:'Nombre'" class="padding-top padding-left Pointer" ng-click="openEntidad(E)"
				ng-class="{ 'text-bold': ( E.id == EntidadSel.id ) }">
				<div class="text-14px">{{ E.Nombre }}</div>
			</div>

			<div class="h50"></div>

		</div>

	</div>
	</md-sidenav>

	<div flex layout layout-align="center center" ng-show="loadingEntidad">
		<md-progress-circular md-diameter="52"></md-progress-circular>
	</div>

	<div flex layout=column class="bg-white md-short relative" ng-show="!loadingEntidad && EntidadSel">

		
		
	    <div layout class="border-bottom">

    		<md-button class="md-icon-button no-margin h40 mw40 w40" aria-label="Button" ng-click="Storage.EntidadSidenav = !Storage.EntidadSidenav">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<div class="text-bold lh40 margin-right" hide-xs md-truncate style="min-width: 149px">{{ EntidadSel.Nombre }}</div>
 
	    	<div ng-repeat="I in EntidadesSecciones" layout layout-align="center center" class="mw40 border-left relative SectionIcon" 
				md-ink-ripple ng-class="{ 'border-right': $last, 'SectionIcon_Selected': State.route[3] == I[0] }" 
				ng-click="navToSubsection(I[0])">
	    		<md-icon md-font-icon="{{ I[1] }} fa-fw SectionIcon_Icon"></md-icon>
	    		<div class="SectionIcon_Text">{{ I[0] }}</div>
	    		<md-tooltip md-direction="bottom" hide>{{ I[0] }}</md-tooltip>
	    	</div>

	    	<div layout flex></div>
			
			<md-button class="md-icon-button s40 text-clear" ng-show="!Storage.EntidadSelId" ng-click="fijarEntidad()">
	    		<md-icon md-font-icon="fa-thumbtack fa-rotate-90"></md-icon>
	    		<md-tooltip md-direction=left>Fijar</md-tooltip>
	    	</md-button>

	    	<md-button class="md-icon-button s40" ng-show="Storage.EntidadSelId" ng-click="Storage.EntidadSelId = false">
	    		<md-icon md-font-icon="fa-thumbtack"></md-icon>
	    		<md-tooltip md-direction=left>No Fijar</md-tooltip>
	    	</md-button>

		</div>

		<div flex layout class="bg-lightgrey-5" ui-view>
			<div flex layout layout-align="center center">
				<md-progress-circular md-diameter="48"></md-progress-circular>
			</div>
			
		</div>

	</div>

</div>

<style type="text/css">
	
	


</style>