<div id="Inicio" flex layout ng-controller="InicioCtrl">

	<div id="Inicio_Overlay" flex layout>

		<div flex layout=column   class="padding overflow-y hasScroll" >

			<div layout layout-align="center center" class="margin-bottom">
				<h2 flex class="no-margin-top no-margin-bottom md-headline text-300">{{ Saludo }}, {{ Usuario.Nombres | getword:1 }}</h2>
				<md-button class="no-margin border-rounded bg-theme mh30 h30 lh30 md-whiteframe-3dp text-15px" ng-click="Storage.InicioSidenav = !Storage.InicioSidenav" >
					<md-icon md-font-icon="fa-history margin-right-5" style="transform: translateY(0px);"></md-icon>
					<span hide-xs>Recientes</span>
				</md-button>
			</div>

			@include('Inicio_Search')

			<div layout=column class="transition" ng-show="!searchMode">

				<div layout=column ng-show="Usuario.Apps.length > 0">
					
					<div class="md-subheader padding-5-0">Aplicaciones y Reportes</div>

					<div class="md-subheader padding-5-0" layout layout-align="center center"
						ng-show="cantFavorites > 0">
						<div>⭐ Favoritas</div>
						<span flex></span>
					</div>

					<div layout layout-wrap class="">

						<div class="rect-card w180 padding-5" layout layout-align="center center"
							ng-repeat="A in Usuario.Apps | filter:{ favorito:true }:true | orderBy:'Titulo' " 
							ng-click="openUrl('#/a/'+A.Slug)" 
							ng-style="{ backgroundColor: A.Color, color: A.textcolor }">
							<md-icon class="fa-fw text-25px text-inherit margin" md-font-icon="{{ A.Icono }}" ></md-icon>
							<div class="rect-card-text padding-right" flex>{{ A.Titulo }}</div>
							<md-button class="md-icon-button no-margin no-padding s30 abs focus-on-hover" style="top: 0; right: 0" 
								ng-click="makeFavorite(A, false)">
								<md-icon md-font-icon="fa-fw fa-star s20 text-inherit"></md-icon>
								<md-tooltip>Remover de Favoritas</md-tooltip>
							</md-button>
						</div>
							
					</div>

					<div class="md-subheader padding-5-0 Pointer" layout layout-align="center center"
						ng-click="showOtherApps = !showOtherApps"
						ng-show="cantFavorites > 0">
						<md-button class="md-icon-button no-margin no-padding s20">
							<md-icon md-font-icon="fa-fw fa-chevron-right s20 transition"
								ng-class="{ 'fa-rotate-90': showOtherApps }"></md-icon>
						</md-button>
						<div flex style="padding-left: 2px;">Otras</div>
					</div>

					<div layout layout-wrap ng-show="showOtherApps">

						<div class="rect-card w180 padding-5 show-child-on-hover" layout layout-align="center center"
							ng-repeat="A in Usuario.Apps | filter:{ favorito:false }:true | orderBy:'Titulo' " 
							ng-click="openUrl('#/a/'+A.Slug)" 
							ng-style="{ backgroundColor: A.Color, color: A.textcolor }">
							<md-icon class="fa-fw text-25px text-inherit margin" md-font-icon="{{ A.Icono }}" ></md-icon>
							<div class="rect-card-text padding-right" flex>{{ A.Titulo }}</div>
							<md-button class="md-icon-button no-margin no-padding s30 abs child" style="top: 0; right: 0" 
								ng-click="makeFavorite(A, true)">
								<md-icon md-font-icon="fa-fw far fa-star s30 text-inherit"></md-icon>
								<md-tooltip>Agregar a Favoritas</md-tooltip>
							</md-button>
						</div>
							
					</div>

					<div class="h20"></div>

				</div>

				


				<div class="md-subheader padding-bottom-5">Accesos Directos</div>
				<div layout layout-wrap class="margin-bottom-20">

					<a class="square-card bg-theme w135 h90" layout=column ng-repeat="S in Usuario.Secciones"
						href="{{ Usuario.Url }}#/Home/{{S.id}}" target="_self">
						<div class="h55" layout layout-align="center center">
							<md-icon class="fa-fw text-30px text-inherit text-clear" md-font-icon="{{ S.Icono }}" 
								style="transform: translateY(5px)"></md-icon>
						</div>
						<div class="square-card-text">{{ S.Seccion }}</div>
					</a>
						
				</div>

				<!--
				<div class="md-subheader padding-bottom-5">Mi Proceso</div>
				include('MiProceso.MiProceso')
				-->

			</div>

			
			


		</div>

		<md-sidenav id="InicioSidenav" layout=column class="w300 text-white padding-but-right  md-sidenav-right no-overflow" 
			md-is-locked-open="$mdMedia('(min-width: 750px)') && Storage.InicioSidenav"
			md-is-open="Storage.InicioSidenav">

			<div flex layout=column class="w290 overflow-y hasScroll relative">
				<label class="text-clear margin-bottom-5 text-14px">Recientes</label>
				<div ng-repeat="R in Recientes" md-truncates class="margin-bottom text-14px focus-on-hover " 
					layout >
					<div flex ng-click="openUrl('#/a/'+R.app.Slug+'/'+R.page.id, '_blank', true)" 
						layout layout-align="center center" class="Pointer" >
						<md-icon md-font-icon="{{ R.app.Icono }} fa-fw margin-right-5 s30 border-rounded text-16px"></md-icon>
						<div layout=column flex>
							<div class="">{{ R.app.Titulo }}</div>
							<div class="text-clear text-13px">{{ R.page.Titulo }}</div>
						</div>	
					</div>
					<md-button class="md-icon-button no-margin no-padding s30 focus-on-hover" 
						ng-click="makeFavorite(R.app, !R.app.favorito)">
						<md-icon md-font-icon="fa-fw fa-star s20" ng-class="{ 'far': !R.app.favorito }"></md-icon>
					</md-button>
				</div>

				<div class="h30"></div>

				<md-button class="md-icon-button no-margin fixed focus-on-hover" ng-show="Storage.InicioSidenav"  
					style="top: -2px; right: -5px;" ng-click="Storage.InicioSidenav = false">
					<md-icon md-svg-icon="md-close"></md-icon>
				</md-button>

			</div>

		</md-sidenav>

	</div>

