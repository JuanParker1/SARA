<md-sidenav class="w250 border-left overflow-y" layout=column md-is-locked-open="BDDFavSidenav">

	<div layout class="border-bottom" style="height: 49px" layout-align="center center">
		<div class="md-toolbar-searchbar" flex layout>
			<md-icon md-font-icon="fa-search" class="fa-fw padding"></md-icon>
			<input flex type="search" placeholder="Buscar Favorito..." ng-model="filterFavs" class="no-padding">
		</div>
		<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addFav()">
			<md-icon md-font-icon="fa-plus"></md-icon>
			<md-tooltip md-direction=left>Agregar Favorito</md-tooltip>
		</md-button>
	</div>

	<md-list class="no-padding">

		<md-list-item class="lh20" ng-click="useFav(F)" ng-repeat="F in FavsCRUD.rows | filter:{ Nombre: filterFavs } | orderBy:'-updated_at'">
			{{ F.Nombre }}
			<md-button class="md-icon-button no-margin md-secondary" aria-label="b" ng-click="editFav(F)" style="margin-right: -15px !important">
				<md-icon md-svg-icon="md-more-v"></md-icon>
				<md-tooltip md-direction=left>Editar</md-tooltip>
			</md-button>
		</md-list-item>
	</md-list>
</md-sidenav>