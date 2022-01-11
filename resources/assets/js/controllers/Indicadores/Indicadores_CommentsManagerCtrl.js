angular.module('Indicadores_CommentsManagerCtrl', [])
.controller('Indicadores_CommentsManagerCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', '$filter', 'Indicadores', 'Indicador', 'UsuariosCRUD',
	function($scope, $rootScope, $http, $injector, $mdDialog, $filter, Indicadores, Indicador, UsuariosCRUD) {

		console.info('Indicadores_CommentsManagerCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;

		Ctrl.Cancel = $mdDialog.cancel;
		Ctrl.Indicadores = Indicadores;

		Ctrl.getUsuarios = async () => { return await UsuariosCRUD.get() }

		var last_user_id = Rs.Usuario.id;

		Ctrl.CommentsCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Main/comentarios',
			query_with: [ 'autor' ],
			query_scopes:[
				[ 'tipoentidad', 'Indicador' ],
				[ 'grupo', 'Comentario' ]
			],
			order_by: [ '-Op1', 'created_at' ]
		});

		Ctrl.filters = {
			Indicador: angular.copy(Indicador),
			desde: moment().add(-36, 'months').toDate(),
			hasta: moment().toDate()
		};

		Ctrl.getComments = () => {

			let desde = parseInt(moment(Ctrl.filters.desde).format('YYYYMM'));
			let hasta = parseInt(moment(Ctrl.filters.hasta).format('YYYYMM'));

			Ctrl.CommentsCRUD
				.setScope('periododesde', desde)
				.setScope('periodohasta', hasta)
				.setScope('entidad', ['Indicador', Ctrl.filters.Indicador.id])
				.get().then(() => {
				
				if(Ctrl.CommentsCRUD.rows.length > 0){
					last_user_id = Ctrl.CommentsCRUD.rows[0].usuario_id;
				}else{
					last_user_id = Rs.Usuario.id;
				}

			});
		};

		Ctrl.searchUsuarios = (searchText) => {
			console.log('searchig');
			return $filter('filter')(UsuariosCRUD.rows, searchText);
		}

		Ctrl.showUsuarios = (U) => {
			return U.Nombres;
		}

		Ctrl.addComment = async () => {

			let Periodo = parseInt(moment().add(-1, 'months').format('YYYYMM'));
			if(UsuariosCRUD.rows.length == 0) await Ctrl.getUsuarios();

			let Usuario = UsuariosCRUD.rows.find(u => u.id == last_user_id );

			Rs.BasicDialog({
				Title: 'Agregar Comentario',
				Fields: [
					{ Nombre: 'Periodo',     Value: Periodo,       Required: true, Type: 'number' },
					{ Nombre: 'Usuario',     Value: Usuario,       Required: true, Type: 'autocomplete', opts: { searchText: Usuario.Nombres, itemsFn: Ctrl.searchUsuarios, itemDisplay: Ctrl.showUsuarios, selectItem: (F, i) => { F.Value = i.id }, itemText: 'Nombres', delay: 400, minLength: 3 } },
					//{ Nombre: 'Usuario',     Value: last_user_id,  Required: true, Type: 'list', List: UsuariosCRUD.rows, Item_Val: 'id', Item_Show: 'Nombres' },
					{ Nombre: 'Comentario',  Value: '',            Required: true, Type: 'textarea' }
				],
				Confirm: { Text: 'Agregar' }
			}).then(r => {
				if(!r) return;
				
				let F = Rs.prepFields(r.Fields);

				Ctrl.CommentsCRUD.add({
					Op1: F.Periodo,
					Comentario: F.Comentario,
					Entidad: 'Indicador',
					Entidad_id: Ctrl.filters.Indicador.id,
					Grupo: 'Comentario',
					usuario_id: F.Usuario.id
				}).then(() => {
					Ctrl.getComments();
				});
			});
		}

		Ctrl.editComment = async (C) => {

			if(UsuariosCRUD.rows.length == 0) await Ctrl.getUsuarios();

			let Usuario = UsuariosCRUD.rows.find(u => u.id == last_user_id );
			
			Rs.BasicDialog({
				Title: 'Editar Comentario',
				Fields: [
					{ Nombre: 'Periodo',     Value: C.Op1, Required: true, Type: 'number' },
					{ Nombre: 'Usuario',     Value: Usuario,       Required: true, Type: 'autocomplete', opts: { searchText: Usuario.Nombres, itemsFn: Ctrl.searchUsuarios, itemDisplay: Ctrl.showUsuarios, selectItem: (F, i) => { F.Value = i.id }, itemText: 'Nombres', delay: 400, minLength: 3 } },
					//{ Nombre: 'Usuario',     Value: C.usuario_id,  Required: true, Type: 'list', List: UsuariosCRUD.rows, Item_Val: 'id', Item_Show: 'Nombres' },
					{ Nombre: 'Comentario',  Value: C.Comentario, Required: true, Type: 'textarea' }
				],
				Confirm: { Text: 'Guardar' },
				HasDelete: true,
			}).then(r => {
				if(!r) return;
				if(r.HasDeleteConf){
					
					Rs.confirmDelete({
						Title: '¿Eliminar el Comentario?',
						Detail: 'Esta acción no se puede deshacer',
					}).then(del => {
						if(!del) return;
						Ctrl.CommentsCRUD.delete(C);
					});

				}else{
					let F = Rs.prepFields(r.Fields);
					C.Op1 = F.Periodo;
					C.Comentario = F.Comentario;

					Ctrl.CommentsCRUD.update(C).then(() => {
						Ctrl.getComments();
						Rs.showToast('Comentario Actualizado', 'Success');
					});
				}

				
			});
		}

		Ctrl.getComments();

	}
]);