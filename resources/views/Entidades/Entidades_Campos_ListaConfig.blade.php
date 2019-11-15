<md-dialog layout=column class="mw400">
	
	<div layout class="h30 lh30 padding-left">
		<div flex class="">Opciones para: <b>{{ C.Alias || C.Columna }}</b></div>
		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div layout=column flex class="padding overflow-y darkScroll">
		AAAA
	</div>

	<div layout class="">
		<span flex></span>
		<md-button class="md-raised md-primary margin-5" ng-click="guardarConfig()">Guardar</md-button>
	</div>

</md-dialog>