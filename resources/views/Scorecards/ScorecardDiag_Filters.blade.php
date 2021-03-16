<div class="w40" style="padding-top: 1px; border-right: 1px solid #3c3c3c;">
	<div ng-repeat="S in SidenavIcons" layout layout-align="center center" 
		class="s40 Pointer relative" ng-class="{ 'text-clear': sidenavSel != S[1] }"
		ng-click="openSidenavElm(S)">
		<md-icon md-font-icon="{{ S[0] }} fa-fw"></md-icon>
		<md-tooltip md-direction="right" md-delay="500">{{ S[1] }}</md-tooltip>
		<div class="icon_indicator" ng-show="S[2]"></div>
	</div>
</div>

<md-sidenav class="w250 bg-black-4 no-overflow" layout=column style="border-right: 1px solid #3c3c3c;"
	md-is-locked-open="sidenavSel">

	<div flex layout=column class="w250" ng-show="sidenavSel == 'Filtros'">
		<div flex layout=column class="overflow-y hasScroll">
			
			<div class="h40 lh40 padding-left text-clear ng-binding margin-bottom" layout layout-align="center center">
				<md-icon md-font-icon="fa-filter"></md-icon><div flex>Filtros</div>
				<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="sidenavSel = ''">
					<md-icon md-svg-icon="md-close" class=""></md-icon>
				</md-button>
			</div>

			<div class="md-subheader padding-0-10">Periodo de Análisis</div>
			<div layout>
				<md-datepicker ng-model="PeriodoDate" md-mode="month" class="periodoDatepicker" md-max-date="MaxDate"
					md-date-locale="{ formatDate: formatPeriodo, parseDate: parsePeriodo }" ng-change="getPeriodoParts()"></md-datepicker>
			</div>

			<div layout class="h30 lh30 md-subheader padding-0-10">
				<div flex>Cumplimiento</div>
				<md-button class="md-icon-button s30 no-margin no-padding " ng-click="filters.cumplimiento = false" 
					ng-show="filters.cumplimiento">
					<md-icon md-font-icon="fas fa-eraser"></md-icon>
					<md-tooltip md-direction="left">Borrar Filtro</md-tooltip>
				</md-button>
			</div>
			<div layout style="padding: 0 10px 0" class="margin-bottom">
				<div ng-repeat="C in filtrosCumplimiento" flex layout layout-align="center center" 
					class="Pointer padding-5 border-radius" 
					ng-click="filters.cumplimiento = C[0]"
					ng-class="{ 'bg-darkgrey' : C[0] == filters.cumplimiento }">
					<div class="s20 border-rounded" style="background-color: {{ C[2] }}"></div>
					<md-tooltip>{{ C[1] }}</md-tooltip>
				</div>
			</div>

			<div layout class="h30 lh30 md-subheader padding-0-10" style="transform: translateY(6px);">
				<div flex>Proceso</div>
				<md-button class="md-icon-button s30 no-margin no-padding " ng-click="filters.proceso_ruta = false" ng-show="filters.proceso_ruta">
					<md-icon md-font-icon="fas fa-eraser"></md-icon>
					<md-tooltip md-direction="left">Borrar Filtro</md-tooltip>
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
		<md-button class="no-margin-bottom" ng-click="clearCache()"  ng-show="Usuario.id == 183">Borrar Cache</md-button>
		<md-button class="md-raised bg-ocean" ng-click="getScorecard(Sco.id, {})">
			<md-icon md-font-icon="fa-filter text-white margin-right"></md-icon>Filtrar
		</md-button>
	</div>


	<div flex layout=column class="w250" ng-show="sidenavSel == 'Descargar'">
		<div flex layout=column class="overflow-y hasScroll">

			<div class="h40 lh40 padding-left text-clear ng-binding margin-bottom" layout layout-align="center center">
				<md-icon md-font-icon="fa-sign-in-alt fa-rotate-90 fa-fw"></md-icon><div flex>Descargar</div>
				<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="sidenavSel = ''">
					<md-icon md-svg-icon="md-close" class=""></md-icon>
				</md-button>
			</div>

			<div flex layout=column class="padding-0-10">
				<span flex></span>
				<md-button class="no-margin h40 md-raised bg-warmblue"
					ng-click="downloadIndicadores()">Descargar Datos</md-button>
				<span flex></span>
			</div>
			

		</div>
	</div>



</md-sidenav>