<md-dialog class="w300" aria-label=d layout=column>
	
	<div class="h30 padding-left " layout layout-align="center center">

		<div class="text-bold" flex>{{ C.campo_title }}</div>

		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" 
			aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>

	</div>

	<canvas id="GaugeCanvas"></canvas>

	<div class="text-center md-display-1 margin-bottom">{{ val }}</div>

</md-dialog>