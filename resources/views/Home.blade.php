<div id="Home" flex layout="column">
	<md-toolbar class="md-short border-bottom bg-white" md-theme="Snow_White">
		<div class="md-toolbar-tools no-padding" layout>

			<md-button class="md-icon-button w40 mw40" aria-label="Button" ng-click="mainSidenav()" style="margin-left: 4px;margin-right: 1px;" hide>
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<img src="img/Logo.png" class="w25 h25" style="margin: 0 12px">
			<h3 class="md-headline text-bold" hide-xs><% env('APP_NAME') %></h3>

			<span flex></span>

			<div class="md-toolbar-searchbar text-clear text-15px" layout>
				<md-icon md-font-icon="fa-search" class="fa-fw" style="margin: 8px 4px 0 8px;"></md-icon>
				<input flex type="search" placeholder="Buscar..." ng-model="a" class="no-padding w100">
			</div>

			
			<md-menu md-position-mode="target-right target">

				<div class="Pointer padding-right" layout layout-align="center center" ng-click="$mdMenu.open($event)">
					<div class="s30 bg-lightgrey border-rounded margin-right-5 border" 
						style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>
					<div class="text-16px" hide-xs>{{ Usuario.Nombres }}</div>
					<md-button class="md-icon-button no-margin" aria-label="Button">
						<md-icon md-font-icon="fa-chevron-down fa-fw"></md-icon>
					</md-button>
				</div>

				
				<md-menu-content class="w170 no-margin">
					<div class="s120 bg-lightgrey border-rounded margin-0-auto" md-whiteframe=1 
						style="background-image: url({{ 'http://sec.comfamiliar.com/images/fotosEmpleados/' + Usuario.Cedula + '.jpg' }}); background-size: cover; background-position: top center;"></div>

					<h3 class="md-title margin text-center">{{ Usuario.Nombres }}</h3>
					<md-menu-item>
						<md-button ng-click="Logout()">
							<md-icon md-font-icon="fa-power-off no-margin"></md-icon>Salir
						</md-button>
					</md-menu-item>
				</md-menu-content>
			</md-menu>
			
			
		</div>
	</md-toolbar>

	<div flex layout>
		
		<div class="w50 bg-white border-right overflow-y darkScroll" layout=column>
			<div layout layout-align="center center" class="Seccion" ng-class="{ 'itemsel' : State.route.length < 3 }" md-ink-ripple ng-click="navTo('Home')" 
				style="margin-bottom: 1px;">
				<md-icon class="fa-fw fa-lg" md-font-icon="fa-home"></md-icon>
				<md-tooltip md-direction="right">Inicio</md-tooltip>
			</div>
			<a ng-repeat="S in Usuario.Secciones" layout layout-align="center center" 
				class="Seccion no-underline" ng-class="{ 'itemsel' : (S.id == State.route[2] ) }" md-ink-ripple 
				href="{{ Usuario.Url }}#/Home/{{S.id}}" target="_self">
				<md-icon class="fa-fw fa-lg" md-font-icon="{{ S.Icono }}"></md-icon>
				<md-tooltip md-direction="right">{{ S.Seccion }}</md-tooltip>	
			</a>
			<div class="h20"></div>
		</div>

		<style type="text/css">
			
			#Home .Seccion{
				position: relative;
			    height: 40px;
			    cursor: pointer;
			    border-top:    1px solid transparent;
			    border-bottom: 1px solid transparent;
			    opacity: 0.7;
			    transition: all 0.3s;
			    outline: none;
			}

			#Home .Seccion:hover{
			    opacity: 1;
			}

			#Home .Seccion.itemsel{
			    opacity: 1;
			    background: #eaeaea;
			    border-top: 1px solid #e1e1e1;
			    border-bottom: 1px solid #e1e1e1;
			}

		</style>

		<div id='Section' ui-view flex layout="column" class="">
			@include('Inicio')
		</div>

	</div>

	
</div>
