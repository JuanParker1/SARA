<div flex id="Scorecards" layout ng-controller="ScorecardsCtrl">
	
	<md-sidenav class="bg-white border-radius border margin-5 w350" layout=column 
		md-is-open="ScorecardsNav"
		md-is-locked-open="$mdMedia('gt-xs') && ScorecardsNav">
		
		<div layout class="border-bottom h30" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
				<input flex type="search" placeholder="Buscar Scorecard..." ng-model="filterScorecards" class="no-padding" ng-change="searchScorecard()" ng-model-options="{ debounce : 500 }">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addScorecard()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Scorecard</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in ScorecardsFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout ng-click="FsOpenFolder(ScorecardsFS, F)" class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition" ng-class="{'fa-rotate-90':F.open}"></md-icon>
					<div flex style="padding: 5px 0">{{ F.name }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openScorecard(F.file)" 
					ng-class="{ 'text-bold' : F.file.id == ScoSel.id }">
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Titulo }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex class="" layout=column ng-show="ScoSel">
		<div flex layout=column class="overflow-y darkScroll padding-5">
			<div layout class="">
				<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="ScorecardsNav = !ScorecardsNav" 
					style="margin-top: 2px !important">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-input-container class="no-margin-top no-margin-bottom" flex>
					<input type="text" ng-model="ScoSel.Titulo" aria-label=s>
				</md-input-container>
			</div>

			<md-chips ng-model="ScoSel.Secciones" placeholder="Secciones" md-enable-chip-edit></md-chips>

			<div class="bg-white border border-radius margin-top">
				<div class="h30" layout layout-align="center center">
					<div class="md-subheader margin-left margin-right">Tarjetas</div>
					<span flex></span>
					<md-button class="md-icon-button no-margin no-padding s30 margin-right" aria-label="b" ng-click="addCard()">
						<md-icon md-svg-icon="md-plus"></md-icon>
						<md-tooltip md-direction="left">Agregar Tarjeta</md-tooltip>
					</md-button>
				</div>
				<md-table-container class="" ng-show="CardsCRUD.rows.length > 0">
					<table md-table class="md-table-short table-col-compress">
						<thead md-head>
							<tr md-row>
								<th md-column>Seccion</th>
								<th md-column>Tipo</th>
								<th md-column></th>
								<th md-column md-numeric></th>
							</tr>
						</thead>
						<tbody md-body>
							<tr md-row class="" ng-repeat="C in CardsCRUD.rows | orderBy:'Indice'" ng-class="{'bg-yellow': C.changed}">
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.seccion_id" aria-label=s ng-change="C.changed = true">
									  <md-option ng-value="null">Ninguna</md-option>
									  <md-option ng-value="k" ng-repeat="(k,S) in ScoSel.Secciones">{{ S }}</md-option>
									</md-select>
								</td>
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.tipo" aria-label=s ng-change="C.elemento_id = null; C.changed = true">
										<md-option ng-value="'Variable'"> <md-icon class="s20" md-font-icon="fa-fw fa-lg fa-superscript"></md-icon>Variable</md-option>
										<md-option ng-value="'Indicador'"><md-icon class="s20" md-font-icon="fa-fw fa-lg fa-chart-line " style="transform: translateY(2px);"></md-icon>Indicador</md-option>
									</md-select>
								</td>
								<td md-cell class="md-cell-compress">
									<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Indicador'" placeholder="Seleccione" ng-change="C.changed = true">
									  <md-option ng-value="Op.id" ng-repeat="Op in IndicadoresCRUD.rows">{{ Op.Indicador }}</md-option>
									</md-select>
									<md-select class="w100p" ng-model="C.elemento_id" aria-label=s ng-if="C.tipo == 'Variable'" placeholder="Seleccione"  ng-change="C.changed = true">
									  <md-option ng-value="Op.id" ng-repeat="Op in VariablesCRUD.rows">{{ Op.Variable }}</md-option>
									</md-select>
								</td>
								<td md-cell class="">
									<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="delCard(C)">
										<md-icon md-svg-icon="md-close"></md-icon>
									</md-button>
								</td>
							</tr>
						</tbody>
					</table>
				</md-table-container>
			</div>






			<div class="h50"></div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu>
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