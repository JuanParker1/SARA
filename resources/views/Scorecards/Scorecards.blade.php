<div flex id="Scorecards" layout ng-controller="ScorecardsCtrl">
	
	<md-sidenav class="bg-white border-right w280" layout=column 
		md-is-open="ScorecardsNav"
		md-is-locked-open="$mdMedia('gt-xs') && ScorecardsNav">
		
		<div layout class="border-bottom padding-left h40" layout-align="center center">
			<md-select ng-model="ScoSel" flex class="md-no-underline no-margin" aria-label="s" ng-change="openScorecard(ScoSel)">
				<md-option ng-repeat="Opt in ScorecardsCRUD.rows | orderBy:'Titulo' " ng-value="Opt">{{ Opt.Titulo }}</md-option>
			</md-select>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addScorecard()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Scorecard</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in NodosFS" class="mh25 borders-bottom padding-0-5 relative text-13px"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout class="">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open}" ng-click="FsOpenFolder(NodosFS, F)"></md-icon>
					<div flex style="padding: 5px 0" class="Pointer" ng-click="openNodo(F.file)">{{ F.name }}</div>
					<div style="padding: 4px" class="text-clear text-right">{{ F.file.peso }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout>
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Nodo }}</div>
					<div style="padding: 5px" class="text-clear text-right">{{ F.file.peso }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex class="" layout=column ng-show="ScoSel !== null">
		<div flex layout=column class="overflow-y darkScroll padding-5">
			
			<div layout class="">
				<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="ScorecardsNav = !ScorecardsNav" 
					style="margin-top: 2px !important">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-input-container class="no-margin-top no-margin-bottom" flex>
					<input type="text" ng-model="ScoSel.Titulo" aria-label=s ng-change="ScoSel.changed = true">
				</md-input-container>
				<md-input-container class="no-margin-top no-margin-bottom w40">
					<input type="number" ng-model="ScoSel.config.open_to_level" aria-label=s ng-change="ScoSel.changed = true">
					<md-tooltip>Abrir al Nivel</md-tooltip>
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

				@include('Scorecards.Scorecards_Subnodos')

				@include('Scorecards.Scorecards_Indicador')

			</div>


			<div class="h50"></div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu hide>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="deleteScorecardNodo()"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Eliminar Nodo</md-button></md-menu-item>
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