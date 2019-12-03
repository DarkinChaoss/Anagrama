<?php
	class EtiquetaQtdController {
	
		public static function insert($qtd, $lote, $dados, $nome_produto){

			$etiquetaqtd = new EtiquetaQtdModel();
			$etiquetaqtd->eqm_qtd = $qtd;
			$etiquetaqtd->eqm_lote = $lote;
			$etiquetaqtd->eqm_dados = $dados;
			$etiquetaqtd->eqm_nome_produto = $nome_produto;
			return $etiquetaqtd->insert();
		}

		public static function getLastEtiquetas() {
			$etiquetas = new EtiquetaQtdModel();
			$data = $etiquetas->getLastEtiquetas();

			return $data;
		}

		public static function getLastEtiquetas48Hours() {
			$etiquetas = new EtiquetaQtdModel();
			$data = $etiquetas->getLastEtiquetas48Hours();

			return $data;
		}


		public static function getEspecificEtiquetas($id) {
			$etiquetas = new EtiquetaQtdModel();
			$data = $etiquetas->getEspecificEtiquetas($id);

			return $data;
		}
		
		
		// public static function deleteOldRegisters(){
		// 	$etiquetaQtd = new EtiquetaQtdModel();
		// 	return $etiquetaQtd->deleteOldRegisters();
		// }
		
	}
?>