<div id="Integraciones" ng-controller="IntegracionesCtrl" flex layout>
	<div class="border-right w220 overflow-y hasScroll hide-in-iframe" >
		<md-subheader>Integraciones</md-subheader>
		<md-list class="no-padding">
			<md-list-item ng-repeat="I in IntegracionesCRUD.rows" 
				ng-class="{ 'bg-black-5': I.id == State.route[3] }"
				href="#/Home/Integraciones/{{ I.id }}">{{ I.Integracion }}</md-list-item>
		</md-list>
	</div>
	<div flex layout ui-view></div>
</div>