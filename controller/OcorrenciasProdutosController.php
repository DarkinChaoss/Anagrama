<?php
	class OcorrenciasProdutosController {
	
		public static function insert($dados){
			// marca o produto como descartado, dependendo da ocorrência
			$oco = new OcorrenciasModel();
			$oco = OcorrenciasController::getOcorrencia($_POST['idocorrencia']);
			if($oco->oco_descarte == 'S') {
				$prod = new ProdutosModel();
				$prod = ProdutosController::getProduto($_POST['idproduto']);
				$prod->pro_descarte = '*';
				$prod->update();
			}
			// se ocorrência for de "perdido", marca produto como perdido
			if($oco->oco_efeitoespecial == 'P') {
				$prod = new ProdutosModel();
				$prod = ProdutosController::getProduto($_POST['idproduto']);
				$prod->pro_perdido = 'S';
				$prod->update();
			}
			$ocoprod = new OcorrenciasProdutosModel();
			$ocoprod->opr_idproduto = $dados['idproduto'];
			$ocoprod->opr_idocorrencia = $dados['idocorrencia'];
			$ocoprod->opr_obs = utf8_decode($dados['obs']);
			$ocoprod->opr_aux = $dados['aux'];
			$ocoprod->opr_nome_pai = $dados['produtopai'];
			$ocoprod->opr_pai_id = $dados['produtopaiid'];
			return $ocoprod->insert();
		}
		
		public static function update($dados){
			$ocoprod = new OcorrenciasProdutosModel();
			$ocoprod->opr_id = $dados['id'];
			$ocoprod->opr_idproduto = $dados['idproduto'];
			$ocoprod->opr_idocorrencia = $dados['idocorrencia'];
			$ocoprod->opr_obs = utf8_decode($dados['obs']);
			$ocoprod->opr_aux = $dados['aux'];
			$ocoprod->opr_nome_pai = $dados['produtopai'];
			$ocoprod->opr_pai_id = $dados['produtopaiid'];
			return $ocoprod->update();
		}
		
		public static function delete($id){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->delete($id);
		}
		
		public static function getOcorrenciaProduto($id){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->selectOcorrenciaProduto($id);
		}
		
		public static function getOcorrenciasProdutos($where){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->selectAll($where);
		}
		
		public static function getOcorrenciasProdutosSterilab($where){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->selectAllSterilab($where);
		}
		
		public static function getDescarteByProduto($id){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->selectDescarteByProduto($id);
		}
		
		public static function getByEfeitoEspecial($pro, $efeito){
			$ocoprod = new OcorrenciasProdutosModel();
			return $ocoprod->selectByEfeitoEspecial($pro, $efeito);
		}
		
        public static function relOcorrenciasProdutos($where, $order){
		    $ocoprod = new OcorrenciasProdutosModel();
		    return $ocoprod->selectAllOrder($where, $order);
		}
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/
?>