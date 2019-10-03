<div flex id="BDD" layout ng-controller="BDDCtrl">
	
	<md-sidenav class="w200 no-margin overflow bg-white border-right" layout=column
		md-is-locked-open="BDDSidenav">
		
		<div layout class="border-bottom" style="height: 42px" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw padding" style="padding-top: 9px !important;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="filterBdds" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addBDD()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=right>Agregar</md-tooltip>
			</md-button>
		</div>

		<md-list class="no-padding">
			<md-list-item ng-repeat="B in BDDsCRUD.rows | filter:filterBdds" ng-click="openBDD(B)" ng-class="{'bg-lightgrey-5': B.id == BDDSel.id}"
				class="border-bottom" style="min-height: 37px;height: 37px;">
				<md-icon md-font-icon="fa-database no-margin"></md-icon>
				<p class="margin-left-5" md-truncate>{{ B.Nombre }}</p>
			</md-list-item>
		</md-list>
	</md-sidenav>

	<div ng-show="BDDSel" flex layout>
		@include('BDD.BDD_Detalle')
		@include('BDD.BDD_Consulta')
		@include('BDD.BDD_Favoritos')
	</div>

</div>