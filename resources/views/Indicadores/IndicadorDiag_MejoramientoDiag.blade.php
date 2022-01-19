<md-dialog class="w380 hasScroll" layout=column md-theme="Black"
	style="max-height: 96% !important;">

	<div layout layout-align="center center" class="">
		<div class="md-title text-14px padding-left" flex>Análisis y Mejoramiento {{ Periodo }}</div>
		<md-button class="md-icon-button no-margin no-padding focus-on-hover s30" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	<div layout=column class="padding">
		
		<div ng-repeat="C in Comentarios" md-whiteframe=2 class="comment" layout=column>
			@include('Indicadores.IndicadorDiag_Comment')
		</div>

		<div class="h20"></div>

	</div>

</md-dialog>