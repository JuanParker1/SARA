<div id="Inicio" flex layout=column ng-controller="InicioCtrl">

	<div flex layout=column id="Inicio_Overlay"  class="padding overflow-y darkScroll" >

		<h2 class="no-margin-top margin-bottom md-headline text-300">{{ Saludo }}, {{ Usuario.Nombres | getword:1 }}</h2>

		<div layout layout-align="center center" class="transition" ng-class="{ 'h180' : !searchMode, 'h40' : searchMode }">
			
			<div class="md-toolbar-searchbar bg-white border h40 margin-bottom wu600 border-rounded" layout
				md-whiteframe=3>
				<md-icon md-svg-icon="md-search" class="s20 margin text-black"></md-icon>
				<input id="searchField" flex type="search" placeholder="Buscar..." ng-model="searchText" class="no-padding h40 lh40 text-18px" 
					ng-change="mainSearch()" ng-model-options="{ debounce: 400 }" md-autofocus>
				<md-icon md-svg-icon="md-close" class="Pointer s25 text-black margin-right" ng-click="searchText = ''; mainSearch();"
					ng-show="searchText != ''"></md-icon>
			</div>

		</div>

		<div layout=column class="transition" ng-show="!searchMode">
			<div class="md-subheader padding-bottom-5" ng-show="Usuario.Apps.length > 0">Mis Aplicaciones</div>
			<div layout layout-wrap class="margin-bottom-20" ng-show="Usuario.Apps.length > 0">

				<a class="square-card w135 h120" layout=column
					ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
					href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_self"
					ng-style="{ backgroundColor: A.Color, color: A.textcolor }">
					<div class="h70" layout layout-align="center center">
						<md-icon class="fa-fw fa-2x text-inherit" md-font-icon="{{ A.Icono }}" ></md-icon>
					</div>
					<div class="square-card-text">{{ A.Titulo }}</div>
				</a>
					
			</div>


			<div class="md-subheader padding-bottom-5">Accesos Directos</div>
			<div layout layout-wrap class="margin-bottom-20">

				<a class="square-card bg-theme w135 h90" layout=column ng-repeat="S in Usuario.Secciones"
					href="{{ Usuario.Url }}#/Home/{{S.id}}" target="_self">
					<div class="h55" layout layout-align="center center">
						<md-icon class="fa-fw fa-2x text-inherit text-clear" md-font-icon="{{ S.Icono }}" ></md-icon>
					</div>
					<div class="square-card-text">{{ S.Seccion }}</div>
				</a>
					
			</div>			
		</div>


		<div flex layout=column class="transition overflow-y hasScroll" ng-show="searchMode" layout-align="start center" style="margin-bottom: -10px;">

			<div layout class="wu800 margin-10-0" layout-align="center center" ng-show="searchResults.groups.length > 1">
				
				<div class="search-group transition Pointer"
					ng-class="{ 'search-group-selected': (searchGroupSel == 0) }" ng-click="selectSearchGroup(0)">
					Todo
				</div>

				<div ng-repeat="(k,G) in searchGroups" class="search-group transition Pointer"	
					ng-class="{ 'search-group-selected': (k == searchGroupSel-1) }" ng-click="selectSearchGroup(k+1)" 
					ng-show="inArray(G.Titulo, searchResults.groups)">
					<md-icon ng-if="G.Icono" md-font-icon="{{ G.Icono }} fa-lg fa-fw margin-right-5"></md-icon>
					<span class="text-16px">{{ G.Titulo }}</span>
				</div>

			</div>

			<div ng-repeat="R in filteredSearchResults()" layout class="bg-theme border border-radius wu800 search-res" md-whiteframe=2>
				
				<div flex layout class="padding Pointer" ng-click="showSearchRes(R)">
					<div class="text-16px">
						<span md-highlight-text="searchText" md-highlight-flags="i">{{ R.Titulo }}</span>
					</div>
					<div class="text-clear text-16px margin-left">{{ R.Secundario }}</div>
					<span flex></span>
					<div class="search-res-pill">
						<md-icon md-font-icon="{{ R.Icono }} fa-fw s20 margin-right-5"></md-icon> {{ R.Tipo }}
					</div>
				</div>
			</div>

			<div ng-show="searchResults.length == 0" class="text-clear text-center text-18px">Sin resultados</div>

			<div class="h50"></div>

		</div>


	</div>



</div>

<style>
	
	#Inicio{
		background-image: url(img/bg_inicio.jpg);
		background-size: cover;
		background-position: center center;
	}

	#Inicio_Overlay{
		background: rgba(0, 0, 0, 0.7);
	}

	#searchField{
		margin-left: -45px;
    	padding-left: 45px !important;
	}

	.square-card{
		cursor: pointer;
		outline: none;
		text-decoration: none !important;
		box-shadow: inset 0 0 0 1px #000000a1, 0 12px 10px -8px rgba(0, 0, 0, 0.4);
		transform: scale(0.95);
		transition: all 0.3s;
		border-radius: 8px;
		margin-bottom: 7px;
	}

	.square-card:hover{
		transform: scale(1);
	}

	.square-card-text{
		font-size: 16px;
		text-align: center;
		padding: 5px;
	}

	.search-res{
		margin-bottom: 1px;
	}

	.search-res span.highlight {
    	background: #00adff7a;
	}

	.search-res-pill{
		background: #3e3e3e;
	    border-radius: 9px;
	    padding: 0px 14px;
	}

	.search-group{
		padding: 5px 20px 10px;
		opacity: 0.7;
	}

	.search-group-selected{
		opacity: 1;
		box-shadow: inset 0 -3px #72acffb8;
	}

</style>

<!--<a class="Pointer mh40 no-underline border border-radius bg-white no-overflow margin-right-5 margin-bottom-5" 
	layout layout-align="center center"
	ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
	href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_blank" hide>
	<div ng-style="{ backgroundColor: A.Color }" class="s40" layout layout-align="center center">
		<md-icon class="fa-fw fa-lg" md-font-icon="{{ A.Icono }}" ng-style="{ color: A.textcolor }"></md-icon>
	</div>
	<div flex class="text-14px margin-0-10 text-black">{{ A.Titulo }}</div>
</a>-->