<div class="w30" style="padding-top: 1px;">
	<div ng-repeat="S in SidenavIcons" layout layout-align="center center" 
		class="s30 Pointer relative" ng-class="{ 'text-clear': sidenavSel != S[1] }"
		ng-click="openSidenavElm(S)">
		<md-icon md-font-icon="{{ S[0] }} fa-fw"></md-icon>
		<md-tooltip md-direction="right" md-delay="500">{{ S[1] }}</md-tooltip>
		<div class="icon_indicator" ng-show="S[2]"></div>
	</div>
</div>

<div class="w0 bg-lightgrey-5 transition" layout=column
	ng-class="{ 'w250 border-left': sidenavSel !== null }">
	
	<div class="h30 lh30 padding-left text-bold text-clear">{{ sidenavSel }}</div>

	<div flex ng-show="sidenavSel == 'Filtros'" layout=column class="">
		<div flex layout=column class="overflow-y darkScroll padding-0-10">
			<div ng-repeat="F in Grid.filtros">
				@include('Core.Filtros')
			</div>
			<div ng-show="Grid.filtros.length == 0" class="text-clear">Sin filtros</div>
			<div class="h30"></div>
		</div>
		<md-button class="margin md-raised" aria-label="a" ng-click="reloadData()">
			<md-icon md-font-icon="fa-bolt fa-fw"></md-icon>
			Recargar Datos
		</md-button>
	</div>

	<div flex ng-show="sidenavSel == 'Descargar'" layout=column class="padding-0-10">
		<span flex></span>
		<md-button class="no-margin h40 md-raised" ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }"
			ng-click="downloadData()">Descargar Datos</md-button>
		<div class="text-center margin-top text-clear">{{ load_data_len | number }} filas</div>
		<span flex></span>
	</div>

	<div flex ng-show="sidenavSel == 'Información'" layout=column class="padding-0-10 text-15px">

		<div class="margin-bottom">{{ Grid.Titulo }}</div>
		<div class="margin-bottom">{{ load_data_len | number }} filas</div>

		<!--<div class="md-subheader">SQL</div>
		<div class="text-13px">{{ Grid.sql.query }}</div>-->
	</div>

</div>