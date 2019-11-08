<md-dialog class="no-overflow h100p" flex=100 aria-label=m>

	<div class="h30 padding-0-10" layout layout-align="center center">

		<div class="md-title" flex>{{ Cargador.Titulo }}</div>

		<div class="">{{ load_data.length }} Registros</div>


		<md-button class="md-icon-button s30 no-padding only-dialog no-margin" aria-label="Button" ng-click="Cancel()">
			<md-icon md-svg-icon="md-close" class=""></md-icon>
		</md-button>

	</div>

	<div flex layout layout-align="center center" style="padding-bottom: 50px;" class="well" ngf-drop="upload($file)" 
		gf-drag-over-class="'dragover'" ngf-multiple="false" ng-show="Etapa == 'PreLoad'">

		<div class="upload_area" layout layout-align="center center" ngf-select="upload($file)">
			<md-icon md-font-icon="fa-arrow-up"></md-icon>
			<div class="upload_area_text">Cargar Archivo</div>
		</div>

	</div>



	<div flex layout class="overflow-y">
		
		<md-table-container class="">
			<table md-table class="md-table-short table-col-compress">
				<thead md-head>
					<tr md-row>
						<th md-column ng-repeat="C in Entidad.campos" >{{ C.campo_title }}</th>
					</tr>
				</thead>
				<tbody md-body>
					<tr md-row class="" ng-repeat="Row in load_data">
						<td md-cell ng-repeat="C in Row track by $index">{{ C }}</td>
					</tr>
				</tbody>
			</table>
		</md-table-container>


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