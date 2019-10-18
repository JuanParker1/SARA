<div flex id="Entidades" layout ng-controller="EntidadesCtrl">

	<md-sidenav class="w250 no-margin bg-white border-right no-overflow" layout=column
		md-is-locked-open="EntidadSidenav">
	<div flex class="w250" layout=column>
		
		<div layout class="border-bottom padding-left" layout-align="center center" style="height: 41px">
			<md-select ng-model="BddSel" flex class="md-no-underline no-margin" aria-label="s">
			  <md-option ng-repeat="Opt in Bdds" ng-value="Opt">
			  	<md-icon md-font-icon="fa-database"></md-icon>{{ Opt.Nombre }}
			  </md-option>
			</md-select>
		</div>

		<div layout class="border-bottom" layout-align="center center" style="height: 41px">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterEntidades" class="no-padding" ng-change="searchEntidades()" ng-model-options="{ debounce : 500 }">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addEntidad()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Entidad</md-tooltip>
			</md-button>
		</div>

		<div class="overflow-y darkScroll padding-top-5" flex>

			<div ng-repeat="F in FsEntidades" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout ng-click="FsOpenFolder(FsEntidades, F)" class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition" ng-class="{'fa-rotate-90':F.open}"></md-icon>
					<div flex style="padding: 5px 0">{{ F.name }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openEntidad(F.file)" 
					ng-class="{ 'text-bold' : F.file.id == EntidadSel.id }">
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Nombre }}</div>
				</div>
			</div>

			<div class="h30"></div>
		</div>
	</div>
	</md-sidenav>

	<div flex layout layout-align="center center" ng-show="loadingEntidad">
		<md-progress-circular md-diameter="52"></md-progress-circular>
	</div>

	<div flex layout=column class="bg-white md-short relative" ng-show="!loadingEntidad && EntidadSel">

		
		
	    <div layout class="border-bottom">
			<md-button class="md-icon-button no-margin h40 w40" aria-label="Button" ng-click="EntidadSidenav = !EntidadSidenav">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<div class="text-bold lh40 margin-right" hide-xs>{{ EntidadSel.Nombre }}</div>
			<span flex></span>
			<md-tabs class="w310">
				<md-tab ng-repeat="S in ['General','Grids','Editores']" ng-click="navToSubsection(S)" label="{{ S }}" md-active="State.route[3] == S"></md-tab>
		    </md-tabs>
		</div>

		<div flex layout class="bg-lightgrey-5" ui-view>
			<div flex layout layout-align="center center">
				<md-progress-circular md-diameter="48"></md-progress-circular>
			</div>
			
		</div>

	</div>

</div>