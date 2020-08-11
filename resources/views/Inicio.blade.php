<div id="Inicio" flex layout ng-controller="InicioCtrl">

	<div id="Inicio_Overlay" flex layout>

		<div flex layout=column   class="padding overflow-y hasScroll" >

			<div layout>
				<h2 flex class="no-margin-top margin-bottom md-headline text-300">{{ Saludo }}, {{ Usuario.Nombres | getword:1 }}</h2>
				<md-button class="no-margin border-rounded bg-theme " ng-click="InicioSidenavOpen = !InicioSidenavOpen" >
					<md-icon md-font-icon="fa-history margin-right-5" style="transform: translateY(-2px);"></md-icon>Recientes
				</md-button>
			</div>

			<div layout layout-align="center center" class="transition" ng-class="{ 'h150' : !searchMode, 'h40' : searchMode }">
				
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
				<div class="md-subheader padding-bottom-5" ng-show="Usuario.Apps.length > 0">Aplicaciones y Reportes</div>
				<div layout layout-wrap class="margin-bottom-20" ng-show="Usuario.Apps.length > 0">

					<a class="rect-card w180 padding-5" layout layout-align="center center"
						ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
						href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_blank"
						ng-style="{ backgroundColor: A.Color, color: A.textcolor }">
						<md-icon class="fa-fw text-25px text-inherit margin" md-font-icon="{{ A.Icono }}" ></md-icon>
						<div class="rect-card-text padding-right" flex>{{ A.Titulo }}</div>
					</a>
						
				</div>


				<div class="md-subheader padding-bottom-5">Accesos Directos</div>
				<div layout layout-wrap class="margin-bottom-20">

					<a class="square-card bg-theme w140 h90" layout=column ng-repeat="S in Usuario.Secciones"
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

		<md-sidenav id="InicioSidenav" layout=column class="w300 text-white padding-but-right  md-sidenav-right no-overflow" 
			md-is-locked-open="$mdMedia('gt-sm') && InicioSidenavOpen"
			md-is-open="InicioSidenavOpen">

			<div flex layout=column class="w290 overflow-y hasScroll">
				<!--
				<label class="text-clear margin-bottom-5 text-14px">Favoritos</label>
				<div md-truncates class="margin-bottom text-14px" layout ng-repeat="F in [1,2,3]">
					<div flex layout layout-align="center center">
						<md-icon md-font-icon="fa-syringe fa-fw margin-right-5 text-16px"></md-icon>
						<div flex>Laboratorio - Informe</div>
					</div>
					<md-icon md-font-icon="fa-star fa-fw margin-right-5 Pointer"></md-icon>
				</div>

				<div class="h20"></div>
				-->
				<label class="text-clear margin-bottom-5 text-14px">Recientes</label>
				<div ng-repeat="R in Recientes" md-truncates class="margin-bottom text-14px" 
					layout >
					<a flex href="{{ R.Url }}" target="_blank"
						layout layout-align="center center" class="no-underline text-white" >
						<md-icon md-font-icon="{{ R.Icono }} fa-fw margin-right-5"></md-icon>
						<div flex>{{ R.Titulo }}</div>
					</a>
					<md-icon hide md-svg-icon="md-close" class="child Pointer" ng-click="removeRec()"></md-icon>
					
				</div>

				<div class="h30"></div>

			</div>

		</md-sidenav>

	</div>

</div>

<style>
	
	#Inicio{
		background-image: url(img/bg_data1.jpg);
		background-size: cover;
		background-position: center center;

	}

	#Inicio_Overlay{
		background: rgba(0, 0, 0, 0.4);
		opacity: 0;
		animation: In_Fade 500ms forwards cubic-bezier(0.34, 0.79, 0.68, 0.96) 500ms;
	}

	#InicioSidenav{
		background: rgba(0, 0, 0, 0.3);
    	backdrop-filter: blur(15px);
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

	.rect-card{
		cursor: pointer;
		outline: none;
		text-decoration: none !important;
		box-shadow: inset 0 0 0 1px #000000a1, 0 12px 10px -8px rgba(0, 0, 0, 0.4);
		transform: scale(0.95);
		transition: all 0.3s;
		border-radius: 8px;
		margin-bottom: 7px;
	}

	.rect-card:hover{
		transform: scale(1);
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