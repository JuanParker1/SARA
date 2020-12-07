<div class="padding bg-black-3" flex layout=column md-theme="Black" ng-controller="Integraciones_SOMACtrl">
	<div class="md-title text-thin">Generador Archivos SOMA</div>
	<div layout layout-wrap class="margin-top-20">
		<md-select ng-model="filters.Tipo" aria-label=s class="no-margin">
			<md-option ng-value="'GCFR'">PGPs GC y FR</md-option>
			<md-option ng-value="'ONC'">PGP Oncológico</md-option>
		</md-select>
		<md-datepicker ng-model="filters.Desde" class="text-white"></md-datepicker>
		<div class="w30"></div>
		<md-button class="md-raised bg-ocean" ng-click="downloadFile()">Generar</md-button>
		<md-button class="md-raised bg-green" ng-click="sendSoma()">Enviar Datos</md-button>
	</div>
</div>