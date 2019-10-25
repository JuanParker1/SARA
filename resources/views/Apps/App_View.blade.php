<div flex ng-controller="App_ViewCtrl" layout=column class="app" ng-class="ops.general_class">
	
	<div layout=column ng-style="{ backgroundColor: AppSel.Color, color: AppSel.textcolor }" class="app_toolbar inherit-color">
		<div layout layout-wrap>
			<md-icon md-font-icon="{{ AppSel.Icono }} fa-fw fa-lg s40 margin-left-5"></md-icon>
			<div class="h40 lh40 margin-right-20">{{ AppSel.Titulo }}</div>
			<div flex layout class="app_pages">
				<div ng-repeat="P in AppSel.pages" md-ink-ripple class="app_page" 
					ng-class="{ 'app_pagesel': P.id == PageSel.id }"
					ng-click="openPage(P)">{{ P.Titulo }}</div>
			</div>
		</div>
	</div>

	<div flex layout>
		
		<div flex ng-if="PageSel.Tipo == 'ExternalUrl'">
			<iframe ng-src="{{ getIframeUrl(PageSel.Config.url) }}"></iframe>			
		</div>

		<div flex ng-if="PageSel.Tipo == 'Scorecard'" class="bg-black-2" md-theme="Black">
			@include('Scorecards.ScorecardDiag_index')
		</div>

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
		padding: 10px 25px;
		opacity: 0.65;
		transition: all 0.5s;
		cursor: pointer; outline: none;
		position: relative;
	}

	.app_pagesel{
		opacity: 1;
		font-weight: 500;
    	box-shadow: inset 0 -4px 0 #ffffff5e;
	}

	iframe{
    	border: none;
    	width: 100%; height: 100%;
	}

	.app.app_text_black .app_pagesel{
		box-shadow: inset 0 -4px 0 #00000014;
	}
</style>