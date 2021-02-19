<div flex layout=column class="padding bg-black-3" md-theme="Black" 
	ng-controller="Integraciones_IkonoCtrl">

	<div class="md-title text-thin">Sincronización con Ikono</div>

	<div flex layout layout-align="center center">
		
		<div layout=column layout-align="center center" style="background: #00000029"
			class="s200 border-rounded Pointer" ng-show="Status == 'Iddle'"
			ngf-select="uploadFile($file)" ngf-drop="uploadFile($file)"
			ngf-pattern="'.csv'">
			<md-icon md-font-icon="fa-upload" style="font-size: 4em;transform: translateY(0px);"></md-icon>
			<div class="md-title text-thin lh20 text-center" style="transform: translateY(-40px);">Cargar Llamadas</div>
		</div>

		<md-progress-circular md-diameter="100" class="md-warn"
		ng-show="Status == 'Uploading'"></md-progress-circular>

		<div ng-show="Status == 'Ended'" layout>
			<md-icon md-font-icon="fa-check margin-right" style="font-size: 4em; color: #a4ffa4;"></md-icon>
			<div layout=column>
				<div style="white-space: pre;">{{ EndedMsg }}</div>
				<md-button ng-click="ReloadStatus()">Cargar Otro</md-button>
			</div>
		</div>

		<div ng-show="Status == 'Error'" layout>
			<md-icon md-font-icon="fa-exclamation-triangle margin-right" style="font-size: 4em; color: #ff4343;"></md-icon>
			<div layout=column>
				<div style="white-space: pre;">{{ EndedMsg }}</div>
				<md-button ng-click="ReloadStatus()">Cargar Otro</md-button>
			</div>
		</div>

	</div>

</div>
