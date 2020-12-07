<div flex layout=column class="overflow-y hasScroll padding-0-10" ng-if="Modo == 'Mes'">
	
	<div layout layout-wrap>
		<scorecard-nodo nodo="Sco.nodo" periodo="Periodo" flex=100></scorecard-nodo>
	</div>

	<div class="h50"></div>

</div>

<style type="text/css">
	.card_title{ font-size: 0.9em; opacity: 0.3;  }
	.card_value{ font-size: 2.3em; text-shadow: 2px 2px 4px #0000008f; font-weight: 400; }
	.card_subtext{ text-align: right; font-size: 0.9em; opacity: 0; transition: all 0.3s; }
	.card:hover .card_subtext{ opacity: 0.8;  }
	.scorecard{
		/*opacity: 0;*/
		padding: 2px 4px;
	    animation: 400ms cubic-bezier(0.18, 1, 0.63, 1.21) 100ms 1 normal both running In_FadeScale; transition: all 0.3s;
	    border: 1px solid #ffffff08;
	    background-color: #313131 !important;
	}
	.card:hover .scorecard{
		background-color: #484848 !important;
	}
</style>