<?php
	class EtiquetadoresController {
	
		public static function insert($dados){
			$etiquetador = new EtiquetadoresModel();
			$etiquetador->eti_id = $dados['id'];
			$etiquetador->eti_nome = utf8_decode($dados['nome']);
			$etiquetador->eti_contato = $dados['contato'];
			return $etiquetador->insert();
		}
		
		public static function update($dados){
			$etiquetador = new EtiquetadoresModel();
			$etiquetador->eti_id = $dados['id'];
			$etiquetador->eti_nome = utf8_decode($dados['nome']);
			$etiquetador->eti_contato = $dados['contato'];
			return $etiquetador->update();
		}
		
		public static function delete($id){
			$etiquetador = new EtiquetadoresModel();
			return $etiquetador->delete($id);
		}
		
		public static function getEtiquetador($id){
			$etiquetador = new EtiquetadoresModel();
			return $etiquetador->selectEtiquetador($id);
		}
		
		public static function getEtiquetadores($where){
			$etiquetador = new EtiquetadoresModel();
			return $etiquetador->selectAll($where);
		}
		
	}
?>