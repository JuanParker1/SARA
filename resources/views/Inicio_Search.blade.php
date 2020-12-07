<div layout layout-align="center center" class="transition" ng-class="{ 'h150' : !searchMode, 'h40' : searchMode }">
				
	<div class="md-toolbar-searchbar bg-white border h40 margin-bottom w100p mxw500 border-rounded" layout
		md-whiteframe=3>
		<md-icon md-svg-icon="md-search" class="s20 margin text-black"></md-icon>
		<input id="searchField" name="searchField" flex type="search" placeholder="Buscar..." ng-model="searchText" class="no-padding h40 lh40 text-18px" autocomplete="off"
			enter-stroke="mainSearch()" md-autofocus>
		<md-icon md-svg-icon="md-close" class="Pointer s25 text-black margin-right" ng-click="searchText = ''; mainSearch();"
			ng-show="searchText != ''"></md-icon>
	</div>

</div>

<div flex layout=column class="transition overflow-y hasScroll" 
	ng-show="searchMode" layout-align="start center" style="margin-bottom: -10px;">

	<div layout class="margin-10-0" layout-wrap layout-align="center center" ng-show="searchResults.groups.length > 1">
		
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

	<div ng-repeat="R in filteredSearchResults()" layout class="bg-theme border border-radius wu600 search-res" md-whiteframe=2>
		
		<div flex layout layout-align="center center" class="padding-5 Pointer" ng-click="showSearchRes(R)" 
			>
			<div flex layout=column>
				<div class="text-14px" md-highlight-text="searchText" md-highlight-flags="i" md-truncate>{{ R.Titulo }}</div>
				<div class="text-clear text-14px" md-truncate>{{ R.Secundario }}</div>
			</div>
			
			<div class="search-res-pill" layout ng-style="{ 'background-color': R.Color }">
				<md-icon md-font-icon="{{ R.Icono }} fa-fw s20"></md-icon><div class="margin-left-5" hide-xs>{{ R.Tipo }}</div>
			</div>
		</div>
	</div>

	<div ng-show="searchResults.length == 0" class="text-clear text-center text-18px">Sin resultados</div>

	<div class="h50"></div>

</div>

