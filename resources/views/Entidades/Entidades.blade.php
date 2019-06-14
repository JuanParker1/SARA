<div flex id="Entidades" layout ng-controller="EntidadesCtrl">

	<md-sidenav class="w200 no-margin bg-white border-right" layout=column
		md-is-locked-open="EntidadSidenav">
		
		<div layout class="border-bottom padding-left" layout-align="center center" style="height: 41px">
			<md-select ng-model="BddSel" flex class="md-no-underline no-margin" aria-label="s">
			  <md-option ng-repeat="Opt in Bdds" ng-value="Opt">
			  	<md-icon md-font-icon="fa-database"></md-icon>{{ Opt.Nombre }}
			  </md-option>
			</md-select>
		</div>

		<div layout class="border-bottom h40" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterEntidades" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addEntidad()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar Entidad</md-tooltip>
			</md-button>
		</div>

		<div class="overflow-y darkScroll" flex>
			<div ng-repeat="E in EntidadesCRUD.rows | filter:{ bdd_id: BddSel.id } | filter:filterEntidades " ng-click="openEntidad(E)" 
				ng-class="{'bg-lightgrey-5': E.id == EntidadSel.id}" 
				class="border-bottom mh30 h30 Pointer relative" layout layout-align="center center" md-ink-ripple>
				<md-icon md-font-icon="fa-chess-pawn fa-fw" style="margin: -1px 0px 0px 8px;"></md-icon>
				<span class="margin-left-5 text-13px" md-truncate flex>{{ E.Nombre }}</span>
			</div>
			<div class="h30"></div>
		</div>
	</md-sidenav>

	<div flex layout=column class="bg-white md-short relative" ng-show="EntidadSel">

		
		<md-tabs flex class="md-tabs-fullheight" md-center-tabs>

			
			
			<md-tab label="General">
				<md-content class="no-padding no-border border-top well" layout=column>@include('Entidades.Entidades_General')</md-content>
			</md-tab>

			<md-tab label="Grids">
				<md-content class="no-padding no-border border-top well" layout=column>@include('Entidades.Entidades_Grids')</md-content>
			</md-tab>

			<!--<md-tab label="Formularios">
				<md-content class="no-padding no-border border-top well" layout=column>Hello</md-content>
			</md-tab>-->
	    </md-tabs>
		
	    <div layout class="abs">
			<md-button class="md-icon-button no-margin h40 w40" aria-label="Button" ng-click="EntidadSidenav = !EntidadSidenav">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<div class="md-title lh40" hide-xs>{{ EntidadSel.Nombre }}</div>
		</div>

	</div>

</div>