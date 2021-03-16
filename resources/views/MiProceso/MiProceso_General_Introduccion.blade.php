<div class="text-clear margin-bottom-5">Introducción</div>
<div layout class="margin-bottom">
	<md-input-container flex class="no-margin" md-no-float>
		<textarea ng-model="ProcesoSel.Introduccion" placeholder="Escriba aquí una introducción corta..." 
			rows="3" ng-change="markIntro()"></textarea>
	</md-input-container>
	<md-button class="md-raised md-primary no-margin-right" ng-show="addedIntro" ng-click="saveIntro()">Guardar</md-button>
</div>
<div class="h20"></div>