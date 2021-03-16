<div layout=column>
	<div class="text-clear  margin-5-0">Datos Técnicos</div>
	<md-table-container class="border-left border-right border-bottom border-radius">
		<table md-table class="md-table-short table-col-compress">
			<tbody md-body>
				<tr md-row class=""><td md-cell class="md-cell-compress text-bold">Id:  </td><td md-cell>{{ ProcesoSel.id   }}</td></tr>
				<tr md-row class=""><td md-cell class="md-cell-compress text-bold">Ruta:</td><td md-cell>{{ ProcesoSel.Ruta }}</td></tr>
				<tr md-row class=""><td md-cell class="md-cell-compress text-bold">CDC: </td><td md-cell>{{ ProcesoSel.CDC  }}</td></tr>
				<tr md-row class=""><td md-cell class="md-cell-compress text-bold">Ops:</td><td md-cell>{{ ProcesoSel.Op1  }} {{ ProcesoSel.Op2  }} {{ ProcesoSel.Op3  }}</td></tr>
			</tbody>
		</table>
	</md-table-container>
</div>

<div class="h50"></div>