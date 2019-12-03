<?php
	class AdministracaoController {
	
		public static function insert($dados){
			$administracao = new AdministracaoModel();
			$administracao->adm_id = $dados['id'];
			$administracao->adm_nome = utf8_decode($dados['nome']);
			return $administracao->insert();
		}
		
		public static function update($dados){
			$administracao = new AdministracaoModel();
			$administracao->adm_id = $dados['id'];
			$administracao->adm_nome = utf8_decode($dados['nome']);
			return $administracao->update();
		}
		
		public static function delete($id){
			$administracao = new AdministracaoModel();
			return $administracao->delete($id);
		}
		
		public static function getAdministracao($id){
			$administracao = new AdministracaoModel();
			return $administracao->selectAdministracao($id);
		}
		
		public static function getAdministracaos($where){
			$administracao = new AdministracaoModel();
			return $administracao->selectAll($where);
		}
		
	}
?>