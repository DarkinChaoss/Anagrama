<?php
	class ItensTransferenciaController {

		public static function insert($dados){
			
			$Itenstransferencia = new ItensSaidaModel();
			$Itenstransferencia->isa_idsaida = $dados['smaID'];
			$Itenstransferencia->isa_idproduto = $dados['idPro'];
			$Itenstransferencia->isa_idsetororigem = $dados['idSetor'];
			$Itenstransferencia->isa_idsetordestino = $dados['idSetorDestino'];
			$Itenstransferencia->isa_reuso = $dados['reuso'];
			$Itenstransferencia->isa_data = date("Y-m-d H:i:s");
			$Itenstransferencia->loteref = $dados['loteref'];
			
			return $Itenstransferencia->insert();
			
			/*$Itenstransferencia = new ItensTransferenciaModel();
			$Itenstransferencia->ite_idtransferencia = $dados['tesID'];
			$Itenstransferencia->ite_idproduto = $dados['idPro'];
            $Itenstransferencia->ite_idsetororigem = $dados['idSetor'];
            return $Itenstransferencia->insert();*/
			
		}

		public static function updateSetDestino($dados){
			//error_log("controll");
			$Itenstransferencia = new ItensSaidaModel();
			$Itenstransferencia->isa_idsaida = $dados['tesID'];
			$Itenstransferencia->isa_idsetordestino = $dados['setID'];

			return $Itenstransferencia->updateSetDestino();
		}

		public static function delete($ite_id){
			$Itenstrasnferencia = new ItensSaidaModel();
			return $Itenstrasnferencia->delItensTransf($ite_id);
			/*$Itenstransferencia = new ItensTransferenciaModel();
			return $Itenstransferencia->deleteItem($ite_id);*/
		}
	
		public static function getItemTransferenciaAberta($where){
		    $itensTransferencia = new ItensSaidaModel();
		    return $itensTransferencia->selectItemTransferenciaAberta($where);
		}
		
		public static function limpaItensTransferencia(){
			$item = new ItensSaidaModel();
			return $item->limpaItensTransferencia();
		}
		
		public static function getTransferencia($ite_id){
			$transferencia = new TransferenciaEstoqueModel();
			return $transferencia->selectTrasnferencia($ite_id);
		}

		

		public static function getItensTransferencia($ite_idsma, $qrcode, $lote){
		    /** Busca pela tabela de itens de saída de materiais **/
			$itenstransferencia = new ItensSaidaModel();
		    return $itenstransferencia->selectItensTransferencia($ite_idsma, $qrcode, $lote);
		}

		public static function getIdProdutoByTransferencia($ite_idtes){
			$itenstransferencia = new ItensTransferenciaModel();
			return $itenstransferencia->selectIdProdutoByTransferencia($ite_idtes);
		}
		
		public static function getOrigemProduto($proID){
			$idSes = SolicitacoesController::getMaiorSolicitacaoProd($proID); 
           	$objSes = SolicitacoesController::getSolicitacao($idSes);
           	error_log("data do banco Soliciataca de Esterilicazacao [filho]" . $objSes->ses_dataentrada);
           	$intDataSes = strtotime($objSes->ses_dataentrada); 
           	error_log("strtotime dela [filho] ".$intDataSes);
           	$idSma = SaidaMateriaisController::getMaiorSaidaProd($proID);
           	$objSma = SaidaMateriaisController::getSaidaMateriais($idSma);
           	error_log("data do banco ultima saida [filho] " . $objSma->sma_ultimolancamento);
           	$IntDataSma = strtotime($objSma->sma_ultimolancamento);
           	error_log("strtotime dela ".$IntDataSma);
           	 //verificar se veio data de saida se nao veio pegar direto a de esteriliacao 
           	if ($intDataSes > $IntDataSma){
           		error_log('pegou origem da esterilizacao [filho]');
           		$SetorOrigem = $objSes->ses_idsetor;
           	}else{
           		error_log('pegou origem da saída [filho]');
           		$SetorOrigem = $objSma->sma_idsetor;
           	}
           	
           	return $SetorOrigem;
		}
		
		//*** atualizar **//
		public static function getLastSmaIdFromFather($id){
			$Itenstrasnferencia = new ItensSaidaModel();
			return $Itenstrasnferencia->getLastSmaIdFromFather($id);
		}

		public static function getSetorOrigin($pro_id){
			$Itenstrasnferencia = new ItensSaidaModel();
			return $Itenstrasnferencia->getSetorOrigin($pro_id);
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