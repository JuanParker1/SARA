<div layout=column ng-show="PageSel">

	<div layout class="margin-bottom">
		<md-input-container flex class="no-margin" md-no-float>
			<input type="text" ng-model="PageSel.Titulo" placeholder="Página">
		</md-input-container>
		<md-input-container class="no-margin">
			<md-select ng-model="PageSel.Tipo" aria-label="s" ng-change="prepConfig()">
				<md-option ng-value="Op.id" ng-repeat="Op in TiposPage">
					<md-icon md-font-icon="{{ Op.Icono }} fa-fw mh20 h20"></md-icon>
					{{ Op.Nombre }}
				</md-option>
			</md-select>
		</md-input-container>
	</div>

	<md-subheader class="no-padding margin-bottom">Opciones</md-subheader>
	
	<div layout=column ng-show="PageSel.Tipo == 'ExternalUrl'">
		<md-input-container>
			<input type="text" ng-model="PageSel.Config.url" placeholder="Url Externa">
		</md-input-container>
	</div>

	<div layout=column ng-show="PageSel.Tipo == 'Scorecard'">

		<md-input-container>
			<label>Dashboard</label>
			<md-select ng-model="PageSel.Config.element_id" aria-label="s">
				<md-option ng-value="Op.id" ng-repeat="Op in Scorecards" class="text-14px">
					<span class="">{{ Op.Titulo }}</span>
				</md-option>
			</md-select>
		</md-input-container>

		<div class="h30"></div>
		<md-subheader class="no-padding margin-bottom">Filtrar Proceso</md-subheader>
		<div ng-repeat="P in [ PageSel.Config.proceso_id ]" class="padding-5" layout class="show-children-on-hover" 
			ng-show="PageSel.Config.proceso_id !== null">
			<div ng-repeat="E in Procesos | filter:{ id: P }:true" flex md-truncate class="text-13px">{{ E.Proceso }}</div>
			<md-icon class="s20 child focus-on-hover Pointer" md-svg-icon="md-close" ng-click="PageSel.Config.proceso_id = null"></md-icon>
		</div>
		<md-autocomplete 
			md-selected-item-change="selectedFilterProceso(item)"  
			md-search-text="searchText2" 
			md-items="item in buscarProcesos(searchText2)" 	md-item-text="item.Proceso"
			class="bg-white h30"
			placeholder="Seleccionar Proceso">
			<md-item-template>
				<span md-highlight-text="searchText2">{{ item.Proceso }}</span>
			</md-item-template>
			<md-not-found>No Encontrado</md-not-found>
		</md-autocomplete>



	</div>

	<div layout=column ng-show="PageSel.Tipo == 'Grid'">

		<md-input-container class="no-margin-bottom">
			<label>Grid</label>
			<md-select ng-model="PageSel.Config.element_id" aria-label="s">
				<md-option ng-value="Op.id" ng-repeat="Op in Grids" class="text-14px">
					<span class="text-clear">{{ Op.entidad.Nombre }} - </span><span class="">{{ Op.Titulo }}</span>
				</md-option>
			</md-select>
		</md-input-container>

	</div>

	<div layout=column ng-show="PageSel.Tipo == 'Cargador'">

		<md-input-container class="no-margin-bottom">
			<label>Cargador</label>
			<md-select ng-model="PageSel.Config.element_id" aria-label="s">
				<md-option ng-value="Op.id" ng-repeat="Op in Cargadores" class="text-14px">
					<span class="">{{ Op.Titulo }}</span>
				</md-option>
			</md-select>
		</md-input-container>

	</div>

</div>