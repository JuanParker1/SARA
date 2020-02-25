<div flex id="Scorecards" layout ng-controller="ScorecardsCtrl">
	
	<md-sidenav class="bg-white border-right w280" layout=column 
		md-is-open="ScorecardsNav"
		md-is-locked-open="$mdMedia('gt-xs') && ScorecardsNav">
		
		<div layout class="border-bottom padding-left h40" layout-align="center center">
			<md-select ng-model="ScoSel" flex class="md-no-underline no-margin" aria-label="s" ng-change="openScorecard(ScoSel)">
				<md-option ng-repeat="Opt in ScorecardsCRUD.rows" ng-value="Opt">{{ Opt.Titulo }}</md-option>
			</md-select>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addScorecard()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Scorecard</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in NodosFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout class="">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open}" ng-click="FsOpenFolder(NodosFS, F)"></md-icon>
					<div flex style="padding: 5px 0" class="Pointer" ng-click="openNodo(F.file)">{{ F.name }}</div>
					<div style="padding: 4px" class="text-clear text-right">{{ F.file.peso }}</div>
					<md-menu class="child">
						<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin margin-right-5 no-padding s25" aria-label="m">
							<md-icon md-svg-icon="md-more-h" class="s20"></md-icon>
						</md-button>
						<md-menu-content class="no-padding">
							<md-menu-item><md-button ng-click="addNodo(F.file)"><md-icon md-font-icon="fa-plus margin-right fa-fw"></md-icon>Agregar Nodo</md-button></md-menu-item>
						</md-menu-content>
					</md-menu>
				</div>
				<div ng-show="F.type == 'file'" flex layout>
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Nodo }}</div>
					<div style="padding: 5px" class="text-clear text-right">{{ F.file.peso }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex class="" layout=column>
		<div flex layout=column class="overflow-y darkScroll padding-5">
			
			<div layout class="">
				<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="ScorecardsNav = !ScorecardsNav" 
					style="margin-top: 2px !important">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-input-container class="no-margin-top no-margin-bottom" flex>
					<input type="text" ng-model="ScoSel.Titulo" aria-label=s ng-change="ScoSel.changed = true">
				</md-input-container>
			</div>

			<div layout=column ng-show="NodoSel !== null">

				<div layout class="">
					<div class="text-clear" style="padding: 8px 5px 0 6px;">Nodo</div>
					<md-input-container class="no-margin-top no-margin-bottom" flex>
						<input type="text" ng-model="NodoSel.Nodo" aria-label=s ng-change="NodoSel.changed = true">
					</md-input-container>
					<md-input-container class="no-margin-top no-margin-bottom w50 text-right">
						<md-tooltip>Peso</md-tooltip>
						<input type="number" ng-model="NodoSel.peso" aria-label=s ng-change="NodoSel.changed = true">
					</md-input-container>

					<div class="w20"></div>
					<md-select class="no-margin" ng-model="NodoSel.padre_id" ng-change="NodoSel.changed = true">
						<md-tooltip md-direction=left>Padre</md-tooltip>
						<md-option ng-repeat="Op in NodosCRUD.rows | filter:{tipo:'Nodo'}" ng-value="Op.id" ng-if="Op.id !== NodoSel.id">{{ Op.Nodo }}</md-option>
					</md-select>

				</div>

				<div class="bg-white border border-radius margin-top">
					<div class="h30" layout layout-align="center center">
						<div class="md-subheader margin-left margin-right">Indicadores</div>
						<span flex></span>
						<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addIndicador()">
							<md-icon md-svg-icon="md-plus"></md-icon>
							<md-tooltip md-direction="left">Agregar Indicador</md-tooltip>
						</md-button>
					</div>
					<md-table-container class="" ng-show="NodoSel.indicadores.length > 0">
						<table md-table class="md-table-short table-col-compress">
							<thead md-head>
							</thead>
							<tbody md-body>
								<tr md-row class="" ng-repeat="C in NodoSel.indicadores | orderBy:'Indice'" ng-class="{'bg-yellow': C.changed}">
									<td md-cell class="md-cell-compress">
										<md-select class="w100p" ng-model="C.tipo" aria-label=s ng-change="C.elemento_id = null; C.changed = true">
											<md-option ng-value="'Variable'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</md-option>
											<md-option ng-value="'Indicador'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Indicador</md-option>
										</md-select>
									</td>
									<td md-cell class="md-cell-compress">
										<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Indicador'" placeholder="Seleccione" ng-change="C.changed = true">
										  <md-option ng-value="Op.id" ng-repeat="Op in IndicadoresCRUD.rows">
										  	<span class="text-clear">{{ Op.proceso.Proceso }}&nbsp;&nbsp;</span>{{ Op.Indicador }}</md-option>
										</md-select>
										<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Variable'" placeholder="Seleccione"  ng-change="C.changed = true">
										  <md-option ng-value="Op.id" ng-repeat="Op in VariablesCRUD.rows">{{ Op.Variable }}</md-option>
										</md-select>
									</td>
									<td md-cell class="h30" layout>
										<span flex></span>
										<md-input-container class="no-margin w50  md-no-underline no-padding h30">
											<md-tooltip md-direction=left>Indice</md-tooltip>
											<input type="number" ng-model="C.Indice" aria-label="s" class="text-right" ng-change="C.changed = true">
										</md-input-container>
										<md-input-container class="no-margin w50  md-no-underline no-padding h30">
											<md-tooltip md-direction=left>Peso</md-tooltip>
											<input type="number" ng-model="C.peso" aria-label="s" class="text-right" ng-change="C.changed = true">
										</md-input-container>
										<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" aria-label="b" ng-click="delIndicador(C)">
											<md-tooltip md-direction=left>Eliminar</md-tooltip>
											<md-icon md-svg-icon="md-close"></md-icon>
										</md-button>
									</td>
								</tr>
							</tbody>
						</table>
					</md-table-container>
				</div>

			</div>


			<div class="h50"></div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu hide>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="copyScorecard()"><md-icon md-font-icon="fa-copy margin-right fa-fw"></md-icon>Copiar Scorecard</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="viewScorecardDiag(ScoSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateScorecard()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>