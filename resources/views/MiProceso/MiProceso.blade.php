<div flex id="MiProceso" layout ng-controller="MiProcesoCtrl" class="">
	
	<div layout=column class="w300 margin bg-theme border-radius" md-whiteframe=1>
		
		<div class="bg-cover bg-center-center h120 padding border-radius"
			style="background-image: url('img/bg_data1.jpg');
				   box-shadow: inset 0 -90px 30px -60px #313131" 
				   layout=column layout-align="end start">

			<md-select ng-model="a" class="no-margin md-title text-thin md-no-underline">
			  <md-option ng-value="P.id" ng-repeat="P in Usuario.Procesos">{{ P.Proceso }}</md-option>
			</md-select>
			
		</div>
		
		<div ng-repeat="P in Usuario.Procesos">{{ P.Proceso }}</div>
	</div>

	<div flex layout class="" ng-show="!Loading">
		
		

	</div>

</div>