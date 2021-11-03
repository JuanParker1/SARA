<div class="w40" style="padding-top: 1px; border-right: 1px solid #3c3c3c;">
	<div ng-repeat="S in SidenavIcons" layout layout-align="center center" 
		class="s40 Pointer relative" ng-class="{ 'text-clear': sidenavSel != S[1] }"
		ng-click="openSidenavElm(S)">
		<md-icon md-font-icon="{{ S[0] }} fa-fw text-18px"></md-icon>
		<md-tooltip md-direction="right" md-delay="500">{{ S[1] }}</md-tooltip>
		<div class="icon_indicator" ng-show="S[2]"></div>
	</div>
</div>

<md-sidenav md-is-locked-open="$mdMedia('gt-sm') && sidenavSel" md-is-open="sidenavSel"  
	class="bg-black-3 text-white mxw1000 hasScroll {{ activeSidenav()[3] }}" layout=column >

		
	@include('Indicadores.IndicadorDiag_Sidenav_Mejora')
	@include('Indicadores.IndicadorDiag_Sidenav_Desagregar')
	@include('Indicadores.IndicadorDiag_Sidenav_Ficha')

	<!-- include('Indicadores.IndicadorDiag_Sidenav_IA') -->

</md-sidenav>

<style type="text/css">
	.comment{
		background: #303030;
		font-size: 15px;
		margin: -5px 0 12px;
		padding: 9px 10px;
		border-radius: 4px;
	}

	.comment.mine{
	    background: #173d46;
	}

	.comment .comment_pill{
		background: #5d5d5d;
		border-radius: 25px;
		padding: 0px 10px;
	}
</style>