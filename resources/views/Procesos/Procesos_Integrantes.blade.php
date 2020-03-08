<div layout=column
	class="bg-white border border-radius margin-left-5 margin-bottom-5 overflow-y darkScroll">
	<div class="md-subheader margin">Integrantes ({{ AsignacionesCRUD.rows.length }})</div>

	<div flex layout=column class="border-top overflow-y hasScroll" ng-show="AsignacionesCRUD.rows.length > 0">
		
		<div layout ng-repeat="A in AsignacionesCRUD.rows" class="padding-0-5 border-bottom" layout-align="center center">
			
			<div class="s50 bg-lightgrey border-right" 
			style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + A.usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>

			<div layout=column flex class="margin-left-5" md-truncate>
				<span class="text-14px text-bold">{{ A.usuario.Nombres }}</span>
				<span class="text-clear text-14px">{{ A.usuario.Email }}</span>
			</div>

			<md-select ng-model="A.perfil_id" aria-label=s class="no-margin md-no-underline h50 text-13px" ng-change="AsignacionesCRUD.update(A)">
				<md-option ng-repeat="Op in Perfiles" ng-value="Op.id">{{ Op.Perfil }}</md-option>
			</md-select>

			<md-button class="md-icon-button no-margin focus-on-hover s30 no-padding" ng-click="AsignacionesCRUD.delete(A)">
				<md-icon md-svg-icon="md-close"></md-icon>
			</md-button>

		</div>

	</div>

	<md-autocomplete 
		md-selected-item="selectedItem"
		md-selected-item-change="selectedUser(item)" 
		md-search-text="searchText"
		md-items="item in userSearch(searchText)"
		md-delay="400"
		placeholder="Agregar Usuario..."
		class="bg-lightgrey-5 margin">

		<md-item-template>
			<span md-highlight-text="searchText" md-highlight-flags="^i" >{{ item.Nombres }}</span>
			<span md-highlight-text="searchText" md-highlight-flags="^i" class="margin-left-5 ">{{ item.Email }}</span>
		</md-item-template>
		<md-not-found>No Encontrado</md-not-found>

	</md-autocomplete>
</div>