<md-card class="bg-theme margin-but-bottom " layout=column ng-repeat="P in PasosCRUD.rows | orderBy:'Indice' ">
	<div layout class="h40 padding-left-5" layout-align="center center">

		<md-button class="md-icon-button no-margin drag-handle no-padding s30" aria-label="b" ass-sortable-item-handle>
			<md-icon md-svg-icon="md-drag-handle"></md-icon>
		</md-button>

		<md-button ng-click="P.config.open = !P.config.open" class="md-icon-button no-margin" aria-label="m">
			<md-icon md-font-icon="fa-chevron-right fa-fw transition" ng-class="{ 'fa-rotate-90': P.config.open }"></md-icon>
		</md-button>
		<div class="margin-right text-clear text-14px">{{ PasosCRUD.columns[3]['Options']['options'][P.Tipo] }}</div>
		<md-input-container class="no-margin md-no-underline" md-no-float flex=50>
			<input type="text" ng-model="P.Nombre" placeholder="Nombre">
		</md-input-container>
		<span flex></span>

		<div layout ng-show="P.Tipo == 'Sql'">
			<md-select ng-model="P.config.bdd_id" class="md-no-underline no-margin" aria-label="s">
				<md-option ng-repeat="Opt in Bdds" ng-value="Opt.id"><md-icon md-font-icon="fa-database" style="transform: translateY(-3px);"></md-icon>{{ Opt.Nombre }}</md-option>
			</md-select>
		</div>

		<md-input-container class="no-margin md-no-underline w30" md-no-float>
			<input type="text" ng-model="P.Indice" placeholder="Indice">
		</md-input-container>

		<md-menu>
			<md-button ng-click="$mdMenu.open($event)" class="md-icon-button no-margin" aria-label="m">
				<md-icon md-svg-icon="md-more-v"></md-icon>
			</md-button>
			<md-menu-content>
				<md-menu-item><md-button ng-click="delPaso(P)" class="md-warn"><md-icon md-font-icon="fa-trash margin-right fa-fw"></md-icon>Eliminar Paso</md-button></md-menu-item>
			</md-menu-content>
		</md-menu>
	</div>
	<div layout=column ng-show="P.config.open">
		<div ng-if="P.Tipo == 'Url'" layout=column>
			<div layout class="padding-but-top">
				<md-input-container class="no-margin">
					<md-select ng-model="P.config.req_type">
					  <md-option ng-value="Op" ng-repeat="Op in ['GET','POST']">{{ Op }}</md-option>
					</md-select>
				</md-input-container >
				<md-input-container class="no-margin" flex md-no-float>
					<input type="url" ng-model="P.config.req_url" placeholder="Url">
				</md-input-container>
			</div>
			<div ui-ace="aceOptionsJs" class="" ng-model="P.config.req_params" ng-show="P.config.req_type == 'POST'"></div>
		</div>

		<div ng-if="P.Tipo == 'Sql'" layout=column>
			<div ui-ace="aceOptionsSql" class="" ng-model="P.config.sql"></div>
		</div>
	</div>


	
</md-card>

<div class="padding" layout layout-align="center center">
	<md-button class="no-margin" aria-label="b" ng-click="addPaso()">
		<md-icon md-svg-icon="md-plus" class="margin-right-5"></md-icon>Agregar Paso
	</md-button>
</div>