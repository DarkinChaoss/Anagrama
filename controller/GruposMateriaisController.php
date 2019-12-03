<?php
	class GruposMateriaisController {
	
		public static function insert($dados){
			$grupoMateriais = new GruposMateriaisModel();
			$grupoMateriais->gma_id = $dados['id'];
			$grupoMateriais->gma_nome = utf8_decode($dados['nome']);
			$grupoMateriais->gma_obs = utf8_decode($dados['obs']);
			return $grupoMateriais->insert();
		}
		
		public static function update($dados){
			$grupoMateriais = new GruposMateriaisModel();
			$grupoMateriais->gma_id = $dados['id'];
			$grupoMateriais->gma_nome = utf8_decode($dados['nome']);
			$grupoMateriais->gma_obs = utf8_decode($dados['obs']);
			return $grupoMateriais->update();
		}
		
		public static function delete($id){
			$grupoMateriais = new GruposMateriaisModel();
			return $grupoMateriais->delete($id);
		}
		
		public static function getGrupoMateriais($id){
			$grupoMateriais = new GruposMateriaisModel();
			return $grupoMateriais->selectMetodo($id);
		}
		
		public static function getGruposMateriais($where){
			$grupoMateriais = new GruposMateriaisModel();
			return $grupoMateriais->selectAll($where);
		}
		
	}
?>