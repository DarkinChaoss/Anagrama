<?php
	class MateriaisInternosController {
	
		public static function insert($dados){
			$materialInterno = new MateriaisInternosModel();
			$materialInterno->mai_cod = $dados['cod'];
			$materialInterno->mai_nome = utf8_decode($dados['nome']);
			$materialInterno->mai_qtde = (trim($dados['qtde']) == "") ? 0 : $dados['qtde'];
			return $materialInterno->insert();
		}
		
		public static function update($dados){
			$materialInterno = new MateriaisInternosModel();
			$materialInterno->mai_id = $dados['id'];
			$materialInterno->mai_cod = $dados['cod'];
			$materialInterno->mai_nome = utf8_decode($dados['nome']);
			$materialInterno->mai_qtde = (trim($dados['qtde']) == "") ? 0 : $dados['qtde'];
			return $materialInterno->update();
		}
		
		public static function delete($id){
			$materialInterno = new MateriaisInternosModel();
			return $materialInterno->delete($id);
		}
		
		public static function getMaterialInterno($id){
			$materialInterno = new MateriaisInternosModel();
			return $materialInterno->selectMaterialInterno($id);
		}
		
		public static function getMateriaisInternosBuscar($buscar){
			$materialInterno = new MateriaisInternosModel();
			return $materialInterno->search($buscar);
		}
		
		public static function getMateriaisInternos($where){
			$materialInterno = new MateriaisInternosModel();
			return $materialInterno->selectAll($where);
		}
		
	}
?>