<div flex id="Indicadores" layout ng-controller="IndicadoresCtrl">
	
	<md-sidenav class="bg-white border-radius border margin-5 w350" layout=column 
		md-is-open="IndicadoresNav"
		md-is-locked-open="$mdMedia('gt-xs') && IndicadoresNav">
		
		<div layout class="border-bottom h30" layout-align="center center">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 3px 4px 0 5px;"></md-icon>
				<input flex type="search" placeholder="Buscar Indicador..." ng-model="filterIndicadores" class="no-padding" ng-change="searchIndicador()" ng-model-options="{ debounce : 500 }">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addIndicador()">
				<md-icon md-font-icon="fa-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Indicador</md-tooltip>
			</md-button>
		</div>

		<div layout=column flex class="overflow-y darkScroll padding-top-5">

			<div ng-repeat="F in IndicadoresFS" class="mh25 borders-bottom padding-0-5 relative text-13px show-child-on-hover"
				md-ink-ripple layout ng-show="F.show">
				<div ng-style="{ width: (F.depth * 12) }"></div>
				<div ng-show="F.type == 'folder'" flex layout ng-click="FsOpenFolder(IndicadoresFS, F)" class="Pointer">
					<md-icon md-font-icon="fa-chevron-right  fa-fw transition" ng-class="{'fa-rotate-90':F.open}"></md-icon>
					<div flex style="padding: 5px 0">{{ F.name }}</div>
				</div>
				<div ng-show="F.type == 'file'" flex layout class="Pointer" ng-click="openIndicador(F.file)" 
					ng-class="{ 'text-bold' : F.file.id == IndSel.id }">
					<div flex style="padding: 5px 0 5px 12px">{{ F.file.Indicador }}</div>
				</div>
			</div>

			<div class="h50"></div>
		</div>

	</md-sidenav>

	<div flex class="" layout=column>
		<div flex layout=column class="overflow-y darkScroll padding-5">
			<div layout class="">
				<md-button class="md-icon-button no-margin no-padding s30" aria-label="b" ng-click="IndicadoresNav = !IndicadoresNav" 
					style="margin-top: 2px !important">
					<md-icon md-svg-icon="md-bars" class=""></md-icon>
				</md-button>
				<md-input-container class="no-margin-top margin-bottom" flex>
					<input type="text" ng-model="IndSel.Indicador" aria-label=s>
				</md-input-container>
				<md-input-container class="no-margin-top margin-bottom">
					<md-tooltip md-direction="top">Tipo de Dato</md-tooltip>
					<md-select ng-model="IndSel.TipoDato" aria-label=s>
						<md-option ng-repeat="Op in tiposDatoInd" ng-value="Op">{{ Op }}</md-option>
					</md-select>
				</md-input-container>
				<md-input-container class="no-margin-top margin-bottom w40">
					<md-tooltip md-direction="top">Decimales</md-tooltip>
					<input type="number" ng-model="IndSel.Decimales" aria-label=s min=0>
				</md-input-container>
				<md-input-container class="no-margin-top margin-bottom">
					<md-tooltip md-direction="top">Sentido</md-tooltip>
					<md-select ng-model="IndSel.Sentido" aria-label=s>
						<md-option ng-value="'ASC'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-up"></md-icon></md-option>
						<md-option ng-value="'RAN'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-right"></md-icon></md-option>
						<md-option ng-value="'DES'"><md-icon class="s20" md-font-icon="fa-fw fa-arrow-down"></md-icon></md-option>
					</md-select>
				</md-input-container>
			</div>

			<div layout>
				<md-input-container flex class="no-margin-top margin-bottom" md-no-float>
					<textarea ng-model="IndSel.Definicion" rows=1 placeholder="Definición"></textarea>
				</md-input-container>
			</div>

			@include('Indicadores.Indicadores_Formula')
			@include('Indicadores.Indicadores_Metas')

			<div class="h50"></div>
		</div>

		<div layout class="border-top bg-lightgrey-5">
			<md-menu>
				<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
					<md-icon md-svg-icon="md-more-v"></md-icon>
				</md-button>
				<md-menu-content>
					<md-menu-item><md-button ng-click="openIndicador(IndSel)"><md-icon md-font-icon="fa-sync-alt margin-right fa-fw"></md-icon>Recargar Indicador</md-button></md-menu-item>
					<md-menu-item><md-button ng-click="copyIndicador()"><md-icon md-font-icon="fa-copy margin-right fa-fw"></md-icon>Copiar Indicador</md-button></md-menu-item>
				</md-menu-content>
			</md-menu>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="viewIndicadorDiag(IndSel.id)">
				<md-icon md-font-icon="fa-external-link-alt fa-fw fa-lg"></md-icon>
			</md-button>
			<span flex></span>
			<md-button class="md-primary md-raised mh30 h30 lh30" ng-click="updateIndicador()">
				<md-icon md-svg-icon="md-save" class="margin-right-5 s20"></md-icon>Guardar
			</md-button>
		</div>

	</div>

</div>