<?php
	class ArsenaisController {
	
		public static function insert($dados){
			$arsenal = new ArsenalModel();
			$arsenal->ars_id = $dados['id'];
			$arsenal->ars_nome = utf8_decode($dados['nome']);
			$arsenal->ars_contato = $dados['contato'];
			return $arsenal->insert();
		}
		
		public static function update($dados){
			$arsenal = new ArsenalModel();
			$arsenal->ars_id = $dados['id'];
			$arsenal->ars_nome = utf8_decode($dados['nome']);
			$arsenal->ars_contato = $dados['contato'];
			return $arsenal->update();
		}
		
		public static function delete($id){
			$arsenal = new ArsenalModel();
			return $arsenal->delete($id);
		}
		
		public static function getArsenal($id){
			$arsenal = new ArsenalModel();
			return $arsenal->selectArsenal($id);
		}
		
		public static function getArsenais($where){
			$arsenal = new ArsenalModel();
			return $arsenal->selectAll($where);
		}
		
	}