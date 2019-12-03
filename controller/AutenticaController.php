<?php
	class AutenticaController {
	
		public static function logado(){
			@session_start();
			if(isset($_SESSION['usu_id']))
				return true;
			else
				return false;
		}
		
		public static function login($login, $senha){
			$usuario = new UsuariosModel();
			$usuario = $usuario->validaUsuario($login, $senha);
			if(!empty($usuario->usu_id)){
				@session_start();
				$_SESSION['usu_id'] = $usuario->usu_id;
				$_SESSION['usu_masterclient'] = $usuario->usu_masterclient;
				$_SESSION['usu_leituraqr'] = $usuario->usu_leituraqr;
				$_SESSION['usu_login'] = $usuario->usu_login;
				$_SESSION['usu_nivel'] = $usuario->usu_nivel;
				$_SESSION['usu_referencia'] = $usuario->usu_referencia;
				$masterClient = ClientesController::getMasterClient($usuario->usu_masterclient);
				$_SESSION['usu_cli_logo'] = $masterClient->cli_logo;
				$_SESSION['usu_cli_nome'] = $masterClient->cli_nome;
				return true;
			} else {
				return false;
			}
		}
		
		public static function permission($login, $senha){
			$usuario = new UsuariosModel();
			$usuario = $usuario->validaUsuario($login, $senha);
			return $usuario;
		}
		
		public static function logout(){
			$usuario = new UsuariosModel();
			$usuario = $usuario->logout();
			@session_start();
			unset($_SESSION['usu_id']);
			unset($_SESSION['usu_masterclient']);
			unset($_SESSION['usu_leituraqr']);
			unset($_SESSION['usu_login']);
			unset($_SESSION['usu_nivel']);
			unset($_SESSION['usu_referencia']);
			unset($_SESSION['usu_cli_logo']);
			unset($_SESSION['usu_cli_nome']);
			return true;
		}
		
	}
?>