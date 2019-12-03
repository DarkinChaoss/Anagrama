<?php

	function excluir( $idproduto , $idsaida , $print = null ){

		// volta status do produto para "pronto"
		$res = ProdutosController::setStatus( $idproduto , '1');
		if($res){
			// apaga registro de saída do material
			$res = ItensSaidaController::delete( $idsaida );

			if( !empty( $print ) ){
				if($res){
					die("OK");
				} else {
					die("ERRO");
				}
			}


		} else {

			if( !empty( $print ) ){
				die("ERRO");
			}

		}

	}

	// efetua devolução do material
	if ($_POST['acao'] == "devolver" && $_POST['idsaida'] != "" && $_POST['idproduto'] != "") {


		$produto = ProdutosController::getProduto( $_POST['idproduto'] );

		if( !empty( $produto ) AND is_object( $produto ) AND isset( $produto->pro_id ) ){

			if( $produto->pro_composto == 1 ){

				$filhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $produto->pro_id );
				if( !empty( $filhos ) ){

					$err = false;
					foreach ($filhos as $filho) {	

						$isa = ItensSaidaController::getItemUltimaSaida( $filho->pco_idfilho );				
						$isa = $isa[0];

						excluir( $filho->pco_idfilho , $isa->isa_id );

					}

				}

				excluir( $_POST['idproduto'] , $_POST['idsaida'] , 1 );				

			}
			else{
				excluir( $_POST['idproduto'] , $_POST['idsaida'] , 1 );
			}

		}
		else{
			die("ERRO");
		}

	}
	/*

		quando busca 

	*/
	// busca produto para devolução
	if ($_POST['acao'] == "buscar" && isset($_POST['qrcode']) && (!empty($_POST['qrcode']) || $_POST['qrcode'] != "" || $_POST['qrcode'] != " ")){
		//obter dados do produto pelo qrcode
		$pro = ProdutosController::getProdutos("pro_qrcode = '".$_POST['qrcode']."'");
		// produto não vazio!
		if (!empty($pro)){
			
			// transforma o produto em 
			$produto = new ProdutosModel();
			$produto = $pro[0];
			
			// se produto não estiver atualmente retirado
			if($produto->pro_prontos <= 1 && false){
				if($produto->pro_status == '1') {
					die("NAORETIRADO");
				}
			}
			
			// total de itens na saída
			$total = ItensSaidaController::countItens($produto->pro_id);
			
			// obter a últim  saída do produto;
			$isa = ItensSaidaController::getItemUltimaSaida($produto->pro_id);
			// se não houver saída lançada para o produto
			if(count($isa) == 0){
				die("SEMSAIDA");
			}
			
			// 
			$isa = $isa[0];
			
			$sma = SaidaMateriaisController::getSaidaMateriais($isa->isa_idsaida);
			
			$infoProduto = $produto->pro_nome
						. (($produto->pro_calibre != "") ? ", " . $produto->pro_calibre : "") 
						. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "")
						. (($produto->pro_comprimento != "") ? ", " . $produto->pro_comprimento : "")
						. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");
			
			
			$arr = array();
			foreach( ItensSaidaController::getItemUltimaSaida($produto->pro_id) as $isa ){

				$sma = SaidaMateriaisController::getSaidaMateriais($isa->isa_idsaida);
				
				array_push($arr, 
					[
					 'produto' => $produto->pro_id,
					 'infoProduto' => $infoProduto,
					 'lote' => $isa->isa_lote,
					 'isa_id' => $isa->isa_id,
					 'isa_data' => DefaultHelper::converte_data($isa->isa_data),
					 'prontuario' => $sma->sma_prontuario,
					 'paciente' => $sma->sma_paciente,
					 'total' => $total,
					 'isa_idsaida' => $isa->isa_idsaida
					]
				);
				
				//array_push($arr, "$produto->pro_id"."*;*"."$infoProduto"."*;*".$isa->isa_lote."*;*".$isa->isa_id."*;*".DefaultHelper::converte_data($isa->isa_data)."*;*".$sma->sma_prontuario."*;*".$sma->sma_paciente."*;*"."$total"."*;*".$isa->isa_idsaida);
				
			}
			die(json_encode($arr));
			
			
			//array_push($arr, "$produto->pro_id"."*;*"."$infoProduto"."*;*".$isa->isa_lote."*;*".$isa->isa_id."*;*".DefaultHelper::converte_data($isa->isa_data)."*;*".$sma->sma_prontuario."*;*".$sma->sma_paciente."*;*"."$total");
			
			//print_r($arr);
			//die("$produto->pro_id"."*;*"."$infoProduto"."*;*".$isa->isa_lote."*;*".$isa->isa_id."*;*".DefaultHelper::converte_data($isa->isa_data)."*;*".$sma->sma_prontuario."*;*".$sma->sma_paciente."*;*"."$total");
			
		} else {
			die("ERRO");
		}
			
	}
	
?>