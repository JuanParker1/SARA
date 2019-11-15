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