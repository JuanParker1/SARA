<div id="Home" flex layout="column">
	<md-toolbar class="md-short border-bottom bg-white" md-theme="Snow_White">
		<div class="md-toolbar-tools" layout>

			<md-button class="md-icon-button w30 mw30" aria-label="Button" ng-click="mainSidenav()">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<img src="img/Logo.png" class="w25 h25 margin-right-5">
			<h3 class="md-headline text-bold"><% env('APP_NAME') %></h3>
			<span flex></span>
			
			<img ng-src="{{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}" class="h40 margin-right">
			<h3 class="md-headline">{{ Usuario.Nombres }}</h3>
			<md-button class="md-icon-button" aria-label="Button" ng-click="Logout()">
				<md-tooltip>Cerrar Sesion</md-tooltip>
				<md-icon md-font-icon="fa-power-off" class="fa-lg"></md-icon>
			</md-button>
			
		</div>
	</md-toolbar>

	<div flex layout>
		
		<md-sidenav md-component-id="SectionsNav" id="SectionsNav"
			md-is-locked-open="true" 
			ng-class="{ 'w60': (mainSidenavLabels || !gtsm ), 'w200': (!mainSidenavLabels && gtsm) }"
			class="darkScroll border-right">

			<md-list class="Navigation no-padding">
				<md-list-item ng-click="navTo('Home')" class="" ng-class="{ 'itemsel' : (State.route.length < 3 ) }">
						<md-icon class="fa-fw fa-lg" md-font-icon="fa-home"></md-icon>
						<span flex class="SectionsNav_Text" ng-hide="(mainSidenavLabels || !gtsm)">Inicio</span>
						
				</md-list-item>
	        	<md-list-item ng-click="navTo('Home.Section', { section: S.id })" 
	                ng-repeat="S in Usuario.Secciones"
	                ng-class="{ 'itemsel' : (S.id == State.route[2] ) }"
	                class="transition " layout>
	        			<md-icon class="fa-fw fa-lg" md-font-icon="{{ S.Icono }}"></md-icon>
	        			<span flex class="SectionsNav_Text margin-right-5" ng-hide="(mainSidenavLabels || !gtsm)">{{ S.Seccion }}</span>
	        	</md-list-item>
	        </md-list>

		</md-sidenav>

		<div id='Section' ui-view flex layout="column" class="">
			@include('Inicio')
		</div>

	</div>

	
</div>