</div>

<style>
	
	#Inicio{
		background-image: url(img/bg_data1.jpg);
		background-size: cover;
		background-position: center center;

	}

	#Inicio_Overlay{
		background: rgba(0, 0, 0, 0.4);
		opacity: 0;
		animation: In_Fade 500ms forwards cubic-bezier(0.34, 0.79, 0.68, 0.96) 500ms;
	}

	#InicioSidenav{
		background: rgba(0, 0, 0, 0.3);
    	backdrop-filter: blur(15px);
    	top: 41px;
    	min-width: 50px !important;
    	transition: all 0.3s;
	}

	@media (max-width: 750px){
		#InicioSidenav{
			background: rgb(23 23 23);
		}
	}

	@media (max-width: 440px){
		.rect-card{
			width: 100% !important;
			transform: scale(1) !important;
		}
	}

	#searchField{
		margin-left: -45px;
    	padding-left: 45px !important;
	}

	.square-card{
		cursor: pointer;
		outline: none;
		text-decoration: none !important;
		box-shadow: inset 0 0 0 1px #000000a1, 0 12px 10px -8px rgba(0, 0, 0, 0.4);
		transform: scale(0.95);
		transition: all 0.3s;
		border-radius: 8px;
		margin-bottom: 7px;
	}

	.square-card:hover{
		transform: scale(1);
	}

	.square-card-text{
		font-size: 16px;
		text-align: center;
		padding: 5px;
	}

	.rect-card{
		cursor: pointer;
		outline: none;
		text-decoration: none !important;
		/*box-shadow: inset 0 0 0 1px #000000a1, 0 12px 10px -8px rgba(0, 0, 0, 0.4);*/
		transform: scale(0.95);
		transition: all 0.3s;
		border-radius: 8px;
		margin-bottom: 7px;
	}

	.rect-card:hover{
		transform: scale(1);
	}

	.search-res{
		margin-bottom: 1px;
	}

	.search-res span.highlight {
    	background: #00adff7a;
	}

	.search-res-pill{
		background: #3e3e3e;
	    border-radius: 30px;
	    padding: 2px 14px;
	}

	.search-group{
		padding: 0px 14px 7px;
		opacity: 0.7;
		font-size: 14px;
	}

	.search-group-selected{
		opacity: 1;
		box-shadow: inset 0 -3px #72acffb8;
	}

</style>

<!--<a class="Pointer mh40 no-underline border border-radius bg-white no-overflow margin-right-5 margin-bottom-5" 
	layout layout-align="center center"
	ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
	href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_blank" hide>
	<div ng-style="{ backgroundColor: A.Color }" class="s40" layout layout-align="center center">
		<md-icon class="fa-fw fa-lg" md-font-icon="{{ A.Icono }}" ng-style="{ color: A.textcolor }"></md-icon>
	</div>
	<div flex class="text-14px margin-0-10 text-black">{{ A.Titulo }}</div>
</a>-->