<?php

	session_start();

	if( $_POST['buscar'] ){		
		$typeprod = $_POST['productcheck'];
		
		if($typeprod == 'pn'){
			$_POST['buscar'] = strtoupper( $_POST['buscar'] );
			$produto = ProdutosController::getProdutos("pro_qrcode = '{$_POST['buscar']}'");
			$retorno = null;

			if($produto[0]->pro_qtde > 0){
				$retorno['qtde'] = $produto[0]->pro_qtde;
				$retorno['produto'] = (integer)$produto[0]->pro_id;
			}else{                                                                                                         
				if(!empty($produto)){
					$produto = current($produto);
					$retorno['produto'] = (integer)$produto->pro_id;
				}else{
					$retorno['erro'] = "Produto {$_POST['buscar']} não foi encontrado";
				}
			}
		}else{
			
			$_POST['buscar'] = strtoupper( $_POST['buscar'] );

			$produto = ProdutosConsignadoController::getProdutoConsignadoByQrCode($_POST['buscar']);
			$retorno = null;

			if(!$produto->pro_id == null){
				$retorno['produto'] = $produto->pro_id;
			}
			else{
				$retorno['erro'] = "Produto {$_POST['buscar']} não foi encontrado";
			}
		}

		header("Content-Type: application/json", true);
		echo json_encode( $retorno );
		exit;
	}

	if( $_POST['idsaida'] ){

		$dados['conferente'] = $_SESSION['usu_login'];
		$dados['idsaida'] = $_POST['idsaida'];

		$item_saida = new ItensSaidaModel();
			
		if($_POST['qtde2']){
		
			//ver como fazer o update sequencial de produtos com mesmo id na saida
			foreach ($_POST['materiais_pendentesQtde'] as $material) {
				$iditen = $material;
			}
			
			$qtde = $_POST['qtde2'];
			
			$id = str_replace('_', '', $iditen);
		
			$ret = $item_saida->updateConfereSaida2( $dados, $id, $qtde);

		}
		
		/*if($_POST['materiais_pendentesConsignado']){
			$pendentesmatCons = $_POST['materiais_pendentesConsignado'];
			$id = str_replace('_c', '', $pendentesmatCons);
			
			$retorno = $id;
			$ret = $item_saida->updateConfereSaidaConsignado( $dados , $id );		
		}*/
		
		if($_POST['qtde1']){
			$not_in = null;
			// verifica se tem alguma 
			
			$pendentesmat = $_POST['materiais_pendentes'];
			
			$ret = $item_saida->updateConfereSaida( $dados , $pendentesmat );
			
			//$retorno = $ret;
			
			///$retorno = $ret;

			//retorna o post que vem quando salvar a coferencia pos procedimento.		
			/*if( array_key_exists( 'materiais_pendentes' , $_POST ) ){
				$not_in .='(';

				foreach ($_POST['materiais_pendentes'] as $material) {
					$not_in .= "{$material},";
				}
				$not_in = substr($not_in,0, strlen( $not_in ) -1) . ")";
			}

				$ret = $item_saida->updateConfereSaida( $dados , $not_in );*/
		}


		$saida = new SaidaMateriaisModel();
		$saida->updateConferencia( $dados['idsaida'] );

		header("Content-Type: application/json", true);

	
		if( $ret ){
			$retorno['msg'] = "Conferência referente ao Prontuário {$dados['idsaida']} concluida.";
		}
		else{
			$retorno['msg'] = "Erro ao realizar a Conferência do Prontuário {$dados['idsaida']}";
		}

		echo json_encode($retorno);
		exit;

	}