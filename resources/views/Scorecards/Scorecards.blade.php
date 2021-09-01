<div flex id="Scorecards" layout ng-controller="ScorecardsCtrl">
	
	<md-sidenav class="bg-white border-right w300 no-overflow" layout=column 
		md-is-open="ScorecardsNav"
		md-is-locked-open="$mdMedia('gt-xs') && ScorecardsNav">
		
		<div layout class="border-bottom padding-left h40" layout-align="center center">
			<md-select ng-model="ScoSel" flex class="md-no-underline no-margin" aria-label="s" ng-change="openScorecard(ScoSel)">
				<md-option ng-repeat="Opt in ScorecardsCRUD.rows | orderBy:'Titulo' " ng-value="Opt">{{ Opt.Titulo }}</md-option>
			</md-select>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addScorecard()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Tablero</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5 mw300">

			<div ng-repeat="F in NodosFS" class="mh25 borders-bottom padding-0-5 relative text-13px"
				md-ink-ripple layout ng-show="F.show && F.type == 'folder'">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout class="">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition Pointer" ng-class="{'fa-rotate-90':F.open, 'opacity-0':F.children == 0 }" ng-click="FsOpenFolder(NodosFS, F)"></md-icon>
					<div flex style="padding: 5px 0" class="Pointer" ng-click="openNodo(F.file)"
						ng-class="{ 'text-bold': (F.file.id == NodoSel.id) }">
						{{ F.file.Nodo }}
					</div>
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
				<md-input-container class="no-margin-bottom" flex>
					<label>Tablero</label>
					<input type="text" ng-model="ScoSel.Titulo" aria-label=s ng-change="ScoSel.changed = true">
				</md-input-container>
				<md-input-container class="no-margin-bottom w50">
					<label>Abrir a</label>
					<input type="number" ng-model="ScoSel.config.open_to_level" aria-label=s ng-change="ScoSel.changed = true">
					<md-tooltip>Abrir al Nivel</md-tooltip>
				</md-input-container>
			</div>

			<div layout=column ng-show="NodoSel !== null">

				<div layout class="" ng-if="NodoSel.padre_id">
					<div class="w30"></div>
					<md-input-container class="no-margin-bottom" flex>
						<label>Nodo</label>
						<input type="text" ng-model="NodoSel.Nodo" aria-label=s ng-change="NodoSel.changed = true">
					</md-input-container>
					<md-input-container class="no-margin-bottom w50">
						<label>Peso</label>
						<input type="number" ng-model="NodoSel.peso" aria-label=s ng-change="NodoSel.changed = true">
					</md-input-container>

					<div class="w20"></div>

					<md-input-container class="no-margin-bottom">
						<label>Padre</label>
						<md-select class="" ng-model="NodoSel.padre_id" ng-change="NodoSel.changed = true">
							<md-option ng-repeat="Op in NodosCRUD.rows | filter:{tipo:'Nodo'}" ng-value="Op.id" ng-if="Op.id !== NodoSel.id">{{ Op.Nodo }}</md-option>
						</md-select>
					</md-input-container>

				</div>

				@include('Scorecards.Scorecards_Subnodos')

				@include('Scorecards.Scorecards_Indicador')

			</div>


			<div class="h50"></div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="copyUrlDatos()" class=""><md-icon md-font-icon="fa-copy margin-right fa-fw"></md-icon>Copiar URL de Datos</md-button></md-menu-item>
					<md-menu-item ng-show="NodosSelected.length > 0"><md-button ng-click="eraseCacheNodosInd()" class=""><md-icon md-font-icon="fa-eraser margin-right fa-fw"></md-icon>Borrar la Caché de {{ NodosSelected.length }} Indicadores</md-button></md-menu-item>
					<md-menu-item ng-show="NodosSelected.length > 0"><md-button ng-click="moveNodosInd()" class=""><md-icon md-font-icon="fa-sign-out-alt margin-right fa-fw"></md-icon>Mover {{ NodosSelected.length }} Indicadores</md-button></md-menu-item>
					<md-menu-item ng-show="NodosSelected.length > 0"><md-button ng-click="deleteNodosInd()" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Eliminar {{ NodosSelected.length }} Indicadores</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="reindexarNodo(NodoSel)"><md-icon md-font-icon="fa-redo margin-right fa-fw"></md-icon>Reindexar Indicadores / Valores</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="deleteScorecardNodo()" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Eliminar Nodo Actual</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="viewScorecardDiag(ScoSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
				<md-tooltip md-direction="right">Abrir Tablero</md-tooltip>
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateScorecard()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>