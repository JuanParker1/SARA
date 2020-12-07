<div layout layout-align="center center">
	<md-button class="md-icon-button focus-on-hover no-padding no-margin s30" ng-click="retroalimentarDiag('')">
		<md-icon md-svg-icon="md-feedback" aria-label="Retroalimentar" class="s25"></md-icon>
		<md-tooltip >Retroalimentar</md-tooltip>
	</md-button>
	<div class="w10"></div>
	<md-menu md-position-mode="target-right target">

		<div class="Pointer h100p" layout layout-align="center center" ng-click="$mdMenu.open($event)">
			<div class="s30 bg-lightgrey border-rounded margin-right-5 border" 
				style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
			<div class="text-16px" hide-xs>{{ Usuario.Nombres }}</div>
			<md-button class="md-icon-button no-margin s30 no-padding" aria-label="Button">
				<md-icon md-svg-icon="md-chevron-down"></md-icon>
			</md-button>
		</div>

		
		<md-menu-content class="w180 no-margin no-padding-bottom bg-theme">
			<div class="s120 bg-lightgrey border-rounded margin-0-auto" md-whiteframe=1 
				style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>

			<h3 class="md-title margin text-center">{{ Usuario.Nombres }}</h3>
			<md-menu-item ng-click="navTo('Home', {})" class="UserMenu_Item">
				<md-icon md-font-icon="fa-home no-margin no-padding"></md-icon>Ir a Inicio
			</md-menu-item>
			<md-menu-item ng-click="retroalimentarDiag({})" class="UserMenu_Item">
				<md-icon md-font-icon="fa-comment-dots no-margin no-padding"></md-icon>Retroalimentar
			</md-menu-item>
			<md-menu-item ng-click="Logout()" class="UserMenu_Item">
				<md-icon md-font-icon="fa-power-off no-margin no-padding"></md-icon>Cerrar Sesión
			</md-menu-item>
		</md-menu-content>
	</md-menu>
</div>

<style type="text/css">
	.UserMenu_Item{
		cursor: Pointer;
		min-height: 40px; height: 40px; 
		padding: 10px; box-sizing: border-box; 
		line-height: 24px;
	}
</style>