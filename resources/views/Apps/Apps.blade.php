<div flex layout ng-controller="AppsCtrl">
	
	<md-sidenav class="w300 no-margin  bg-white border-right no-overflow" layout=column
		md-is-locked-open="AppsSidenav" md-is-open="AppsSidenav">
	<div flex layout=column class="w300 overflow-y">
		<div layout class="border-bottom" style="height: 40px" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="padding: 0px 8px 0 3px"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterApps" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addApp()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll">
			<div ng-repeat="A in AppsCRUD.rows | filter:filterApps" ng-click="openApp(A)" ng-class="{'bg-lightgrey-5': A.id == AppSel.id}" layout
				class="border-bottom relative Pointer" md-ink-ripple>
				<div layout layout-align="center center" ng-style="{ backgroundColor: A.Color }"><md-icon md-font-icon="{{ A.Icono }} fa-fw s30" ng-style="{ color: A.textcolor }"></md-icon></div>
				<div flex class="padding-5 text-13px" layout layout-align="start center">{{ A.Titulo }}</div>
			</div>

			<div class="h30"></div>
		</div>
	</div>
	</md-sidenav>

	<div flex layout=column class="inherit-color">
		
		<div layout=column ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }" class="seam-bottom">
			<div layout>
				<md-button class="md-icon-button no-margin no-padding s40" aria-label="b" ng-click="AppsSidenav = !AppsSidenav">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-button class="md-icon-button no-margin no-padding w30" aria-label="b" ng-click="changeIcon()">
					<md-icon md-font-icon="{{ AppSel.Icono }} fa-fw fa-lg"></md-icon>
				</md-button>
				<md-input-container class="no-margin no-padding md-no-underline h40 lh40" flex>
					<input type="text" ng-model="AppSel.Titulo" aria-label=s class="h40 lh40">
				</md-input-container>
				<input type="color" ng-model="AppSel.Color" ng-change="changeTextColor()">
			</div>
			<div>A</div>
		</div>

		<div flex layout></div>

		<div layout class="border-top bg-lightgrey-5">
			<span flex></span>
			<md-button class="md-raised mh30 h30 lh30" ng-click="updateApp()" ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>










