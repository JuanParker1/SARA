<div flex layout ng-controller="AppsCtrl" >
	
	<md-sidenav class="w300 no-margin  bg-white border-right no-overflow" layout=column
		md-is-locked-open="AppsSidenav" md-is-open="AppsSidenav">
	<div flex layout=column class="w300 overflow-y">
		<div layout class="border-bottom" style="height: 40px" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="padding: 0px 8px 0 3px"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterApps" class="no-padding">
			</div>
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-font-icon="fa-sort-amount-down fa-fw"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="changeAppOrder('Titulo')">Ordenar Alfabéticamente</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="changeAppOrder('-updated_at')">Ordenar por Modificado</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="changeAppOrder('-created_at')">Ordenar por Creado</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addApp()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll">
			<div ng-repeat="A in AppsCRUD.rows | filter:filterApps | orderBy: orderBy" ng-click="openApp(A)" ng-class="{'bg-lightgrey-5': A.id == AppSel.id}" layout
				class="border-bottom relative Pointer" md-ink-ripple>
				<div layout layout-align="center center" ng-style="{ backgroundColor: A.Color }"><md-icon md-font-icon="{{ A.Icono }} fa-fw s30" ng-style="{ color: A.textcolor }"></md-icon></div>
				<div flex class="padding-5 text-13px" layout layout-align="start center">{{ A.Titulo }}</div>
			</div>

			<div class="h50"></div>
		</div>
	</div>
	</md-sidenav>

	<div flex layout=column class="inherit-color" ng-show="AppSel">
		
		<div layout=column ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }" class="border-bottom">
			<div layout>
				<md-button class="md-icon-button no-margin no-padding s40" aria-label="b" ng-click="AppsSidenav = !AppsSidenav">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-button class="md-icon-button no-margin no-padding s40" aria-label="b" ng-click="changeIcon(AppSel, 'Icono')">
					<md-icon md-font-icon="{{ AppSel.Icono }} fa-fw fa-lg"></md-icon>
				</md-button>
				<md-input-container class="no-margin no-padding md-no-underline h40 lh40" flex>
					<input type="text" ng-model="AppSel.Titulo" aria-label=s class="h40 lh40">
				</md-input-container>
				<input type="color" ng-model="AppSel.Color" ng-change="changeTextColor()">
				
			</div>
		</div>

		<div flex layout class="bg-lightgrey-2">
			
			<div class="bg-white w250 border border-radius padding-5" layout=column style="margin: 5px 0 5px 5px">
				<div class=" md-subheader margin-bottom-5">Parámetros Generales</div>
				<div layout>
					<md-input-container flex>
						<label>Navegación</label>
						<md-select ng-model="AppSel.Navegacion" placeholder="">
							<md-option ng-value="Op" ng-repeat="Op in ['Superior','Izquierda','Inferior']">{{ Op }}</md-option>
						</md-select>
					</md-input-container>

					<md-input-container class="w50">
						<label>{{ AppSel.Navegacion == 'Izquierda' ? 'Ancho' : 'Altura' }}</label>
						<input type="number" ng-model="AppSel.ToolbarSize" min=0>
					</md-input-container>
				</div>

				<div class="md-subheader margin-bottom-5">Procesos</div>
				<div layout=column>
					<div ng-repeat="(kP, P) in AppSel.Procesos" class="padding-5" layout class="show-children-on-hover">
						<div ng-repeat="E in Procesos | filter:{ id: P }:true" flex md-truncate class="text-13px">{{ E.Proceso }}</div>
						<md-icon class="s20 child focus-on-hover Pointer" md-svg-icon="md-close" ng-click="removeProceso(kP)"></md-icon>
					</div>
				</div>
				<md-autocomplete 
					md-selected-item="selectedP" 
					md-selected-item-change="selectedProceso(item)"  
					md-search-text="searchText" 
					md-items="item in buscarProcesos(searchText)" 	md-item-text="item.Proceso"
					class="bg-lightgrey-5 h30"
					placeholder="Agregar Proceso">
					<md-item-template>
						<span md-highlight-text="searchText">{{ item.Proceso }}</span>
					</md-item-template>
					<md-not-found>No Encontrado</md-not-found>
				</md-autocomplete>


			</div>

			<div class="bg-white w170 border border-radius" layout=column style="margin: 5px 0 5px 5px">
				<div class="padding-5 md-subheader">Páginas</div>
				<div flex layout=column class="overflow-y darkScroll" as-sortable="dragListener" ng-model="PagesCRUD.rows">
					<div class="margin-left-5 Pointer lh15 text-15px" 
						ng-repeat="P in PagesCRUD.rows" 
						ng-class="{'text-bold': P.id == PageSel.id}" layout layout-align="center center"
						as-sortable-item>
						<md-button class="md-icon-button w30 mw30 h30 mh30 no-margin no-padding drag-handle" aria-label="b" 
							as-sortable-item-handle>
							<md-icon md-svg-icon="md-drag-handle"></md-icon>
						</md-button>
						<div flex ng-click="openPage(P)" class="Pointer padding-right-5">{{ P.Titulo }}</div>
					</div>
				</div>
			
				<md-button class="margin-5" aria-label="b" ng-click="addPage()">
					<md-icon md-font-icon="fa-plus"></md-icon>
					<md-tooltip md-direction=right>Agregar</md-tooltip>
				</md-button>
			</div>

			<div class="padding-5 overflow-y darkScroll" flex layout=column>
				@include('Apps.Apps_Page') 
			</div>

		</div>

		<div layout class="bg-white border-top" layout-align="center center">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="removePage()" class="md-warn"><md-icon md-font-icon="fa-trash margin-right-5 fa-fw"></md-icon>Remover Página "{{ PageSel.Titulo }}"</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="calcSlug()"><md-icon md-font-icon="fa-fingerprint margin-right-5 fa-fw"></md-icon>Cambiar URL</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<a class="text-clear h40 lh40" href="{{ Usuario.url }}#/a/{{ AppSel.Slug }}" target="_blank">{{ Usuario.url }}#/a/{{ AppSel.Slug }}</a>
			<span flex></span>
			<md-button class="mh30 h30 lh30 margin-5" ng-click="updateApp()" ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>










