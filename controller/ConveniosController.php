<?php
	class ConveniosController {
	
		public static function insert($dados){
			$convenio = new ConveniosModel();
			$convenio->cvn_id = $dados['id'];
			$convenio->cvn_nome = utf8_decode($dados['nome']);
			$convenio->cvn_obs = utf8_decode($dados['obs']);
			$convenio->cvn_ativo = utf8_decode($dados['ativo']);
			return $convenio->insert();
		}
		
		public static function update($dados){
			$convenio = new ConveniosModel();
			$convenio->cvn_id = $dados['id'];
			$convenio->cvn_nome = utf8_decode($dados['nome']);
			$convenio->cvn_obs = utf8_decode($dados['obs']);
			$convenio->cvn_ativo = utf8_decode($dados['ativo']);
			return $convenio->update();
		}
		
		public static function delete($id){
			$convenio = new ConveniosModel();
			return $convenio->delete($id);
		}
		
		public static function getConvenio($id){
			$convenio = new ConveniosModel();
			return $convenio->selectConvenio($id);
		}
		
		public static function getConvenios($where){
			$convenio = new ConveniosModel();
			return $convenio->selectAll($where);
		}
		
	}
?>