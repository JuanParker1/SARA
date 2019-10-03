<div flex layout=column class="overflow-y hasScroll padding-0-10" ng-show="Modo == 'Mes'">
	
	<div ng-repeat="S in Secciones" layout=column ng-show="S.cards > 0" class="margin-bottom">
		
		<div layout ng-if="S.Seccion !== null" ng-click="S.open = !S.open" class="Pointer">
			<md-icon md-font-icon="fa-chevron-right fa-fw s20 margin-left-5 transition" ng-class="{'fa-rotate-90':S.open}"></md-icon>
			<div class="md-subhead text-clear margin-left-5" flex>{{ S.Seccion }}</div>
		</div>

		<div layout layout-wrap ng-show="S.open">
		<div layout=column class="card" flex=100 flex-gt-xs=50 flex-gt-sm=33 flex-gt-md=20 ng-repeat="C in Sco.cards | filter:{ seccion_name: S.Seccion }">
			<div layout=column class="mh130 bg-black-3 padding-5 margin-5 relative border-radius scorecard" md-whiteframe=2 md-ink-ripple
				ng-style="{ 'animation-delay': C.animation_delay }">
				
				<div ng-show="C.tipo == 'Indicador'" flex layout=column class="Pointer" ng-click="viewIndicadorDiag(C.elemento_id)"
					ng-repeat="I in [Sco.elementos.Indicador[C.elemento_id]]">
					<div class="card_title" layout>
						<div flex>{{ I.Indicador }}</div>
						<md-icon class="s20" md-font-icon="{{Sentidos[I.Sentido].icon}}">
							<md-tooltip md-direction=left>{{ Sentidos[I.Sentido].desc }}</md-tooltip>
						</md-icon>
					</div>
					<div flex layout layout-align="center center" style="color: {{ I.valores[(Anio*100)+Mes].color }}">
						<div class="card_value">{{ I.valores[(Anio*100)+Mes].val }}</div>
					</div>
					<div class="card_subtext">Meta: {{ I.valores[(Anio*100)+Mes].meta_val }}</div>
				</div>

				<div ng-show="C.tipo == 'Variable'" flex layout=column class="Pointer" ng-click="viewVariableDiag(C.elemento_id)"
					ng-repeat="V in [Sco.elementos.Variable[C.elemento_id]]">
					<div class="card_title" layout><div flex>{{ V.Variable }}</div></div>
					<div flex layout layout-align="center center">
						<div class="card_value">{{ V.valores[(Anio*100)+Mes].val }}</div>
					</div>
					<div class="card_subtext"></div>
				</div>

			</div>
		</div>
		</div>

	</div>

	<div class="h50"></div>

</div>

<style type="text/css">
	.card_title{ font-size: 0.9em; opacity: 0.5;  }
	.card_value{ font-size: 2.3em; text-shadow: 2px 2px 4px #0000008f; font-weight: 300; }
	.card_subtext{ text-align: right; font-size: 0.9em; opacity: 0; transition: all 0.3s; }
	.card:hover .card_subtext{ opacity: 0.5;  }
	.scorecard{
		/*opacity: 0;*/
	    animation: 400ms cubic-bezier(0.18, 1, 0.63, 1.21) 100ms 1 normal both running In_FadeScale;
	}
</style>