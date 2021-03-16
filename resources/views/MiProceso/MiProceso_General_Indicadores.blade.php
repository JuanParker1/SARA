<div class="text-clear  margin-5-0">Nuestros Indicadores</div>

<div layout layout-wrap>
	<div class="border-rounded padding-5-10 border bg-lightgrey-5 Pointer TableroButton" ng-repeat="T in ProcesoSel.tableros"
		ng-click="viewTableroDiag(T)" layout layout-align="center center">
		<div>{{ T.Titulo }}</div>
		<md-icon md-font-icon="fa-external-link-alt margin-left fa-fw"></md-icon>
	</div>
</div>

<md-button class="border-rounded h40" style="border: 1px solid #b7b7b7" ng-click="goToTab('Indicadores')" ng-show="ProcesoSel.indicadores.length > 0">
	<md-icon md-svg-icon="md-arrow-forward" class="s30"></md-icon> Ver nuestros {{ ProcesoSel.indicadores.length }} Indicadores
</md-button>

<div ng-show="ProcesoSel.indicadores.length == 0" class="text-clear">Sin Indicadores</div>

<div class="h30"></div>

<style type="text/css">
	.TableroButton{
		transform: scale(0.95);
		transition: all 0.3s;
		padding-left: 20px !important;
	}

	.TableroButton:hover{
		transform: scale(1);
		background-color: #164ea5 !important;
		color: white;
	}

	.TableroButton md-icon{ color: inherit !important; }
</style>