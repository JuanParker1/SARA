<div layout=column class="relative" ng-show="ProcesoSel.asignaciones.length > 0">
	<div class="text-clear margin-5-0">Nuestro Equipo ({{ ProcesoSel.asignaciones.length }})</div>
	<div layout class="overflow-x hasScroll">
		
		<div class="teammember" layout=column layout-align="center center"
			ng-repeat="TM in ProcesoSel.asignaciones">
			<div class="teammember_image" style="background-image: url({{ 'https://sec.comfamiliar.com/images/fotosEmpleados/' + TM.usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
			<div class="teammember_name">{{ TM.usuario.Nombres }}</div>
		</div>

		<div class="w100"></div>

	</div>
	<div layout=column class="abs bg-white" style="top: 29px; right: -9px" ng-click="goToTab('Equipo')"
		ng-class="{ 'border-left' : ProcesoSel.asignaciones.length > 6 }">
		<md-button class="md-icon-button s70 " style="border: 1px solid #b7b7b7" ng-click="goToTab('Equipo')">
			<md-icon md-svg-icon="md-arrow-forward" class="s40"></md-icon>
		</md-button>
		<div class="teammember_name" style="height: 44px">Ver Todos</div>
	</div>
</div>

<div class="h30"></div>