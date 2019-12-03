<?php
	class TransferenciaEstoqueController {

		public static function insert(){
			/*** Insere em saida de materiais ***/
			$STransferencia = new SaidaMateriaisModel();
			$STransferencia->sma_tiposaida = 'T';
			$STransferencia->sma_idsetor = SetoresController::getCmeId();
			return $STransferencia->insert();
		}

		public static function updateSetorDestino( $dados ){

			$transferencia = new SaidaMateriaisModel();
			$transferencia->sma_id = $dados['tesID'];
			$transferencia->sma_idsetor = $dados['setID'];
			$transferencia->sma_retirado_por = $dados['retiradoPor'];
			return $transferencia->updateSetorDestino();
		}

		public static function getQtdTransferenciaNull(){
			//** Traz a qtd de tranferencia que nao foram concluidas. **//
			$transferencia = new SaidaMateriaisModel();
			return $transferencia->selectNull();
		}
		
		public static function limpaTransferencia(){
			$transferencia = new SaidaMateriaisModel();
			return $transferencia->limpaTransferencia();
		}
		
		public static function delete($tes_id){
			$transferencia = new TransferenciaEstoqueModel();
			return $transferencia->delete($tes_id);
		}

		public static function getTransferencia($tes_id){
			$transferencia = new TransferenciaEstoqueModel();
			return $transferencia->selectTrasnferencia($tes_id);
		}


		public static function deleteLastEntry($pro_id){
			$transferencia = new SaidaMateriaisModel();
			return $transferencia->deleteLastEntry($pro_id);
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