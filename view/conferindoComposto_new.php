<?php

	@session_start();

	if( $_POST['retirarEsterilizacao'] ){

		$item = ItensSolicitacoesController::getItens( "iso_idproduto={$_POST['retirarEsterilizacao']} AND iso_status=0");	

		if( !empty( $item ) ){
			
			$i = new ItensSolicitacaoModel();
			$i->delete( $item[0]->iso_id );

		}

	}

	if( $_POST['qrcode_busca'] ){

		$_POST['qrcode_busca'] = strtoupper( $_POST['qrcode_busca'] );
		
		$produto = ProdutosController::getProdutos("upper(pro_qrcode) = '{$_POST['qrcode_busca']}'");
		$retorno = null;

		if( !empty( $produto ) ){

				$filhos = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = " . $produto[0]->pro_id );
				if( !empty( $filhos ) ){

					$prod_pai = ProdutosController::getProduto( $filhos[0]->pco_idpai );
					$retorno['msg'] = "O Material: {$produto[0]->pro_qrcode} - {$produto[0]->pro_nome} pertence à {$prod_pai->pro_qrcode} - {$prod_pai->pro_nome}.";

				}
				else{
					$retorno['msg'] = "O Material: {$produto[0]->pro_qrcode} - {$produto[0]->pro_nome} é um item avulso.";
				}							

		}
        else{
            $retorno['msg'] = "O Material: {$_POST['qrcode_busca']} não foi encontrado.";
        }

		header("Content-Type: application/json", true);
		echo json_encode( $retorno );
		exit;

	}

	if( $_POST['buscar'] ){

		$_POST['buscar'] = strtoupper( $_POST['buscar'] );

		$produto = ProdutosController::getProdutos("upper(pro_qrcode) = '{$_POST['buscar']}'");
		$retorno = null;
		if( !empty( $produto ) ){

			if( $produto[0]->pro_composto == 1 ){

				$filhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $produto[0]->pro_id );
				if( !empty( $filhos ) ){

					$produto = current( $produto );

					$item = ItensSolicitacoesController::getItens( "iso_idproduto={$produto->pro_id} AND iso_status=0");					
					if( !empty( $item ) ){
						$retorno['produto'] = $produto;
					}
					else {
						$retorno['erro'] = "Não consta uma solicitação de esterilização aberta para este material: {$produto->pro_nome}";
					}

				}
				else{
					$retorno['erro'] = "Nenhum material esta associado a este material composto";
				}				

			}else{

				$retorno['erro'] = "Material {$_POST['buscar']} não é um composto.";

			}
		}
		else{
			$retorno['erro'] = "Material {$_POST['buscar']} não foi encontrado.";
		}

		header("Content-Type: application/json", true);
		echo json_encode( $retorno );
		exit;

	}

	if( $_POST['idpai'] ){
		// verifica se tem algum materiais_conferido
		if( array_key_exists( 'materiais_conferidos' , $_POST ) ){
			
			$idpai = $_POST['idpai'];
		
			//aqui para conferir o pai
			$itpai = ItensSolicitacoesController::getItens( "iso_idproduto={$idpai} AND iso_status=0");
			switch($_SESSION['usu_nivel']){
				case 2:
					$aux = new ConferentesModel();
					$aux = ConferentesController::getConferente($_SESSION['usu_referencia']);
					$respPreparo = $aux->cnf_nome;
					break;
				case 3:
					$aux = new EtiquetadoresModel();
					$aux = EtiquetadoresController::getEtiquetador($_SESSION['usu_referencia']);
					$respPreparo = $aux->eti_nome;
					break;
				case 4:
					$aux = new ResponsaveisTecnicosModel();
					$aux = ResponsaveisTecnicosController::getRTecnico($_SESSION['usu_referencia']);
					$respPreparo = $aux->rte_nome;
					break;
				default:
					break;
			}
			$conf = ResponsaveisTecnicosController::getRTecnico($_SESSION['usu_referencia']); 

			$dados = array(
				'id' => $itpai[0]->iso_id,
				'dataconferencia' => date('Y-m-d H:i:s'),
				'conferidopor' => $respPreparo,	
			);
			
			//CONFERE FILHOS
			$teste = ItensSolicitacoesController::updateConfPai($dados);
			$retorno['msg'] = $conf->cnf_nome;
			
			foreach ($_POST['materiais_conferidos'] as $material) {

				$item = ItensSolicitacoesController::getItens( "iso_idproduto={$material} AND iso_status=0");

				// so conferente o q encontrar na esterilizacao
				if( is_array( $item ) AND ( isset( $item[0]->iso_idproduto ) OR !empty( $item[0]->iso_idproduto ) ) ){

					$item = current( $item );

					$respPreparo = "";
					switch($_SESSION['usu_nivel']){
						case 2:
							$aux = new ConferentesModel();
							$aux = ConferentesController::getConferente($_SESSION['usu_referencia']);
							$respPreparo = $aux->cnf_nome;
							break;
						case 3:
							$aux = new EtiquetadoresModel();
							$aux = EtiquetadoresController::getEtiquetador($_SESSION['usu_referencia']);
							$respPreparo = $aux->eti_nome;
							break;
						case 4:
							$aux = new ResponsaveisTecnicosModel();
							$aux = ResponsaveisTecnicosController::getRTecnico($_SESSION['usu_referencia']);
							$respPreparo = $aux->rte_nome;
							break;
						default:
							break;
					}					

					$item->iso_conferidopor = $respPreparo;
					$item->iso_dataconferencia = date('Y-m-d H:i:s');

					$item->updateConferencia();
	
				}
			}
			
			
			$retorno['msg'] = "Conferência referente ao Material {$_POST['nome_pai']} concluida.";			

		}
		else{
			$retorno['msg'] = "Nenhum item foi conferido. Para salvar a conferência, confira pelo menos 1 item da composição";
			/*{$_POST['nome_pai']}*/
		}

		header("Content-Type: application/json", true);		
		echo json_encode( $retorno );
		exit;

	}