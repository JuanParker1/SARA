<div id="Bots" flex layout ng-controller="BotsCtrl">
	
	<md-sidenav class="w250 no-margin bg-theme border-right no-overflow" layout=column
		md-is-locked-open="$mdMedia('gt-xs') && BotsNav" 
		mdd-is-open="BotsNav">

		<div class="padding-5 border-bottom"  layout layout-align="center center">
			<md-icon md-font-icon="fa-robot fa-fw margin-right-5 fa-lg"></md-icon>
			<div class="lh25 text-bold" flex>Bots</div>
			<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="addBot()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Bot</md-tooltip>
			</md-button>
		</div>	

		<div ng-repeat="B in BotsCRUD.rows" class="padding-5 text-14px Pointer mw250" layout layout-align="center center" ng-click="openBot(B)"
			ng-class="{ 'bg-darkgrey' : (B.id == BotSel.id) }">
			<div class="s15  border-rounded" style="margin: 0 11px 0 6px;" ng-style="{ 'background-color': EstadosBotsDet[B.Estado].Color }"></div>
			<div flex>{{ B.Nombre }}</div>
		</div>

	</md-sidenav>

	<div class="w380  bg-theme margin-but-right border-radius" layout=column>
		<md-content flex layout=column class="overflow-y hasScroll padding border-radius" md-theme="Transparent">
			@include('Bots.Bot_Detail')
		</md-content>
		<div layout layout-align="center center" class="padding-left-5">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="runBot()"><md-icon md-font-icon="fa-play margin-right fa-lg fa-fw"></md-icon>Correr el Bot</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="seeLogs()"><md-icon md-font-icon="fa-clipboard-list margin-right fa-lg fa-fw"></md-icon>Ver Logs</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<span flex></span>
			<md-progress-circular md-mode="indeterminate" md-diameter="30" class="md-warn" ng-show="BotRunning"></md-progress-circular>
			<md-button class="md-raised md-primary margin" ng-click="saveBot()">Guardar Cambios</md-button>
		</div>
	</div>

	<div flex layout=column class="overflow-y hasScroll" md-theme="Transparent">

		@include('Bots.Bot_Pasos')
		
	</div>

</div>