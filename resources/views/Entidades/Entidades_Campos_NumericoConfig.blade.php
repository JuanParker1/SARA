<md-dialog layout=column class="w350" style="max-height: 100%;">
	
	<div layout class="h30 lh30 padding-left">
		<div flex class="">Opciones para: <b>{{ C.Alias || C.Columna }}</b></div>
		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>
	</div>

	<div layout=column flex class="overflow-y darkScroll padding">
		
		<div layout>
			<md-input-container flex>
				<input type="number" ng-model="C.Op1" placeholder="Mínimo">
			</md-input-container>
			<md-input-container flex>
				<input type="number" ng-model="C.Op2" placeholder="Máximo">
			</md-input-container>
		</div>
		
		<md-checkbox ng-model="C.Config.use_alerts" aria-label="a">Usar Alertas</md-checkbox>

		<div layout=column ng-show="C.Config.use_alerts">
			
			<div ng-repeat="(kA,A) in C.Config.alerts" layout layout-align="center center"
				class="bg-lightgrey-5 border-radius padding-left-5 border"
				style="margin-bottom: 5px;">
				<md-input-container class="no-margin-bottom md-no-underline" flex>
					<input type="number" ng-model="A.upto" placeholder="Hasta" ng-blur="reorderAlertas()">
				</md-input-container>
				<input type="color" ng-model="A.color" class="s30 no-margin no-padding">
				<md-button class="md-icon-button focus-on-hover no-margin" ng-click="removeAlerta(kA)">
					<md-icon md-font-icon="fa-trash"></md-icon>
				</md-button>
			</div>

			<md-button class="no-margin" ng-click="addAlerta()">
				<md-icon md-font-icon="fa-plus"></md-icon> Agregar
			</md-button>

			<div class="h60"></div>

		</div>

	</div>

	<div layout class="">
		<span flex></span>
		<md-button class="md-raised md-primary margin-5" ng-click="guardarConfig()">Guardar</md-button>
	</div>
	
</md-dialog>