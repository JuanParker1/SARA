<md-dialog layout class="no-overflow bg-black-2 h100p" md-theme="Black" flex=100 aria-label=m>

	@include('Scorecards.ScorecardDiag_Filters')

	<div flex layout=column>

		<div layout class="h40 border-bottom" layout-align="start center">

			<div flex layout>
				<div class="text-16px margin-0-10" md-truncate>{{ Sco.Titulo }}</div>
				<span flex></span>
			</div>

			<div layout ng-show="Modo == 'Mes'">
				<md-button ng-click="periodoAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
				<div class="h30 lh30 Pointer" ng-click="changeModo('Año')">{{ Periodo }}
					<md-tooltip ng-if="!BlockModo">Ver Año</md-tooltip>
				</div>
				<md-button ng-click="periodoAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
			</div>
			<div layout ng-show="Modo == 'Año'">
				<md-button ng-click="anioAdd(-1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
				<div class="h30 lh30 Pointer" ng-click="changeModo('Mes')">{{ PeriodoDate | date:'yyyy' }}
					<md-tooltip ng-if="!BlockModo">Ver Mes</md-tooltip>
				</div>
				<md-button ng-click="anioAdd( 1)" class="no-margin s30 no-padding md-icon-button"><md-icon class="s30" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
			</div>

			<div flex layout layout-align="end">
				<span flex></span>
				<div layout layout-align="center center"
					class="border-rounded margin-right-5 padding-5-0 w150" 
					ng-click="changeModo()" 
					ng-class="{ 'Pointer bg-black-3': !BlockModo }">
					<div><md-icon md-svg-icon="{{ Modos[Modo][1] }}" class="s20 margin-right-5"></md-icon></div>
					<div>{{ Modos[Modo][0] }}</div>
				</div>
				<md-button class="md-icon-button s30 no-padding only-dialog" aria-label="Button" ng-click="Cancel()">
					<md-icon md-svg-icon="md-close" class=""></md-icon>
				</md-button>

			</div>

		</div>

		<div flex layout=column layout-align="center center" ng-show="Loading">
			
			<div layout class="margin-right-20">
				<md-progress-circular md-mode="indeterminate" md-diameter="40" class="md-warn"></md-progress-circular>
				<div class="md-headline margin-left-20" style="line-height: 40px;">Cargando...</div>
			</div>
			<div class="h100"></div>

		</div>

		<div flex layout ng-show="Modo && !Loading">
			@include('Scorecards.ScorecardDiag_Mes')
			@include('Scorecards.ScorecardDiag_Anio')
		</div>
		

	</div>
</md-dialog>