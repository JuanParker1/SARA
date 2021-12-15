<div flex layout>
	
	<div class="w215 bg-white border-right" layout=column>
		
		<div layout class="border-bottom h40" layout-align="center center" 
			ng-show="!PerfilesCRUD.ops.loading">
			<div class="md-toolbar-searchbar" flex layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 6px 0 9px;"></md-icon>
				<input flex type="search" placeholder="Buscar Perfil..." ng-model="filterPerfiles" class="no-padding">
			</div>
			<md-button class="md-icon-button no-margin" aria-label="b" ng-click="addPerfil()">
				<md-icon md-svg-icon="md-plus"></md-icon>
				<md-tooltip md-direction=left>Agregar Perfil</md-tooltip>
			</md-button>
		</div>

		<div layout layout-align="center center" 
			ng-repeat="P in PerfilesCRUD.rows | filter:filterPerfiles | orderBy:'Orden' " 
			ng-click="openPerfil(P)" 
			ng-class="{ 'opacity-90': (P.id == PerfilSel.id) }"
			class="padding Pointer focus-on-hover">
			<md-icon md-font-icon="fa-tag fa-fw text-13px margin-right-5"></md-icon>
			<div flex>{{ P.Perfil }}</div>
		</div>

	</div>



	<div flex layout=column >
		
		<div flex layout=column layout-align="start center" class="padding overflow-y">

			<div class="bg-white w100p mxw450 border-radius padding" md-whiteframe=1
				layout=column>
					
				<div layout>
					<md-input-container class="" flex>
						<label>Perfil</label>
						<input type="text" ng-model="PerfilSel.Perfil" class="md-title">
					</md-input-container>

					<md-input-container class="w130">
						<label>Plural</label>
						<input type="text" ng-model="PerfilSel.Perfil_Show" class="">
					</md-input-container>

					<md-input-container class="w50">
						<label>Orden</label>
						<input type="number" ng-model="PerfilSel.Orden" class="">
					</md-input-container>
				</div>

				<div class="md-subheader margin-bottom-5">Acceso a Secciones</div>
				<div layout=column class="border border-radius">
					<div ng-repeat="S in PerfilSel.secciones" layout layout-align="center center" class="h30 border-bottom">
						<md-icon md-font-icon="{{ S.Icono }} fa-fw margin-0-5"></md-icon>
						<div flex>{{ S.Seccion }}</div>
						<md-select class="no-margin w180 md-no-underline" ng-model="S.Level" aria-label=s
							ng-class="{ 'opacity-40': S.Level == 0 }">
							<md-option ng-repeat="(kN, N) in NivelesAcceso" ng-value="kN">
								<md-icon md-font-icon="{{ N[1] }} fa-fw" style="transform: translateY(4px);"></md-icon>
								{{ N[0] }}
							</md-option>
						</md-select>
					</div>
				</div>

			</div>

			<div class="h50"></div>

		</div>

		<div class="border-top bg-white" layout>
			
			<span flex></span>
			<md-button class="md-raised md-primary" ng-click="savePerfil()">
				Guardar
			</md-button>

		</div>


	</div>


</div>