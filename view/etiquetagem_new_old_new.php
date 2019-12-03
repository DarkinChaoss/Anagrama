<?php
	//error_log(" - - - - - - - - INICIO");
	if (isset($_POST['ctx'])){
		$ctx = $_POST['ctx'];
		$_SESSION['ctx'] = $ctx;
	}
	
	//cleverson
	if($_POST['acao'] == 'setProntos'){

		$res = ProdutosController::setProntosById($_POST['idproduto'], $_POST['value']);
		die($res);
	}
	
	//cleverson
	if( $_POST['acao'] == 'getquantidadepn'){
		$res =  ProdutosController::getquantidade($_POST['idpn']);
		die($res);
	}
	//cleverson
	if( $_POST['acao'] == 'getquantidadepc'){
		$res =  ProdutosConsignadoController::getquantidade($_POST['idpc']);
		die($res);
	}
	//cleverson
	if( $_POST['acao'] == 'contaComum'){
		$res =  ItensSolicitacoesController::countComumEtiquetagem();
		die($res);
	}

	//cleverson
	if( $_POST['acao'] == 'contaConsig'){
		$res =  ItensSolicitacoesController::countConsigEtiquetagem();
		die($res);
	}

	// busca solicitação e exibe seus itens
	if ($_POST['acao'] == 'buscar' && isset($_POST['numero'])){
		$ses = SolicitacoesController::getSolicitacoes("ses_id = " . $_POST['numero']);
		if (!empty($ses)){
			$solicitacao = new SolicitacoesModel();
			$solicitacao = $ses[0];
			$data = DefaultHelper::converte_data($solicitacao->ses_dataentrada);
			$datasaida = DefaultHelper::converte_data($solicitacao->ses_datasaida);
			$setor = SetoresController::getSetor($solicitacao->ses_idsetor);
			die("$solicitacao->ses_id".";"."$solicitacao->ses_id".";".$setor->set_nome.";"."$data".";"."$datasaida");
		} else {
			die("ERRO");
		}
	}

	// monta lista de itens da solicitação
	elseif ($_POST['acao'] == 'listar'){
		die(ItensSolicitacaoHelper::listaItensSolicitacaoEtiquetagem($_POST['id']));
	}

 	// busca item da solicitação para finalização
	elseif ($_POST['acao'] == 'buscar' && isset($_POST['qrcode']) && isset($_POST['product'])){
		//error_log(" - - - - - - - - IF BUSCA");
		// trava de oitavo dígito (sétimo para Sugisawa e casos especiais)
		if($_POST['product'] == 'pn'){
			if (
					(isset($_SESSION['usu_leituraqr']) && strlen($_POST['qrcode']) >= $_SESSION['usu_leituraqr'])
					||
					(!isset($_SESSION['usu_leituraqr']) && strlen($_POST['qrcode']) >= 8)
				) {
				//error_log(" - - - - - - - - CONSULTA");
				$produto = new ProdutosModel();
				$produto = ProdutosController::getProdutoInSolicitacao($_POST['qrcode']);
				//print_r($produto);
				if (!empty($produto)){
					$dadosProduto1 = (($produto->pro_calibre != "") ? "" . $produto->pro_calibre : "")
					. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "");
					$dadosProduto2 = (($produto->pro_comprimento != "") ? "" . $produto->pro_comprimento : "")
					. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");

					/////$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);

					// verifica se produto contém ocorrência que anula reuso a ser aplicada
					$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
					//
					$reproc = ItensSolicitacoesController::getReprocessamentoItem($produto->pro_id) - count($ee);
					if($reproc < 1)
						$reproc = 1;

					/////$setor = SetoresController::getSetor($produto->pro_idsetor);

					//verifica se produto tem quantidade, se tiver ele entra no primeiro if se não, ele entra no else
					//echo $produto->pro_iso_id;
					if($produto->pro_qtde > 0){
						//echo 'tem';
					}else{

						//print_r($item);					
					}
			
					$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);
						
					/////$setor = SetoresController::getSetor($produto->pro_idsetor);
					//$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);
					//print_r($item);
					/////$metodo = MetodosController::getMetodo($item->iso_idmetodo);
					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					//$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);

					/////$metodo = MetodosController::getMetodo($item->iso_idmetodo);
					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					// se já foi finalizado, recupera os dados para apenas reimprimir
					if ($item->iso_status == 1) {
						$ultimoLote = $item->iso_lote;
						$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
						$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
					} else {

						// salva último lote na sessão
						if(isset($_SESSION['ultimoLote'])){ $ultimoLote = $_SESSION['ultimoLote']; }
							else{ $ultimoLote = ItensSolicitacoesController::getUltimoLote(); }

						$dataEsterilizacao = date("d/m/Y");
						$dataLimite = EtiquetagemHelper::populaComboLimiteUso();
					}
					$countiten = ItensSolicitacoesController::countProdetiquetagem($produto->pro_id);

					//error_log(" - - - - - - - - RETORNO");
					die("".$item->iso_id."*;*".$produto->pro_id."*;*".$produto->pro_qrcode."*;*".$produto->pro_nome."*;*".$dadosProduto1."*;*".$dadosProduto2."*;*".$produto->pro_set_nome."*;*"." - "."*;*".$item->iso_met_nome."*;*".$reproc."*;*".$produto->pro_maxqtdprocessamento."*;*".$ultimoLote."*;*".$dataEsterilizacao."*;*".$dataLimite."*;*".$item->iso_status."*;*".$produto->pro_composto."*;*".$produto->pro_qtde."*;*".$countiten['itens']);
				} else {
					die("ERRO");
				}

			} else {
				die("NULO"); // não executou consulta por falta de dígito
			}
		}else if($_POST['product'] == 'pc'){ // cleverson matias  busca produtos consignados
			if (
					(isset($_SESSION['usu_leituraqr']) && strlen($_POST['qrcode']) >= $_SESSION['usu_leituraqr'])
					||
					(!isset($_SESSION['usu_leituraqr']) && strlen($_POST['qrcode']) >= 8)
				) {
				//print_r($v);
				//error_log(" - - - - - - - - CONSULTA");
				$produto = new ProdutosConsignadoModel();
				$produto = ProdutosConsignadoController::getProdutoConsignadoInSolicitacao($_POST['qrcode']);
				//print_r($produto);
				if (!empty($produto)){
					$dadosProduto1 = (($produto->pro_calibre != "") ? "" . $produto->pro_calibre : "")
					. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "");
					$dadosProduto2 = (($produto->pro_comprimento != "") ? "" . $produto->pro_comprimento : "")
					. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");

					/////$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);

					// verifica se produto contém ocorrência que anula reuso a ser aplicada
					$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
					//
					$reproc = ItensSolicitacoesController::getReprocessamentoItemConsignado($produto->pro_id) - count($ee);
					if($reproc < 1)
						$reproc = 1;

					/////$setor = SetoresController::getSetor($produto->pro_idsetor);

					//verifica se produto tem quantidade, se tiver ele entra no primeiro if se não, ele entra no else
					//echo $produto->pro_iso_id;
					if($produto->pro_qtde > 0){
						//echo 'tem';
					}else{

						//print_r($item);					
					}
			
					$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);
						
					/////$setor = SetoresController::getSetor($produto->pro_idsetor);
					//$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);
					//print_r($item);
					/////$metodo = MetodosController::getMetodo($item->iso_idmetodo);
					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					//$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);

					/////$metodo = MetodosController::getMetodo($item->iso_idmetodo);
					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					// se já foi finalizado, recupera os dados para apenas reimprimir
					if ($item->iso_status == 1) {
						$ultimoLote = $item->iso_lote;
						$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
						$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
					} else {

						// salva último lote na sessão
						if(isset($_SESSION['ultimoLote'])){ $ultimoLote = $_SESSION['ultimoLote']; }
							else{ $ultimoLote = ItensSolicitacoesController::getUltimoLote(); }

						$dataEsterilizacao = date("d/m/Y");
						$dataLimite = EtiquetagemHelper::populaComboLimiteUso();
					}
					$countiten = ItensSolicitacoesController::countProdetiquetagem($produto->pro_id);

					//error_log(" - - - - - - - - RETORNO");
					die("".$item->iso_id."*;*".$produto->pro_id."*;*".$produto->pro_qrcode."*;*".$produto->pro_nome."*;*".$dadosProduto1."*;*".$dadosProduto2."*;*".$produto->pro_set_nome."*;*"." - "."*;*".$item->iso_met_nome."*;*".$reproc."*;*".$produto->pro_maxqtdprocessamento."*;*".$ultimoLote."*;*".$dataEsterilizacao."*;*".$dataLimite."*;*".$item->iso_status."*;*".$produto->pro_composto."*;*".$produto->pro_qtde."*;*".$countiten['itens']);
				} else {
					die("ERRO");
				}

			} else {
				die("NULO"); // não executou consulta por falta de dígito
			}
		}
	}

	// calcula data limite
	elseif (isset($_POST['limiteUso']) && !isset($_POST['lote'])){
		die( calculaDataLimite( $_POST['limiteUso'] ) );
	}

	// atualiza lote no item da solicitação e marca item como finalizado; se item já for finalizado, apenas retorna composição para reimpressão
	elseif (isset($_POST['lote'])){

	    // salva último lote na sessão
	    $_SESSION['ultimoLote'] = $_POST['lote'];

	    $iso = new ItensSolicitacaoModel();
	    $iso->iso_id                   = $_POST['iditem'];
	    $iso->iso_dataesterilizacao    = DefaultHelper::converte_data($_POST['dataEsterilizacao']);
	    $iso->iso_horaesterilizacao    = date("H:i:s");
	    $iso->iso_datalimite           = DefaultHelper::converte_data($_POST['dataLimite']);
	    $iso->iso_lote                 = $_POST['lote'];
	    $composto                      = $_POST['composto'];
	    $limiteUso                     = $_POST['limiteUso'];
		$qtde						   = $_POST['qtde'];
		$idproduto 					   = $_POST['idproduto'];
	    // Chama rotina de Etiquetagem

		if($qtde > 0){
		   	$item = ItensSolicitacoesController::getItemCount( $idproduto, $qtde );
			//print_r($item);
			//condicional para adicionar 1 ao status em quantidade
			foreach($item as $it){
				//echo $it->iso_id;
				$itemf = ItensSolicitacoesController::getItem( $it->iso_id );
				$finalizado = $item->iso_status;
				//print_r($itemf);
				if ($finalizado != 1) {
					$itemf->iso_dataesterilizacao   = $iso->iso_dataesterilizacao;
					$itemf->iso_horaesterilizacao   = $iso->iso_horaesterilizacao;
					$itemf->iso_datalimite          = $iso->iso_datalimite;
					$itemf->iso_lote                = $iso->iso_lote;
					$itemf->iso_status              = '1';
					$itemf->iso_nivelrpreparo       = $_SESSION['usu_nivel'];
					$itemf->iso_refrpreparo         = $_SESSION['usu_referencia'];
					$itemf->update();
					
					// marca produto como "pronto"
					if($_POST['ctx'] == 'pn'){
						ProdutosController::setStatus($item->iso_idproduto, '1');
					}else{
						$teste = ProdutosConsignadoController::setStatus($item->iso_idproduto, '1');
						return $teste;
					}
					// apaga duplicações, se houver
					//ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
				}
			}
			//aqui é com quantidade
			//print_r($item);	
		}else{
			die( rotinaEtiquetagem( $iso, $composto, $limiteUso ));			
		}

	}

	// busca item para reimpressão
	if (isset($_POST['acao']) && $_POST['acao'] == 'reimpressaoEtiqueta'){
	    $pro = ItensSolicitacaoHelper::listaItensSolicitacaoEtiquetagemReimpressao( $_POST['qrcode'] );
	    die($pro);
	}

	// Altera a validade - Usado em caso de reimpressão que o item não tem dataLimite
	if (isset($_POST['acao']) && $_POST['acao'] == 'alteraValidade'){
	    $data = DefaultHelper::converte_data( $_REQUEST['dataLimite'] );
	    ItensSolicitacoesController::alteraDataLimite($_REQUEST['id'], $data);
	    return;
	}

	// Etiquetagem em Massa
	elseif ($_POST['acao'] == 'etiquetagemMassa'){

	    foreach(ItensSolicitacoesController::getItensEtiquetagem() as $iso){

	        $objPro                         = ProdutosController::getProduto( $iso->iso_idproduto );   // Busca o produto
	        // $objLimiteUso                   = LimitesUsoController::getUltimoLimitesUso(); // Busca ultimo Limite de Uso Utilizado
	        $dataLimite                     = DefaultHelper::converte_data( calculaDataLimite( '2 ANOS' ) );   // Calcula data Limite de Uso

            $iso->iso_dataesterilizacao     = date("Y-m-d");
            $iso->iso_horaesterilizacao     = date("H:i:s");
            $iso->iso_datalimite            = $dataLimite;
            $iso->iso_lote                  = '';

	        // Chama rotina de Etiquetagem
	        rotinaEtiquetagem( $iso, $objPro->pro_composto, '' );
	    }

	    die('OK');

	}

    // Rotina padrão usada na etiquetagem dos itens
	//update para 1 itens
	function rotinaEtiquetagem( ItensSolicitacaoModel $iso, $composto, $limiteUso = ''){
	    $item = ItensSolicitacoesController::getItem( $iso->iso_id );
	    $finalizado = $item->iso_status;

	    if ($finalizado != 1) {

	        $item->iso_dataesterilizacao   = $iso->iso_dataesterilizacao;
	        $item->iso_horaesterilizacao   = $iso->iso_horaesterilizacao;
	        $item->iso_datalimite          = $iso->iso_datalimite;
	        $item->iso_lote                = $iso->iso_lote;
	        $item->iso_status              = '1';
	        $item->iso_nivelrpreparo       = $_SESSION['usu_nivel'];
	        $item->iso_refrpreparo         = $_SESSION['usu_referencia'];
	        $item->update();

	        // marca produto como "pronto"
	        if($_POST['ctx'] == 'pn'){
	        	ProdutosController::setStatus($item->iso_idproduto, '1');
			}else{
	    		ProdutosConsignadoController::setStatus($item->iso_idproduto, '1');
	    	}
	        // apaga duplicações, se houver
	        ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
	    }
	    // recupera idses
	    $idses = $item->iso_idses;
		
		$idpai = $item->iso_idproduto;		
		$compostosInSolicitacao = ProdutosCompostosController::getProdutosCompostosInSolicitacao(" pco_idpai = ". $idpai ."");
		
		print_r($compostosInSolicitacao);
		$composicao = 0;
		
		if($compostosInSolicitacao){
			// se item for composto, etiqueta todos os seus filhos inclusos na mesma solicitação
			if( $composto == 1){
				$idpai = $item->iso_idproduto;
				// array de filhos adicionados na mesma solicitação que o pai
				$arrAdicionados = array();
				foreach(ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_idproduto <> " . $idpai . " AND iso_status = '0'") as $ja){
					$arrAdicionados[] = $ja->iso_idproduto;
				}

				foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $idpai) as $pco){
					// se filho estiver na mesma solicitação que pai, etiqueta filho
					if(in_array($pco->pco_idfilho, $arrAdicionados)){
						// só atualiza dados se item pai não estiver já finalizado
						if ($finalizado != 1) {

							$item = new ItensSolicitacaoModel();
							$item = ItensSolicitacoesController::getItemBySolicitacaoEProduto($idses, $pco->pco_idfilho, '0');
							
							$item->iso_dataesterilizacao =  date('Y-m-d') ; // DefaultHelper::converte_data($iso->iso_dataesterilizacao);
							$item->iso_horaesterilizacao = date("H:i:s");
							// $item->iso_datalimite = DefaultHelper::converte_data($iso->iso_datalimite);
							$item->iso_datalimite = $iso->iso_datalimite;
							
							$item->iso_lote = $iso->iso_lote;
							$item->iso_status = '1'; 
							$item->iso_nivelrpreparo = $_SESSION['usu_nivel'];
							$item->iso_refrpreparo = $_SESSION['usu_referencia'];
							$item->update();
							// marca filho como "pronto"
							ProdutosController::setStatus($item->iso_idproduto, '1');
							// apaga duplicações, se houver
							ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
						}
						$composicao++;
					}
				}

				if( $composicao == 0 ){
					
					$item  = new ItensSolicitacaoModel();
					$itens = $item->selectUltimaSolicitacao( $idpai );

					$composicao = count( $itens );
									
				}
				
			}			
		}

	    // verifica quantos itens ainda faltam etiquetar
	    $itens = ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_status = '0'");
	    if(sizeof($itens) > 0) {
	        // atualiza status da solicitação: 2 (Etiquetagem)
	        $status = "2";
	    } else {
	        // atualiza status da solicitação: 5 (Finalizada)
	        $status = "5";
	    }

	    if( $limiteUso > 0 ){
	        // grava em tb_limitesuso o último valor utilizado
	        $liu = new LimitesUsoModel();
	        $liu->limpaUltimo();
	        $liu = LimitesUsoController::getLimiteUso( $limiteUso );
	        $liu->liu_ultimo = '1';
	        $liu->update();
	    }

        // atualiza solicitação
        $ses = new SolicitacoesModel();
        $ses = $ses->selectSolicitacao($idses);
        $ses->ses_status = $status;

        if($status == "5")
            $ses->ses_datasaida = date("Y-m-d H:i:s");
            $ses->update();

	    return("".$composicao);

	}


	function calculaDataLimite( $limiteUso ){

	    // Ex: string, 30 DIAS / 2 MESES / 2 ANOS

	    $aux = split(" ", $limiteUso);
	    $qtd = $aux[0];
	    $medida = $aux[1];
	    switch ($medida) {
	        case "DIAS":
	            $medida = " days";
	            break;
	        case "MESES":
	            $medida = " months";
	            break;
	        case "ANOS":
	            $medida = " years";
	            break;
	        default:
	            break;
	    }
	    $dataLimite = date("d/m/Y", strtotime("+".$qtd.$medida));
	    return ($dataLimite);

	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2015
*/
?>