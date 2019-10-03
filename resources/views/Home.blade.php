<div id="Home" flex layout="column">
	<md-toolbar class="md-short border-bottom bg-white" md-theme="Snow_White">
		<div class="md-toolbar-tools" layout>

			<md-button class="md-icon-button w30 mw30" aria-label="Button" ng-click="mainSidenav()" style="margin-left: -5px;margin-right: 7px;">
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<img src="img/Logo.png" class="w25 h25 margin-right-5">
			<h3 class="md-headline text-bold" hide-xs><% env('APP_NAME') %></h3>

			<span flex></span>

			<div class="md-toolbar-searchbar w120 text-clear text-15px" layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="a" class="no-padding">
			</div>

			<div class="w35 h35 bg-lightgrey border-rounded margin-right-5 border"
				style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
			<h3 class="md-headline" hide-xs>{{ Usuario.Nombres }}</h3>
			<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="Logout()">
				<md-icon md-font-icon="fa-chevron-down"></md-icon>
			</md-button>
			
		</div>
	</md-toolbar>

	<div flex layout>
		
		<md-sidenav md-component-id="SectionsNav" id="SectionsNav"
			md-is-locked-open="true" 
			ng-class="{ 'w50': (mainSidenavLabels || !gtsm ), 'w220': (!mainSidenavLabels && gtsm) }"
			class="darkScroll border-right bg-white">

			<md-list class="Navigation no-padding" style="margin-top: -1px;">
				<md-list-item ng-click="navTo('Home')" class="mh40 h40 itemselec" ng-class="{ 'itemsel' : (State.route.length < 3 ) }">
						<md-icon class="fa-fw fa-lg" md-font-icon="fa-home"></md-icon>
						<span flex class="SectionsNav_Text" ng-hide="(mainSidenavLabels || !gtsm)">Inicio</span>
						
				</md-list-item>
	        	<md-list-item ng-click="navTo('Home.Section', { section: S.id })" 
	                ng-repeat="S in Usuario.Secciones"
	                ng-class="{ 'itemsel' : (S.id == State.route[2] ) }"
	                class="transition mh40 h40 itemselec" layout>
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
