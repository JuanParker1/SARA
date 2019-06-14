<div layout=column class="padding-right w270 border-right">

	<div layout>
		<md-button class="md-icon-button no-margin" aria-label="Button" ng-click="BDDSidenav = !BDDSidenav">
			<md-icon md-svg-icon="md-bars"></md-icon>
		</md-button>
		<div class="md-title lh40">{{ BDDSel.Nombre }}</div>
	</div>

	<form flex layout=column class="overflow-y darkScroll padding-left" ng-submit="updateBDD()">
		
		<md-input-container class="margin-bottom-5">
			<label>Nombre</label>
			<input type="text" ng-model="BDDSel.Nombre" required>
		</md-input-container>

		<md-input-container class="margin-bottom-5">
			<label>Tipo de Conector</label>
			<md-select ng-model="BDDSel.Tipo" required>
				<md-option ng-repeat="(kT,T) in TiposBDD" ng-value="kT">{{ kT }}</md-option>
			</md-select>
		</md-input-container>

		<md-input-container ng-repeat="Op in ['Op1','Op2','Op3','Op4','Op5']" ng-show="TiposBDD[BDDSel.Tipo][Op]"
			class="margin-bottom-5">
			<label>{{ TiposBDD[BDDSel.Tipo][Op] }}</label>
			<input type="text" ng-model="BDDSel[Op]" ng-required="TiposBDD[BDDSel.Tipo][Op]">
		</md-input-container>

		<md-input-container class="margin-bottom-5">
			<label>Usuario</label>
			<input type="text" ng-model="BDDSel.Usuario" required>
		</md-input-container>

		<md-input-container class="margin-bottom-5">
			<label>Contraseña</label>
			<input type="password" ng-model="BDDSel['Contraseña']" required>
		</md-input-container>

		<span flex></span>

		<div layout=column layout-gt-xs=row class="border-top">
			<md-button class="md-icon-button md-warning" aria-label="b" ng-click="removeBDD()">
				<md-icon md-font-icon="fa-trash"></md-icon>
				<md-tooltip md-direction=right>Eliminar</md-tooltip>
			</md-button>
			<span flex></span>
			<md-button class="no-margin-right" ng-click="testBDD()">Probar</md-button>
			<md-button class="md-primary md-raised no-margin-right" type="submit">Guardar</md-button>
		</div>

	</form>

</div>