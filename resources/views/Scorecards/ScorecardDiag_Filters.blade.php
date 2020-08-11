<div class="w30" style="padding-top: 1px; border-right: 1px solid #3c3c3c;">
	<div ng-repeat="S in SidenavIcons" layout layout-align="center center" 
		class="s30 Pointer relative" ng-class="{ 'text-clear': sidenavSel != S[1] }"
		ng-click="openSidenavElm(S)">
		<md-icon md-font-icon="{{ S[0] }} fa-fw"></md-icon>
		<md-tooltip md-direction="right" md-delay="500">{{ S[1] }}</md-tooltip>
		<div class="icon_indicator" ng-show="S[2]"></div>
	</div>
</div>

<md-sidenav class="w250 bg-black-4 no-overflow" layout=column style="border-right: 1px solid #3c3c3c;"
	md-is-locked-open="sidenavSel">

	<div flex layout=column class="w250" ng-show="sidenavSel == 'Filtros'">
		<div flex layout=column>
			
			<div class="h30 lh30 padding-left text-bold text-clear ng-binding">Filtros</div>

			<div layout class="h30 lh30 md-subheader padding-0-10">
				<div flex>Proceso</div>
				<md-button class="md-icon-button s30 no-margin no-padding " ng-click="filters.proceso_ruta = false" ng-show="filters.proceso_ruta">
					<md-icon md-font-icon="fas fa-eraser"></md-icon>
					<md-tooltip md-direction="left">Borrar</md-tooltip>
				</md-button>
			</div>
			

			<div ng-repeat="F in ProcesosFS" class="mh30 text-14px border-radius margin-0-5" ng-class="{ 'bg-darkgrey': F.route == filters.proceso_ruta }" 
				layout ng-show="F.show" >
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout  class="Pointer" >
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" 
						ng-class="{'fa-rotate-90':F.open, 'opacity-0': (F.children == 0) }" ng-click="FsOpenFolder(ProcesosFS, F)"></md-icon>
					<div flex class="Pointer" style="padding: 6px 0" ng-click="lookupProceso(F)">{{ F.name }}</div>
				</div>
			</div>

		</div>
		<md-button class="md-raised bg-ocean" ng-click="getScorecard(Sco.id, {})">
			<md-icon md-font-icon="fa-filter text-white margin-right"></md-icon>Filtrar
		</md-button>
	</div>
</md-sidenav>