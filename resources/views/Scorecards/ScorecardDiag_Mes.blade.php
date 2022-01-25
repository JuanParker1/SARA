<div flex layout=column class="overflow-y hasScroll padding-0-10" ng-show="Modo == 'Mes'">
	
	<div class="h10"></div>

	<div layout layout-wrap>
		
		<div ng-repeat="N in Sco.nodos_flat_show" ng-class="{ 'nodo_container': N.tipo == 'Nodo', 'card_container': N.tipo !== 'Nodo' }"
			ng-click="openFlatLevel(N, $event)">
			
			<div class="node" ng-show="N.tipo == 'Nodo'" layout>
				<div ng-style="{ width: 10 * N.depth }"></div>
				<md-icon md-font-icon="fa-chevron-right fa-fw s20 transition margin-right-5" 
					 ng-class="{'fa-rotate-90':N.open}"></md-icon>
				<div class="margin-right">{{ N.Nodo }}</div>
				<div ng-style="{ color: N.calc[Periodo].color }">{{ N.calc[Periodo].val }}</div>
				<span flex></span>
			</div>

			<div class="card" ng-show="N.tipo !== 'Nodo'" layout=column>
				<div class="card_title" layout layout-align="start start">
					<div flex>{{ N.Nodo }}</div>
				</div>
				<div class="card_content" layout layout-align="center center">
					<div class="card_value" style="color: {{ N.valores[Periodo].color }}">
						<md-icon ng-if="N.valores[Periodo].comentarios_total > 0" md-font-icon="fa-circle fa-fw text-7pt s10 text-clear"></md-icon>
						<span ng-show="filters.see == 'Res'">{{ N.valores[Periodo].val }}</span>
						<span ng-show="filters.see == 'Cump'">{{ N.valores[Periodo].cump_porc | percentage:1 }}</span>
					</div>
				</div>
				<span flex></span>
				<div class="" layout>
					<div flex class="card_process">{{ N.elemento.proceso.Proceso }}</div>
					<div class="card_subtext">
						{{ N.valores[Periodo].meta_val }}
						<md-icon class="s15" md-font-icon="{{ Sentidos[N.elemento.Sentido].icon}} fa-fw" style="transform: translateY(-2px);"></md-icon>
						<md-tooltip md-direction=left>Meta: {{ N.valores[Periodo].meta_val }} {{ Sentidos[N.elemento.Sentido].desc }}</md-tooltip>
					</div>
				</div>
			</div>

		</div>

	</div>

	<div class="h50"></div>

</div>

<style type="text/css">

	.nodo_container{
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 100%;
	    flex: 1 1 100%;
	    max-width: 100%;
	    box-sizing: border-box;
	    cursor: pointer;
	    padding: 3px 2px;
	}

	.card_container{
		width: 260px;
	}

	.card{
		height: 130px;
		padding: 4px 5px 3px 6px;
	    animation: 400ms cubic-bezier(0.18, 1, 0.63, 1.21) 100ms 1 normal both running In_FadeScale; transition: all 0.3s;
	    border: 1px solid #ffffff08;
	    border-radius: 10px;
	    background-color: #252525 !important;
	    cursor:  pointer;
	    transform: scale(0.97) !important;
	}

	.card:hover{ background-color: #292929 !important; transform: scale(1) !important; }
	.card_title{   font-size: 0.9em; opacity: 0.3;  }
	.card_process{ font-size: 0.9em; opacity: 0.3;  }
	.card_value{ font-size: 2.3em; text-shadow: 2px 2px 4px #0000008f; font-weight: 400; }
	.card_subtext{ text-align: right; opacity: 0.3; transition: all 0.3s; }
	.card:hover .card_subtext{ opacity: 0.8;  }


	.card_content{
	    position: absolute;
	    top: 12px;
	    bottom: 0;
	    left: 0;
	    right: 0;
	}

</style>