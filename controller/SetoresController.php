<?php
	class SetoresController {
	
		public static function insert($dados){
			$setor = new SetoresModel();
			$setor->set_nome = utf8_decode($dados['nome']);
			$setor->set_fazsolicitacao = $dados['fazsolicitacao'];
			return $setor->insert();
		}
		
		public static function update($dados){
			$setor = new SetoresModel();
			$setor->set_id = $dados['id'];
			$setor->set_nome = utf8_decode($dados['nome']);
			$setor->set_fazsolicitacao = $dados['fazsolicitacao'];
			return $setor->update();
		}
		
		public static function delete($id){
			$setor = new SetoresModel();
			return $setor->delete($id);
		}
		
		public static function getSetor($id){
			$setor = new SetoresModel();
			return $setor->selectSetor($id);
		}
		
		public static function getSetores($where){
			$setor = new SetoresModel();
			return $setor->selectAll($where);
		}

		public static function getNomeSetor($id){
			$setor = new SetoresModel();
			return $setor->getNomeSetor($id);
		}

		public static function getCmeId(){
			$setor = new SetoresModel();
			return $setor->getCmeId();
		}
		
		
	}
?>