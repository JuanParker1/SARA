<md-dialog class="no-overflow h100p" flex=100 aria-label=m layout=column>

	<div class="h30 padding-left" layout layout-align="center center">

		<div class="text-bold" flex>{{ Cargador.Titulo }}</div>

		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>

	</div>

	<div flex layout layout-align="center center" style="padding-bottom: 50px;" class="well" ngf-drop="upload($file)" 
		gf-drag-over-class="'dragover'" ngf-multiple="false" ng-show="Etapa == 'PreLoad'" 
		 accept="{{ ConfTipoArchivo[Cargador.Config.tipo_archivo][0] }}">

		<div class="upload_area" layout layout-align="center center" accept="{{ ConfTipoArchivo[Cargador.Config.tipo_archivo][0] }}" ngf-select="upload($file)">
			<md-icon md-font-icon="fa-arrow-up"></md-icon>
			<div class="upload_area_text">Cargar Archivo</div>
		</div>

	</div>

	<div ng-show="Etapa == 'Loading'" flex layout layout-align="center center">
		<md-progress-circular md-diameter="48"></md-progress-circular>
	</div>

	<div flex layout=column class="" ng-show="Etapa == 'TestLoad'">
		
		<md-table-container flex class="overflow darkScroll">
			<table md-table class="md-table-short table-col-compress">
				<thead md-head>
					<tr md-row>
						<th md-column>Fila</th>
						<th md-column ng-repeat="C in Entidad.campos | exclude:ExcludeTipos:'tipo_valor'">{{ C.campo_title }}</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="(kR,R) in load_data">
						<td md-cell class="">{{ pag_from + kR + 1 }}</td>
						<td md-cell ng-repeat="C in Entidad.campos | exclude:ExcludeTipos:'tipo_valor' ">{{ R[C.Indice] }}</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>

		<div layout class="bg-white border-top padding-left" layout-align="center center">

			<md-button ng-click="pag_go(-1)" class="md-icon-button no-padding no-margin s30" aria-label="b"><md-icon md-font-icon="fa-chevron-left"></md-icon></md-button>
			<div class="text-clear">{{ pag_from + 1 }} a {{ pag_to }} de {{ load_data_len }}</div>
			<md-button ng-click="pag_go(1)" class="md-icon-button no-padding no-margin s30" aria-label="b"><md-icon md-font-icon="fa-chevron-right"></md-icon></md-button>

			<span flex></span>
			<md-button class="md-raised" accept="{{ ConfTipoArchivo[Cargador.Config.tipo_archivo][0] }}" ngf-select="upload($file)">
				<md-icon md-font-icon="fa-undo"></md-icon>
				Cambiar Archivo
			</md-button>
			<md-button class="md-primary md-raised" ng-click="sendData()">
				Insertar {{ load_data_len }} Registros
			</md-button>
		</div>

	</div>



	<style type="text/css">
		.upload_area{
			position: relative;
			width: 180px; height: 180px;
			border: 5px dashed hsla(0, 0%, 0%, 0.5);
			border-radius: 50%;
			cursor: pointer;
			transition: all 0.3s cubic-bezier(0.43, 0.58, 0.45, 1.32);
			font-size: 80px;
		}

		.dragover .upload_area, .upload_area:hover{
			width: 220px; height: 220px;
			border: 5px solid hsla(0, 0%, 0%, 0.35);
			box-shadow: inset 0 0 0 140px rgba(0, 0, 0, 0.15);
			font-size: 130px;
		}

		.upload_area_text {
		    font-size: 20px;
		    position: absolute;
		    bottom: -40px;
		    color: #6b6b6b;
		}
	</style>

</md-dialog>