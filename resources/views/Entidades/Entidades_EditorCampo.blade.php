<md-input-container class="" ng-if="C.campo.Tipo == 'Texto'">
	<label>{{ C.campo_title }}</label>
	<input type="text" ng-model="C.val" name="c{{ C.id }}" ng-required="{{ C.Requerido }}">
</md-input-container>

<md-input-container class="" ng-if="C.campo.Tipo == 'TextoLargo'">
	<label>{{ C.campo_title }}</label>
	<textarea ng-model="C.val" rows="2" name="c{{ C.id }}" ng-required="{{ C.Requerido }}"></textarea>
</md-input-container>

<md-input-container class="" ng-if="inArray(C.campo.Tipo, ['Entero'])">
	<label>{{ C.campo_title }}</label>
	<input type="number" ng-model="C.val" name="c{{ C.id }}" ng-required="{{ C.Requerido }}">
</md-input-container>

<md-input-container class="" ng-if="inArray(C.campo.Tipo, ['Dinero'])">
	<label>{{ C.campo_title }}</label>
	<input type="text" ng-model="C.val" name="c{{ C.id }}" ng-required="{{ C.Requerido }}" ui-money-mask="0">
</md-input-container>

<md-input-container class="" ng-if="inArray(C.campo.Tipo, ['Fecha'])">
	<label>{{ C.campo_title }}</label>
	<md-datepicker ng-model="C.val" name="c{{ C.id }}" ng-required="{{ C.Requerido }}" md-hide-icons="calendar"></md-datepicker>
</md-input-container>

<div layout ng-if="inArray(C.campo.Tipo, ['FechaHora'])">
	<md-input-container class="w95">
		<label>{{ C.campo_title }}</label>
		<md-datepicker class="w95" ng-model="C.dateval" name="c{{ C.id }}_1" ng-required="{{ C.Requerido }}" md-hide-icons="calendar" ng-change="changedField(C)"></md-datepicker>
	</md-input-container>
	<md-input-container class="" flex>
		<input type="time" ng-model="C.dateval" name="c{{ C.id }}_2" ng-required="{{ C.Requerido }}" aria-label=f
			list="listaHoras" class="text-14px lh15" ng-change="changedField(C)"></input>
	</md-input-container>
</div>

<md-input-container class="" ng-if="inArray(C.campo.Tipo, ['Lista'])">
	<label>{{ C.campo_title }}</label>
	<md-select ng-model="C.val" name="c{{ C.id }}" ng-required="{{ C.Requerido }}" class="w100p">
		<md-option ng-repeat="Op in C.campo.Config.opciones" ng-value="Op.value">{{ Op.desc == '' ? Op.value : Op.desc }}</md-option>
	</md-select>
</md-input-container>



<div layout class="" ng-if="C.campo.Tipo == 'Entidad'">
	
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
	    ng-required="C.Requerido">

		<md-item-template>
			<div class="entidad_chip" layout=column>
				<div class="entidad_title" layout><div flex md-truncate>{{item.C1}}</div><div class="entidad_id">{{item.C2}}</div></div>
				<div class="entidad_metadata" layout><div flex md-truncate>{{item.C3}}</div><div>{{item.C4}}</div></div>
				<div class="entidad_metadata" layout><div flex md-truncate>{{item.C5}}</div></div>
			</div>
	    </md-item-template>

		<md-not-found>"{{C.searchText}}" no encontrado.</md-not-found>
	</md-autocomplete>

	<md-button class="md-icon-button s30 no-margin no-padding " hide ng-show="C.val == null" ng-click="clearCampo(C)">
		<md-icon md-font-icon="fa-search"></md-icon>
		<md-tooltip md-direction="left">Buscar</md-tooltip>
	</md-button>

	<div flex layout ng-show="C.val !== null" class="show-child-on-hover relative">
		<div flex layout=column class="">
			<label class="custom-label">{{ C.campo_title }}</label>
			<div class="entidad_pill" layout=column>
				<div class="entidad_chip" layout=column>
					<div class="entidad_title" layout><div flex>{{C.selectedItem.C1}}</div><div class="entidad_id">{{C.selectedItem.C2}}</div></div>
					<div class="entidad_metadata" layout><div flex>{{C.selectedItem.C3}}</div><div>{{C.selectedItem.C4}}</div></div>
					<div class="entidad_metadata" layout><div flex>{{C.selectedItem.C5}}</div></div>
				</div>
			</div>
		</div>
		<md-button class="md-icon-button s30 no-margin no-padding child abs bg-lightgrey-5" style="right: 0"
			ng-show="C.Editable" ng-click="clearCampo(C)">
			<md-icon md-font-icon="fas fa-eraser"></md-icon>
			<md-tooltip md-direction="left">Borrar</md-tooltip>
		</md-button>
	</div>

</div>


<div ng-if="C.campo.Tipo == 'Booleano'" layout class="h30 lh30 text-14px">
	<md-checkbox ng-model="C.val" aria-label="c" name="c{{ C.id }}" ng-required="{{ C.Requerido }}"
		class="no-margin md-primary" ng-true-value="'{{ C.campo.Op4}}'" ng-false-value="'{{C.campo.Op5}}'">
		{{ C.campo_title }}
	</md-checkbox>
</div>
