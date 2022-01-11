<div flex layout layout-wrap layout-align="space-around center" class="padding bg-black-3" md-theme="Black" ng-controller="Integraciones_SolgeinCtrl">
	
	<div layout=column layout-align="center center" style="background: #00000029"
		class="s200 border-rounded Pointer" ng-show="Status == 'Iddle'"
		ngf-select="uploadFile($file)" ngf-drop="uploadFile($file)"
		ngf-pattern="'.xls'">
		<md-icon md-font-icon="fa-upload" style="font-size: 4em;transform: translateY(0px);"></md-icon>
		<div class="md-title text-thin lh20 text-center" style="transform: translateY(-30px);">Subir Archivo de Valores</div>
	</div>

	<div layout=column layout-align="center center" style="background: #00000029"
		class="s200 border-rounded Pointer" ng-show="Status == 'Iddle'"
		ngf-select="uploadFileComments($file)" ngf-drop="uploadFileComments($file)"
		ngf-pattern="'.xls'">
		<md-icon md-font-icon="fa-upload" style="font-size: 4em;transform: translateY(0px);"></md-icon>
		<div class="md-title text-thin lh20 text-center" style="transform: translateY(-30px);">Subir Archivo de Comentarios</div>
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

	<div flex=100 layout=column layout-align="center center" class="margin-top-20">
		<md-button ng-repeat="A in AditionalData" class="md-raised bg-black-4 mw300" ng-show="A.data.length > 0" ng-click="viewAditionalData(A)">
			{{ A.title }} ({{ A.data.length }})
		</md-button>
		
	</div>

</div>