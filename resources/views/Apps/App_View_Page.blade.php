<div layout=column flex ng-repeat="P in [PageSel]">

	<div flex ng-if="P.Tipo == 'ExternalUrl'">
		<iframe ng-src="{{ getIframeUrl(P.Config.url) }}"></iframe>			
	</div>

	<div flex layout ng-if="P.Tipo == 'Scorecard'" ng-controller="Scorecards_ScorecardDiagCtrl" ng-init="getScorecard(P.Config.element_id, P.Config)">
		@include('Scorecards.ScorecardDiag')
	</div>

	<div flex layout ng-if="P.Tipo == 'Grid'" ng-controller="Entidades_GridDiagCtrl" ng-init="getGrid(P.Config.element_id)">
		@include('Entidades.Entidades_GridDiag')
	</div>

	<div flex layout ng-if="P.Tipo == 'Cargador'" ng-controller="Entidades_CargadorDiagCtrl" ng-init="getCargador(P.Config.element_id)">
		@include('Entidades.Entidades_CargadorDiag')
	</div>

</div>