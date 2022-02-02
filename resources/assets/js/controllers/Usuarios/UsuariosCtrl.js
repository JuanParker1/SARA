angular.module('UsuariosCtrl', [])
.controller('UsuariosCtrl', ['$scope', '$rootScope', '$http', '$injector', '$mdDialog', 
	function($scope, $rootScope, $http, $injector, $mdDialog) {

		console.info('UsuariosCtrl');
		var Ctrl = $scope;
		var Rs = $rootScope;
		Rs.mainTheme = 'Snow_White';

		Ctrl.Sections = {
			Usuarios: [ 'Usuarios' ],
			Perfiles: [ 'Perfiles' ],
			Retroalimentacion: [ 'Retroalimentación' ],
		};

		//Usuarios
		Ctrl.usuariosFilters = {
			estado: 'A',
			asignacion: '',
			asignacion_id: 1
		};

		Ctrl.UsuariosCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Usuario/usuarios',
			query_with: [ 'asignacion', 'asignacion.proceso', 'asignacion.perfil' ]
		});

		Ctrl.orderBy = 'Nombres';
		Ctrl.filterRows = '';
		Ctrl.usuariosFiltersSidenav = false;

		Ctrl.getUsuarios = () => {

			Ctrl.UsuariosCRUD.ops.query_call_arr[0] = ['filterAsignacionArr', [Ctrl.usuariosFilters.asignacion, Ctrl.usuariosFilters.asignacion_id]];

			return Ctrl.UsuariosCRUD
				.setScope('estado', Ctrl.usuariosFilters.estado)
				.get().then(() => {
				
				let Usuarios = angular.copy(Ctrl.UsuariosCRUD.rows);
				Ctrl.Usuarios = Usuarios;

			});
		};

		Ctrl.changeAvatar = (U) => {
			var hasAvatar = !(U.avatar == 'img/avatars/default.png');
			var Config = {
				Title: 'Cambiar Avatar',
				Class: '',
				CanvasWidth:  300,
				CanvasHeight: 300,
				CropWidth:  120,
				CropHeight: 120,
				MinWidth:  90,
				MinHeight: 90,
				KeepAspect: true,
				Preview: true,
				PreviewClass: 'md-img-round',
				RemoveOpt: hasAvatar ? 'Remover Avatar' : false,
				Daten: {
					savepath: `fs/${ Rs.Usuario.key }/avatars/${ U.id }.jpg`
				},
				OldImage: hasAvatar ? { url: U.avatar, width: 120, height: 120, class: 'md-img-round md-whiteframe-3dp' } : false
			};

			$mdDialog.show({
				controller: 'ImageEditor_DialogCtrl',
				templateUrl: 'templates/dialogs/image-editor.html',
				locals: { Config: Config },
				clickOutsideToClose: true,
				fullscreen: true
			}).then(function(resp) {
				U.updated_at = moment().format('Y-MM-DD HH:mm:ss');
				Ctrl.UsuariosCRUD.update(U).then(() => {
					Ctrl.getUsuarios();
					if(U.id == Rs.Usuario.id){
						Rs.http('api/Usuario/check-token', {}, Rs, 'Usuario');
					}
				});
			}, function(cancel){
			});
		};

		Ctrl.getUsuarioDiagFields = (U) => {
			return [
				{ Nombre: 'Nombres',     Value: U?.Nombres,     Required: true },
				{ Nombre: 'Email',       Value: U?.Email,       Required: true },
				{ Nombre: 'Documento',   Value: U?.Documento,   Required: false, flex: 50 },
				{ Nombre: 'Celular',     Value: U?.Celular,     Required: false, flex: 50 },
			];
		};

		Ctrl.addUsuario = () => {
			Rs.BasicDialog({
				Title: 'Crear Usuario',
				Fields: Ctrl.getUsuarioDiagFields({}),
				Confirm: { Text: 'Crear' },
			}).then(F => {
				if(!F) return;
				let Fields = Rs.prepFields(F.Fields);
				Ctrl.UsuariosCRUD.add(Fields).then(() => {
					Ctrl.getUsuarios();
				});
			});
		};

		Ctrl.editUsuario = (U) => {
			Rs.BasicDialog({
				Title: 'Editar Usuario',
				Fields: Ctrl.getUsuarioDiagFields(U),
				Confirm: { Text: 'Editar' },
				HasDelete: true,
				fullscreen: false
			}).then(F => {
				if(!F) return;
				if(F.HasDeleteConf){
					if(Ctrl.usuariosFilters.estado == 'A'){
						Ctrl.UsuariosCRUD.delete(U);
					}else{
						Rs.http('api/Usuario/restore', { id: U.id }).then(() => {
							Ctrl.getUsuarios();
						});
					}
				}else{
					let Fields = Rs.prepFields(F.Fields);
					let editedU = angular.extend(U, Fields);
					Ctrl.UsuariosCRUD.update(editedU).then(() => {
						Ctrl.getUsuarios();
					});
				}
			});
		};

		Ctrl.changePassword = (U) => {
			Rs.BasicDialog({
				Title: 'Cambiar Contraseña',
				Fields: [
					{ Nombre: 'Nueva Contraseña',  Value: '', Required: true }
				],
				Confirm: { Text: 'Cambiar' },
				fullscreen: false
			}).then(F => {
				if(!F) return;
				let new_password = F.Fields[0].Value.trim();
				if(new_password == '') return Rs.showToast('Contraseña requerida', 'Error');
				Rs.http('/api/Usuario/change-password', { usuario_id: U.id, new_password });
			});
		};

		//Perfiles
		Ctrl.PerfilesCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Usuario/perfiles-crud',
			order_by: ['Orden']
		});

		Ctrl.SeccionesCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Usuario/secciones',
			order_by: ['Orden']
		});

		Ctrl.getPerfiles = () => {
			return Ctrl.PerfilesCRUD.get().then(() => {
				Ctrl.openPerfil(Ctrl.PerfilesCRUD.rows[0]);
			});
		};

		Ctrl.getSecciones = () => {
			return Ctrl.SeccionesCRUD.get().then(() => {
				Ctrl.getPerfiles();
			});
		};

		Ctrl.NivelesAcceso = {
			0: ['Sin Acceso',    'fa-ban'],
			1: ['Solo Lectura',  'fa-eye'],
			2: ['Puede Agregar', 'fa-plus'],
			3: ['Puede Editar',  'fa-pencil-alt'],
			4: ['Puede Borrar',  'fa-eraser'],
			5: ['Control Total', 'fa-globe-americas']
		};

		Ctrl.addPerfil = () => {
			Rs.BasicDialog({
				Title: 'Agregar Perfil',
				fullscreen: false
			}).then(F => {
				if(!F) return;
				let new_perfil = F.Fields[0].Value.trim();
				Ctrl.PerfilesCRUD.add({
					Perfil: new_perfil,
					Perfil_Show: new_perfil,
					Orden: (Ctrl.PerfilesCRUD.rows.length + 1)
				});
			});
		};

		Ctrl.openPerfil = (P) => {
			Ctrl.PerfilSel = P;
			let secciones = angular.copy(Ctrl.SeccionesCRUD.rows);
			secciones.forEach(s => { s.Level = 0; });
			Ctrl.PerfilSel.perfil_secciones.forEach(ps => {
				let seccion = secciones.find(s => ps.seccion_id == s.id);
				seccion.Level = ps.Level;
			});
			Ctrl.PerfilSel.secciones = secciones;
		};

		Ctrl.savePerfil = async () => {
			await Rs.http('/api/Usuario/perfil-secciones', { perfil_id: Ctrl.PerfilSel.id, secciones: Ctrl.PerfilSel.secciones });
			await Ctrl.PerfilesCRUD.update(Ctrl.PerfilSel);
			Rs.showToast('Perfil Actualizado', 'Success');
		};


		//Feedback
		Ctrl.FeedbackCRUD = $injector.get('CRUD').config({ 
			base_url: '/api/Usuario/feedback',
			query_with: ['usuario'],
			order_by:   ['-created_at']
		});

		Ctrl.feedbackEstados = ['Pendiente', 'En Proceso', 'Terminada', 'Cancelada'];

		Ctrl.feedbackFilters = {
			estado: 'Pendiente'
		};

		Ctrl.getFeedback = () => {
			Ctrl.FeedbackCRUD.setScope('estado', Ctrl.feedbackFilters.estado)
							 .get();
		};


		//Init
		if(Rs.State.route.length == 3){
			Rs.navTo('Home.Section.Subsection', { subsection: 'Usuarios' });
		};

		Promise.all([
			Rs.http('api/Main/get-configuracion', {}, Ctrl, 'Configuracion'),
			Rs.getProcesos(Ctrl),
			Rs.getProcesosFS(Ctrl),
			Ctrl.getSecciones(),
			Ctrl.getFeedback(),
			Ctrl.getUsuarios()
		]);
		

	}
]);