<md-dialog layout=column class="w350">
	
	<div layout class="h30 lh30 padding-left">
		<div flex class="">Opciones para: <b>{{ C.Alias || C.Columna }}</b></div>
		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div layout=column flex class="overflow-y darkScroll padding">
		
		<md-input-container>
			<input type="text" ng-model="C.Config.img_ruta" placeholder="Url (usar $id para el identificador)">
		</md-input-container>

		<md-checkbox ng-model="C.Config.img_quickpreview" aria-label="a">Prevista</md-checkbox>

		<div layout>
			<md-input-container class="w80">
				<input type="number" ng-model="C.Config.img_width" placeholder="Ancho (px)">
			</md-input-container>
			<md-icon md-svg-icon="md-close"></md-icon>
			<md-input-container class="w80">
				<input type="number" ng-model="C.Config.img_height" placeholder="Alto (px)">
			</md-input-container>
			<md-input-container flex>
				<label>Ajuste</label>
				<md-select ng-model="C.Config.img_imagemode">
					<md-option ng-repeat="Op in ImageModes" ng-value="Op">{{ Op }}</md-option>
				</md-select>
			</md-input-container>
		</div>

		<md-input-container>
			<input type="text" ng-model="C.Config.img_uploader" placeholder="Url de Carga">
		</md-input-container>

		<div class="h10"></div>
	</div>

	<div layout class="">
		<span flex></span>
		<md-button class="md-raised md-primary margin-5" ng-click="guardarConfig()">Guardar</md-button>
	</div>
	
</md-dialog>