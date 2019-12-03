<?php
	class ProdutosCompostosController {
	
		public static function insert($dados){
			$prodcomp = new ProdutosCompostosModel();
			$prodcomp->pco_idpai = $dados['idpai'];
			$prodcomp->pco_idfilho = $dados['idfilho'];
			return $prodcomp->insert();
		}
		
		public static function update($dados){
			$prodcomp = new ProdutosCompostosModel();
			$prodcomp->pco_id = $dados['id'];
			$prodcomp->pco_idpai = $dados['idpai'];
			$prodcomp->pco_idfilho = $dados['idfilho'];
			return $prodcomp->update();
		}
		
		public static function delete($id){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->delete($id);
		}

		public static function getProdutosCompostos2($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectAll2($where);
		}
		
		public static function getProdutosCompostos($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectAll($where);
		}
		
		public static function getCountFilhos($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectCountFilhos($where);
		}
		
		public static function getProdutosCompostosInner($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectAllInner($where);
		}
		
		public static function getProdutosCompostosInnerCount($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectAllInnerCount($where);
		}
	
		public static function getProdutosCompostosInSolicitacao($where){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->selectCompostoInSolicitacao($where);
		}

		public static function isCompoundSon($qrcode){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->isCompoundSon($qrcode);
		}

		public static function getFatherId($son_id){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->getFatherId($son_id);
		}
		
		public static function getRepetition($son_id, $id_pai){
			$prodcomp = new ProdutosCompostosModel();
			return $prodcomp->getRepetition($son_id, $id_pai);
		}
	}
?>