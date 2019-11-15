<div id="Inicio" flex layout=column ng-controller="InicioCtrl" class="padding overflow-y darkScroll">

	<div layout class="margin-bottom-5 border-radius">
		<div class="md-toolbar-searchbar" flex layout>
			<md-icon md-font-icon="fa-search" class="fa-fw lh30 w30 h30"></md-icon>
			<input flex type="search" placeholder="Buscar Aplicaciones..." ng-model="filterApps" class="no-padding h30">
		</div>
	</div>

	<div layout=column class="bg-white border border-radius margin-bottom no-overflow">
		<a class="Pointer mh40 no-underline" layout layout-align="center center"
			ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
			href="http://sara.local/#/a/{{ A.Slug }}" target="_blank"
			ngs-click="openApp(A)"
			ng-class="{ 'border-bottom': !$last }">
			<md-button class="md-icon-button no-margin" ng-click="makeFavorite(A,!Fav)" hide>
				<md-icon class="fa-fw fa-star" md-font-icon="{{ Fav ? 'fa' : 'far' }}"></md-icon>
			</md-button>
			<div ng-style="{ backgroundColor: A.Color }" class="s40" layout layout-align="center center">
				<md-icon class="fa-fw fa-lg" md-font-icon="{{ A.Icono }}" ng-style="{ color: A.textcolor }"></md-icon>
			</div>
			<div flex class="text-16px lh30 margin-left text-black">{{ A.Titulo }}</div>
		</a>
	</div>

</div>

<!-- href="http://sara.local/#/a/{{ A.Slug }}" target="_blank"  -->