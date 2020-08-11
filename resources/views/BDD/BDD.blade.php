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

	<div ng-show="BDDSel" flex layout=column>

		<div layout class="bg-theme border-bottom">
			<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="BDDSidenav = !BDDSidenav">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<div class="md-title lh40 w210">{{ BDDSel.Nombre }}</div>
			<div ng-repeat="I in SeccionesBDD" layout layout-align="center center" class="mw40 border-left relative SectionIcon" 
				md-ink-ripple ng-class="{ 'border-right': $last, 'SectionIcon_Selected': SectionSel == I[0] }" 
				ng-click="changeSection(I)">
	    		<md-icon md-font-icon="{{ I[1] }} fa-fw SectionIcon_Icon"></md-icon>
	    		<div class="SectionIcon_Text nowrap">{{ I[2] }}</div>
	    	</div>
		</div>

		<div flex layout>
			@include('BDD.BDD_Detalle')
			
			<div flex layout ng-show="SectionSel == 'ConsultaSQL'">
				@include('BDD.BDD_Consulta')
				@include('BDD.BDD_Favoritos')
			</div>

			<div flex layout=column ng-show="SectionSel == 'Listas'">
				@include('BDD.BDD_Listas')
			</div>

		</div>


	</div>

</div>