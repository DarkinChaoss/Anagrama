<?php
	class ConferentesController {
	
		public static function insert($dados){
			$conferente = new ConferentesModel();
			$conferente->cnf_id = $dados['id'];
			$conferente->cnf_nome = utf8_decode($dados['nome']);
			$conferente->cnf_contato = $dados['contato'];
			return $conferente->insert();
		}
		
		public static function update($dados){
			$conferente = new ConferentesModel();
			$conferente->cnf_id = $dados['id'];
			$conferente->cnf_nome = utf8_decode($dados['nome']);
			$conferente->cnf_contato = $dados['contato'];
			return $conferente->update();
		}
		
		public static function delete($id){
			$conferente = new ConferentesModel();
			return $conferente->delete($id);
		}
		
		public static function getConferente($id){
			$conferente = new ConferentesModel();
			return $conferente->selectConferente($id);
		}
		
		public static function getConferentes($where){
			$conferente = new ConferentesModel();
			return $conferente->selectAll($where);
		}
		
	}
?>