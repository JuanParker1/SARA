<div flex ng-controller="App_ViewCtrl" layout=column class="app" ng-class="ops.general_class">
	
	<div layout ng-style="{ backgroundColor: ops.Color, color: ops.textcolor, height: AppSel.ToolbarSize }" 
		class="app_toolbar inherit-color" ng-if="AppSel.Navegacion == 'Superior'" 
		layout>
		<md-icon md-font-icon="{{ AppSel.Icono }} fa-fw s30"></md-icon>
		<div class="margin-right-20" layout=column layout-align=center>{{ AppSel.Titulo }}</div>
		<div flex layout class="app_pages" >
			<div ng-repeat="P in AppSel.pages" md-ink-ripple class="app_page" 
				ng-class="{ 'app_pagesel': P.id == PageSel.id }"
				ng-click="gotoPage(P.id)" layout=column layout-align=center>{{ P.Titulo }}</div>
		</div>

		@include('Core.UserMenu')
		<!--<img hide src="https://eventosadversos.comfamiliar.com/img/logo.png" height="{{ AppSel.ToolbarSize }}">-->
	</div>

	<div flex layout>
		
		<div layout=column ng-style="{ backgroundColor: ops.Color, color: ops.textcolor,  width: AppSel.ToolbarSize }" class="app_toolbar inherit-color"  ng-if="AppSel.Navegacion == 'Izquierda'">
			<div flex layout=column>
				<div class="h10"></div>
				<md-icon md-font-icon="{{ AppSel.Icono }} fa-lg fa-fw s30 margin-bottom-5"></md-icon>
				<div class="text-center">{{ AppSel.Titulo }}</div>
				<div class="h10"></div>
				<div flex layout=column class="app_pages" >
					<div ng-repeat="P in AppSel.pages" md-ink-ripple class="app_page" ng-show="AppSel.pages.length > 1"
						ng-class="{ 'app_pagesel': P.id == PageSel.id }"
						ng-click="gotoPage(P.id)">{{ P.Titulo }}</div>
				</div>
				@include('Core.UserMenu')
				<div class="h5"></div>
				<!--<img src="https://eventosadversos.comfamiliar.com/img/logo.png" width="90%" style="margin: 10px auto" >-->
			</div>
		</div>

		@include('Apps.App_View_Page')

	</div>

</div>

<style type="text/css">

	.app_toolbar{
		box-shadow: inset 0 -1px 0 #0000001c;
	}

	.app_pages{
		position: relative;
	}

	.app_page{
		padding: 0px 25px;
		opacity: 0.65;
		transition: all 0.5s;
		cursor: pointer; outline: none;
		position: relative;
	}

	.app_pagesel{
		opacity: 1;
		font-weight: 500;
    	box-shadow: inset 0 -3px 0 #ffffff5e;
	}

	.app iframe{
    	border: none;
    	width: 100%; height: 100%;
	}

	.app.app_text_black .app_pagesel{
		box-shadow: inset 0 -4px 0 #00000014;
	}

	.app md-dialog{ opacity: 1; border-radius: 0 !important; box-shadow: none !important; }
	.app .only-dialog{ display: none; }

	.app .button_main{
		border-radius: 3px;
		box-shadow: inset 0 0 0 1px #0000001c;
	}

	.app.app_nav_Izquierda .app_page{
		padding: 5px 7px;
	}

	.app.app_nav_Izquierda .app_pagesel{
		box-shadow: inset 3px 0 0 #ffffff5e;
	}

	.app.app_nav_Izquierda.app_text_black .app_pagesel{
		box-shadow: inset 4px 0 0 #00000014;
	}

	.app.app_nav_Izquierda .app_toolbar{
		box-shadow: inset -1px 0 0 #0000001c;
	}
</style>