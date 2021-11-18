<div id="Home" flex layout="column" mds-theme="{{ mainTheme }}" ng-class="'theme-'+mainTheme">
	<md-toolbar id="HomeToolbar" class="md-short border-bottom bg-theme">
		<div class="md-toolbar-tools no-padding" layout>

			<md-button class="md-icon-button w40 mw40" aria-label="Button" ng-click="mainSidenav()" style="margin-left: 4px;margin-right: 1px;" hide>
				<md-icon md-svg-icon="md-bars"></md-icon>
			</md-button>
			<img src="img/Logo.png" class="w25 h25" style="margin: 0 12px">
			<h3 class="md-headline text-bold" hides-xs md-truncate flex>{{ Usuario.app_name }}</h3>

			@include('Core.UserMenu')
			
		</div>
	</md-toolbar>

	<div flex layout>
		
		<div id="HomeNavigation" class="w50 bg-theme transition border-right overflow-y hasScroll" layout=column>
			<a layout layout-align="center center" 
				class="Seccion no-underline" ng-class="{ 'itemsel' : State.route.length < 3 }" md-ink-ripple 
				href="{{ Usuario.Url }}#/Home" target="_self"
				style="margin-bottom: 1px;">
				<md-icon class="fa-fw fa-lg" md-font-icon="fa-home"></md-icon>
				<md-tooltip md-direction="right">Inicio</md-tooltip>
			</a>
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

			#Home.theme-Black .Seccion.itemsel{
 			    background: #404040;
    			border-top: 1px solid #2d2d2d;
    			border-bottom: 1px solid #2d2d2d;
    		}

		</style>

		<div id='Section' ui-view flex layout="column" class="">
			@include('Inicio')
		</div>

	</div>

	
</div>
