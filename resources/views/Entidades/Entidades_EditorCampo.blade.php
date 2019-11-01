<md-input-container class="no-margin" ng-if="C.campo.Tipo == 'Texto'">
	<label>{{ C.campo_title }}</label>
	<input type="text" ng-model="C.val" name="c{{ C.id }}">
</md-input-container>


<div layout class="padding-right">
	
	<md-autocomplete flex class=""
		md-floating-label="{{ C.campo_title }}"
		ngs-disabled=""
		md-no-cache="false"
		md-selected-item="C.selectedItem"
		md-search-text="C.searchText"
		md-selected-item-change="selectedItem(item, C)"
		md-items="item in searchEntidad(C)"
		md-item-text="item.C1"
		md-min-length="C.campo.entidadext.config.search_minlen"
		md-menu-class=""
	    md-menu-container-class="autocomplete-custom"
	    placeholder="Buscar..." 
	    md-require-match="true"
	    md-delay=1000
	    md-autoselect=true
	    ng-show="C.val == null"
	    ng-required="true">

		<md-item-template>
			<div class="entidad_chip" layout=column>
				<div class="entidad_title" layout><div flex md-truncate>{{item.C1}}</div><div class="entidad_id">{{item.C2}}</div></div>
				<div class="entidad_metadata" layout><div flex md-truncate>{{item.C3}}</div><div>{{item.C4}}</div></div>
				<div class="entidad_metadata" layout><div flex md-truncate>{{item.C5}}</div></div>
			</div>
	    </md-item-template>

		<md-not-found>"{{C.searchText}}" no encontrado.</md-not-found>
	</md-autocomplete>
	<md-button class="md-icon-button s30 no-margin no-padding " 
		style="transform: translateY(20px);" ng-show="C.val == null" ng-click="clearCampo(C)">
		<md-icon md-font-icon="fa-search"></md-icon>
		<md-tooltip md-direction="left">Buscar</md-tooltip>
	</md-button>

	<div flex layout=column ng-show="C.val !== null" class="margin-10-0">
		<div class="md-caption text-clear">{{ C.campo_title }}</div>
		<div class="bg-lightgrey-5 padding-5 border-radius border" layout=column>
			<div class="entidad_chip" layout=column>
				<div class="entidad_title" layout><div flex>{{C.selectedItem.C1}}</div><div class="entidad_id">{{C.selectedItem.C2}}</div></div>
				<div class="entidad_metadata" layout><div flex>{{C.selectedItem.C3}}</div><div>{{C.selectedItem.C4}}</div></div>
				<div class="entidad_metadata" layout><div flex>{{C.selectedItem.C5}}</div></div>
			</div>
		</div>
	</div>
	<md-button class="md-icon-button s30 no-margin no-padding focus-on-hover" 
		style="transform: translateY(23px);" ng-show="C.val !== null" ng-click="clearCampo(C)">
		<md-icon md-font-icon="fas fa-redo fa-flip-horizontal"></md-icon>
		<md-tooltip md-direction="left">Cambiar</md-tooltip>
	</md-button>
</div>

<pre hide>{{ C | json }}</pre>
