<md-dialog class="vh100 no-overflow bg-black-2" md-theme="Black" flex=100 aria-label=m>


	<div layout layout-align="center center" class="padding-left">
		<div flex layout>
			<div class="text-16px margin-right-20"><span>{{ Sco.Titulo }}</span></div>
			<md-select ng-model="Modo" class="no-margin md-no-underline" style="transform: translateY(-6px);">
			  <md-option ng-value="Op" ng-repeat="Op in ['Mes','Año']">{{ Op }}</md-option>
			</md-select>
			<div layout ng-show="Modo == 'Mes'">
				<md-button ng-click="periodoAdd(-1)" class="no-margin s20 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
				<div class="h20 lh20">{{ (Anio*100)+Mes }}</div>
				<md-button ng-click="periodoAdd( 1)" class="no-margin s20 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
			</div>
			<div layout ng-show="Modo == 'Año'">
				<md-button ng-click="anioAdd(-1)" class="no-margin s20 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-left"></md-icon></md-button>
				<div class="h20 lh20">{{ Anio }}</div>
				<md-button ng-click="anioAdd( 1)" class="no-margin s20 no-padding md-icon-button"><md-icon class="s20" md-font-icon="fa-fw fa-chevron-right"></md-icon></md-button>
			</div>
		</div>

		<md-button class="md-icon-button" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close"></md-icon>
		</md-button>
	</div>

	@include('Scorecards.ScorecardDiag_Mes')
	@include('Scorecards.ScorecardDiag_Anio')

</md-dialog>