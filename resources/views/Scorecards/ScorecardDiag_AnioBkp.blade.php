<div flex layout=column class="overflow-y hasScroll" ng-show="Modo == 'Año'">
	
	<md-table-container class="border-bottom">
		<table md-table class="md-table-short table-col-compress">
			<thead md-head>
				<tr md-row class="">
					<th md-column></th>
					<th md-column md-numeric ng-repeat="M in Meses" class="mw45">{{ M[1] }}</th>
					<th md-column  md-numeric>Meta</th>
				</tr>
			</thead>
			<tbody md-body class="text-14px Pointer" ng-repeat="S in Secciones">

				<tr md-row ng-if="S.Seccion !== null && S.cards > 0" ng-click="S.open = !S.open" >
					<td md-cell class="" style="padding-left: 5px !important"><md-icon md-font-icon="fa-chevron-right fa-fw s20 transition margin-right-5" ng-class="{'fa-rotate-90':S.open}"></md-icon>{{ S.Seccion }}</td>
					<td md-cell class="" colspan=13></td>
				</tr>

				<tr md-row class="md-row-hover" ng-show="S.open" ng-repeat="C in Sco.cards | filter:{ seccion_name: S.Seccion, tipo: 'Indicador' }"
					ng-click="viewIndicadorDiag(C.elemento_id)">
					<td md-cell class="w260" style="padding-left: 5px !important"><div layout class="text-13px">
						<div class="w25"></div>{{ Sco.elementos.Indicador[C.elemento_id].Indicador }}
					</div></td>
					<td md-cell class="" ng-repeat="M in Meses">
						<div ng-repeat="I in [ Sco.elementos.Indicador[C.elemento_id].valores[Anio+M[0]] ]">
							<span ng-style="{ color: I.color }" class="text-15px">{{ I.val }}</span>
						</div>
					</td>
					<td md-cell ng-repeat="I in [ Sco.elementos.Indicador[C.elemento_id] ]"> 
						{{ I.valores[Anio+'12'].meta_val }}
						<md-icon class="s15 margin-left-5" md-font-icon="{{ Sentidos[I.Sentido].icon}} fa-fw">
							<md-tooltip md-direction=left>{{ Sentidos[I.Sentido].desc }}</md-tooltip>
						</md-icon>
					</td>
				</tr>

				<tr md-row class="md-row-hover" ng-show="S.open" ng-repeat="C in Sco.cards | filter:{ seccion_name: S.Seccion, tipo: 'Variable' }"
					ng-click="viewVariableDiag(C.elemento_id)">
					<td md-cell class="w260" style="padding-left: 5px !important"><div layout>
						<div class="w25"></div>{{ Sco.elementos.Variable[C.elemento_id].Variable }}
					</div></td>
					<td md-cell class="" ng-repeat="M in Meses" >
						{{ Sco.elementos.Variable[C.elemento_id].valores[Anio+M[0]].val }}
					</td>
					<td md-cell></td>
				</tr>

				



			</tbody>
		</table>
	</md-table-container>

	<div class="h50"></div>

</div>