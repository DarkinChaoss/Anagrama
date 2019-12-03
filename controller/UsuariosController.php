<?php
	class UsuariosController {
	
		public static function insert($dados){
			// verifica se login é exclusivo
			$arr = UsuariosController::getUsuariosGlobal("usu_login = '" . strtoupper(utf8_decode($dados['login'])) . "'");
			if(sizeof($arr) > 0){
				return "ERRO1";
			} else {
				$usuario = new UsuariosModel();
				$usuario->usu_leituraqr = (!isset($dados['leituraqr']) || $dados['leituraqr'] != "") ? $dados['leituraqr'] : 8;
				$usuario->usu_login = utf8_decode($dados['login']);
				$usuario->usu_senha = utf8_decode($dados['senha']);
				$usuario->usu_nivel = $dados['nivel'];
				$usuario->usu_referencia = $dados['referencia'];
				return $usuario->insert();
			}
		}
		
		public static function update($dados){
			// verifica se login é exclusivo
			$arr = UsuariosController::getUsuariosGlobal("usu_id != " . $dados['id'] . " AND usu_login = '" . strtoupper(utf8_decode($dados['login'])) . "'");
			if(sizeof($arr) > 0){
				return "ERRO1";
			} else {
				$usuario = new UsuariosModel();
				$usuario->usu_id = $dados['id'];
				$usuario->usu_leituraqr = $dados['leituraqr'];
				$usuario->usu_login = utf8_decode($dados['login']);
				$usuario->usu_senha = utf8_decode($dados['senha']);
				$usuario->usu_nivel = $dados['nivel'];
				$usuario->usu_referencia = $dados['referencia'];
				return $usuario->update();
			}
		}
		
		public static function delete($id){
			$usuario = new UsuariosModel();
			return $usuario->delete($id);
		}
		
		public static function getUsuario($id){
			$usuario = new UsuariosModel();
			return $usuario->selectUsuario($id);
		}
		
		public static function getUsuarios($where){
			$usuario = new UsuariosModel();
			return $usuario->selectAll($where);
		}
		
		public static function getUsuariosGlobal($where){
			$usuario = new UsuariosModel();
			return $usuario->selectAllGlobal($where);
		}
		
		public static function alterarSenha($dados){
			$usuario = new UsuariosModel();
			$usuario = UsuariosController::getUsuario($dados['id']);
			if($usuario->usu_senha == $dados['senhaAtual']) {
				$usuario->usu_senha = utf8_decode($dados['novaSenha']);
				return $usuario->update();
			} else {
				return false;
			}
		}
		
	}
?>