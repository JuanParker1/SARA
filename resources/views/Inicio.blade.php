<div id="Inicio" flex layout=column ng-controller="InicioCtrl" class="padding overflow-y darkScroll">

	<div layout class="margin-bottom-5 border-radius">
		<div class="md-toolbar-searchbar" flex layout>
			<md-icon md-font-icon="fa-search" class="fa-fw lh30 w30 h30"></md-icon>
			<input flex type="search" placeholder="Buscar Aplicaciones..." ng-model="filterApps" class="no-padding h30">
		</div>
	</div>

	<div layout=column class="bg-white border-radius margin-bottom" 
		ng-repeat="Fav in [true,false]">
		<div layout class="border Pointer relative" ng-click="openApp(A)" md-ink-ripple 
			ng-repeat="A in Usuario.Apps | filter:{favorito:Fav} | filter:filterApps | orderBy:'Titulo' "
			style="margin-bottom: -1px;">
			<md-button class="md-icon-button no-margin" ng-click="makeFavorite(A,!Fav)">
				<md-icon class="fa-fw fa-star" md-font-icon="{{ Fav ? 'fa' : 'far' }}"></md-icon>
			</md-button>
			<div ng-style="{ backgroundColor: A.Color }" class="w40 h40" layout layout-align="center center">
				<md-icon class="fa-fw fa-lg" md-font-icon="{{ A.Icono }}" ng-style="{ color: A.textcolor }"></md-icon>
			</div>
			<div flex class="text-16px lh40 margin-left">{{ A.Titulo }}</div>
		</div>
	</div>

</div>