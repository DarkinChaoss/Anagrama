<?php

	// verifica se é para inserir
	if( isset( $_POST['inserir'] ) AND $_POST['inserir'] == 1 ){

		// o retorno sera JSON
		header("Content-Type: application/json", true);
		$retorno = null;

		$produto = new ProdutosModel();
		$produtos = $produto->selectAll("pro_qrcode='{$_POST['newQRCode']}'");

		if( !empty( $produtos ) ){

			$retorno['err']	= "Erro: O QRCode {$_POST['newQRCode']} informado já está sendo utilizado.";
			echo json_encode( $retorno );		
			exit;
			
		}

		$ret = QrcodesController::insert( $_POST );

		if( $ret ){

			$produto = new ProdutosModel();
			$produto = $produto->selectProduto( $_POST['idproduto'] );

			$produto->pro_id = $_POST['idproduto'];
			$produto->pro_qrcode = $_POST['newQRCode'];
			$produto->updateQrcode();

			// aumentar a quantidade e processamento
			if( isset( $_POST['aumentarProcessamento'] ) ){

				$produto->updateReuso( 	$produto->pro_maxqtdprocessamento,
									 	( $produto->pro_maxqtdprocessamento * 2 ) );				

			}			

			unset( $produto );

			$ocorrencia = new OcorrenciasModel();
			$ocorrencia_subs = $ocorrencia->selectAll( "oco_efeitoespecial='S'" );

			// se não existir cria a ocorrencia do tipo substituicao
			if( empty( $ocorrencia_subs ) ){

				$ocorrencia->oco_nome = "SUBSTITUICAO DE QRCODE";
				$ocorrencia->oco_descricao  = "SUBSTITUICAO DE QRCODE";
				$ocorrencia->oco_efeitoespecial = 'S';	
				$id = $ocorrencia->insert();

				if( $id )
					$ocorrencia_subs = $ocorrencia->selectAll( "oco_id={$id}" );

			}

			//destroy a classe de ocorrencias
			unset( $ocorrencia );

			// ocorrencia de substituicao
			$ocorrencia_subs = current( $ocorrencia_subs );
            
			//ocorrencia do produto
			$ocorrencia_produto = new OcorrenciasProdutosModel();
			$ocorrencia_produto->opr_idproduto = $_POST['idproduto'];
			$ocorrencia_produto->opr_idocorrencia = $ocorrencia_subs->oco_id;
			$ocorrencia_produto->opr_obs = strtoupper( $_POST['motivo_substituicao'] );
			$ocorrencia_produto->opr_data = date('Y-m-d H:i:s');
			
			$id = $ocorrencia_produto->insert();

			if( $id ){
				$retorno['suc'] = "QRCode {$_POST['qrcode_atual']} substituido por {$_POST['newQRCode']}.";
			}
			else{
				$retorno['err']	= "Erro ao substituir o QRCode {$_POST['qrcode_atual']}";				
			}

		}
		else{
			$retorno['err']	= "Erro ao substituir o QRCode {$_POST['qrcode_atual']}";
		}

		echo json_encode( $retorno );		

	}

