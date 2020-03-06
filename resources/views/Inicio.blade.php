<div id="Inicio" flex layout=column ng-controller="InicioCtrl" class="padding overflow-y darkScroll">

	<!--<div layout class="margin-bottom-5 border-radius">
		
	</div>-->

	<h2 class="no-margin-top margin-bottom md-headline">{{ Saludo }}, {{ Usuario.Nombres | getword:1 }}</h2>

	<div layout layout-align="center center" class="h50">
		
		<div class="md-toolbar-searchbar bg-theme border-radius border h40 margin-bottom wu600" layout>
			<md-icon md-font-icon="fa-search" class="fa-fw s30 margin-5"></md-icon>
			<input flex type="search" placeholder="Buscar..." ng-model="filterApps" class="no-padding h40 lh40">
		</div>

	</div>

	<div class="md-subheader padding-bottom-5" ng-show="Usuario.Apps.length > 0">Mis Aplicaciones</div>
	<div layout layout-wrap class="margin-bottom-20">

		<a class="square-card w135 h120" layout=column
			ng-repeat="A in Usuario.Apps | filter:filterApps | orderBy:'Titulo' " 
			href="{{ Usuario.Url }}#/a/{{ A.Slug }}" target="_self"
			ng-style="{ backgroundColor: A.Color, color: A.textcolor }">
			<div class="h70" layout layout-align="center center">
				<md-icon class="fa-fw fa-2x text-inherit" md-font-icon="{{ A.Icono }}" ></md-icon>
			</div>
			<div class="square-card-text">{{ A.Titulo }}</div>
		</a>
			
	</div>


	<div class="md-subheader padding-bottom-5">Accesos Directos</div>
	<div layout layout-wrap class="margin-bottom-20">

		<a class="square-card bg-theme w135 h90" layout=column ng-repeat="S in Usuario.Secciones"
			href="{{ Usuario.Url }}#/Home/{{S.id}}" target="_self">
			<div class="h55" layout layout-align="center center">
				<md-icon class="fa-fw fa-2x text-inherit text-clear" md-font-icon="{{ S.Icono }}" ></md-icon>
			</div>
			<div class="square-card-text">{{ S.Seccion }}</div>
		</a>
			
	</div>

</div>

<style>
	
	.square-card{
		cursor: pointer; outline: none;
		text-decoration: none !important;
		box-shadow: inset 0 0 0 2px #00000021;
		border-radius: 8px;
		transform: scale(0.95);
		transition: all 0.3s;
	}

	.square-card:hover{
		transform: scale(1);
	}

	.square-card-text{
		font-size: 15px;
		text-align: center;
		padding: 5px;
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