<?php
	set_time_limit(0); 
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
	if($_POST['acao'] == 'setLote'){
		$_SESSION['ultimoLote'] = $_POST['ultimo_lote'];
		die();
	}

	if($_POST['acao'] == 'getqte'){
		$iso = new ItensSolicitacaoModel();
		$ret = $iso->getqte($_POST['qrcode']);
		$ret = json_encode($ret['qte']);
		die($ret);
	}

	if($_POST['acao'] == 'getdados'){
		$iso = new ItensSolicitacaoModel();
		$ret = $iso->getDados($_POST['qrcode'], $_POST['lote']);
		$ret = json_encode($ret);
		die($ret);
	}

	//cleverson
	if($_POST['acao'] == 'setLoteQtd'){
		$_SESSION['ultimo_loteQtd'] = $_POST['ultimo_loteQtd'];
		die();
	}
	
	//cleverson
	if( $_POST['acao'] == 'getquantidadepn'){
		$res =  ProdutosController::getquantidade($_POST['idpn']);
		die($res . '*' .$_POST['qrcodeQtd']);
	}
	//cleverson
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

	// busca solicita��o e exibe seus itens
	if ($_POST['acao'] == 'buscar' && isset($_POST['numero'])){
		$ses = SolicitacoesController::getSolicitacoes("ses_id = " . $_POST['numero']);
		if (!empty($ses)){
			$solicitacao = new SolicitacoesModel();
			$solicitacao = $ses[0];
			$data = DefaultHelper::converte_data($solicitacao->ses_dataentrada);
			$datasaida = DefaultHelper::converte_data($solicitacao->ses_datasaida);
			$setor = SetoresController::getSetor($solicitacao->ses_idsetor);
			error_log('aqui numero 1');
			die("$solicitacao->ses_id".";"."$solicitacao->ses_id".";".$setor->set_nome.";"."$data".";"."$datasaida");
		} else {
			die("ERRO");
		}
	}

	// monta lista de itens da solicita��o
	elseif ($_POST['acao'] == 'listar'){
		die(ItensSolicitacaoHelper::listaItensSolicitacaoEtiquetagem($_POST['id']));
		error_log('aqui numero 2');
	}

 	// busca item da solicita��o para finaliza��o
	elseif ($_POST['acao'] == 'buscar' && isset($_POST['qrcode']) && isset($_POST['product'])){
		//error_log(" - - - - - - - - IF BUSCA");
		// trava de oitavo d�gito (s�timo para Sugisawa e casos especiais)
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

					// verifica se produto cont�m ocorr�ncia que anula reuso a ser aplicada
					$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
					//
					$reproc = ItensSolicitacoesController::getReprocessamentoItem($produto->pro_id) - count($ee);
					if($reproc < 1)
						$reproc = 1;

					/////$setor = SetoresController::getSetor($produto->pro_idsetor);

					//verifica se produto tem quantidade, se tiver ele entra no primeiro if se n�o, ele entra no else
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
					$mt = MetodosController::getMetodo($item->iso_idmetodo);

					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					//$item = ItensSolicitacoesController::getItem($produto->pro_iso_id);

					/*$metodo = MetodosController::getMetodo($item->iso_idmetodo);
					/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);

					$aux = explode("*;*", ItensSolicitacoesController::getLastMetodoERespTec());
	
					if(isset($_SESSION['metodo'])){
						$ultimoMetodo = $_SESSION['metodo'];
					}else{
						$ultimoMetodo = $aux[0];
					}*/
					//echo $ultimoMetodo;
					
					// se j� foi finalizado, recupera os dados para apenas reimprimir
					if ($item->iso_status == 1) {
						$ultimoLote = $item->iso_lote;
						$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
						$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
						$metodo = $mt->met_nome;
						
					} else {

						// salva �ltimo lote na sess�o
						//if(isset($_SESSION['ultimoLote'])){ $ultimoLote = $_SESSION['ultimoLote']; }
						//	else{ $ultimoLote = ItensSolicitacoesController::getUltimoLote(); }

						$dataEsterilizacao = date("d/m/Y");
						$dataLimite = EtiquetagemHelper::populaComboLimiteUso();

						$metodo = SolicitacoesHelper::populaComboMEsterilizacao($_SESSION['metodo']);
						//$peo = SolicitacoesHelper::populaComboMEsterilizacao();
						$peo = SolicitacoesHelper::populaComboEEsterilizacaoet($_SESSION['eqet']);

					}
					$countiten = ItensSolicitacoesController::countProdetiquetagem($produto->pro_id);

					//dados retonar aqui na fun��o save
					error_log('aqui numero 3');
					die("".$item->iso_id."*;*".$produto->pro_id."*;*".$produto->pro_qrcode."*;*".$produto->pro_nome."*;*".$dadosProduto1."*;*".$dadosProduto2."*;*".$produto->pro_set_nome."*;*"." - "."*;*".$ultimoMetodo."*;*".$reproc."*;*".$produto->pro_maxqtdprocessamento."*;*".$ultimoLote."*;*".$dataEsterilizacao."*;*".$dataLimite."*;*".$item->iso_status."*;*".$produto->pro_composto."*;*".$produto->pro_qtde."*;*".$countiten['itens']."*;*".$metodo."*;*".$peo);
				
				} else {
					die("ERRO");
				}

			} else {
				die("NULO"); // n�o executou consulta por falta de d�gito
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

					// verifica se produto cont�m ocorr�ncia que anula reuso a ser aplicada
					$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
					//
					$reproc = ItensSolicitacoesController::getReprocessamentoItem($produto->pro_id) - count($ee);
					if($reproc < 1)
						$reproc = 1;

					/////$setor = SetoresController::getSetor($produto->pro_idsetor);

					//verifica se produto tem quantidade, se tiver ele entra no primeiro if se n�o, ele entra no else
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

					$aux = explode("*;*", ItensSolicitacoesController::getLastMetodoERespTec());
/*					$metodo = MetodosController::getMetodo($aux[0]);
					$equipamento = SolicitacoesHelper::populaComboEEsterilizacao();	
*/
					$dataEsterilizacao = date("d/m/Y");
					$dataLimite = EtiquetagemHelper::populaComboLimiteUso();

					$metodo = SolicitacoesHelper::populaComboMEsterilizacao($_SESSION['metodo']);
					//$peo = SolicitacoesHelper::populaComboMEsterilizacao();
					$equipamento = SolicitacoesHelper::populaComboEEsterilizacaoet($_SESSION['eqet']);

					if(isset($_SESSION['metodo'])){
						$ultimoMetodo = $_SESSION['metodo'];

					}else{
						$ultimoMetodo = $aux[0];
					}					
					
					// se j� foi finalizado, recupera os dados para apenas reimprimir
					if ($item->iso_status == 1) {
						$ultimoLote = $item->iso_lote;
						$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
						$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
					} else {

						// salva �ltimo lote na sess�o
						//if(isset($_SESSION['ultimoLote'])){ $ultimoLote = $_SESSION['ultimoLote']; }
							//else{ $ultimoLote = ItensSolicitacoesController::getUltimoLote(); }

						$dataEsterilizacao = date("d/m/Y");
						$dataLimite = EtiquetagemHelper::populaComboLimiteUso();
					}
					$countiten = ItensSolicitacoesController::countProdetiquetagem($produto->pro_id);

					//error_log(" - - - - - - - - RETORNO");
					error_log('aqui numero 4');
					die("".$item->iso_id."*;*".$produto->pro_id."*;*".$produto->pro_qrcode."*;*".$produto->pro_nome."*;*".$dadosProduto1."*;*".$dadosProduto2."*;*".$produto->pro_set_nome."*;*"." - "."*;*".$ultimoMetodo."*;*".$reproc."*;*".$produto->pro_maxqtdprocessamento."*;*".$ultimoLote."*;*".$dataEsterilizacao."*;*".$dataLimite."*;*".$item->iso_status."*;*".$produto->pro_composto."*;*".$produto->pro_qtde."*;*".$countiten['itens']."*;*".$metodo->met_nome);
				} else {
					die("ERRO");
				}

			} else {
				die("NULO"); // n�o executou consulta por falta de d�gito
			}
		}
	}

	// calcula data limite
	elseif (isset($_POST['limiteUso']) && !isset($_POST['lote'])){
		die( calculaDataLimite( $_POST['limiteUso'] ) );
	}

	// atualiza lote no item da solicita��o e marca item como finalizado; se item j� for finalizado, apenas retorna composi��o para reimpress�o
	elseif (isset($_POST['lote'])){

	// salva �ltimo lote na sess�o
		$_SESSION['metodo'] = $_POST['metEsterilizacao'];
	    //$_SESSION['ultimoLote'] = $_POST['lote'];
		$_SESSION['eqet'] = $_POST['eqEsterilizacaoet'];
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
		$iso->iso_idmetodo			   = $_POST['metEsterilizacao'];
		$iso->iso_idequipamentoet	   = $_POST['eqEsterilizacaoet'];

	    // Chama rotina de Etiquetagem

		if($qtde > 0){
			$verifica = new ItensSolicitacaoModel();
			$ver = $verifica::verificarQte($idproduto, $qtde);
			$item = ItensSolicitacoesController::getItemCountNew( $idproduto, $qtde );
			//print_r($item);
			//condicional para adicionar 1 ao status em quantidade
			//echo $it->iso_id;
			$itemf = ItensSolicitacoesController::getItem(  $item['iso_id'] );
			$finalizado = $item['iso_status'];
			//print_r($itemf);
			if ($finalizado != 1) {

				$_SESSION['metodo'] = $iso->iso_idmetodo;
				
				if ($ver != '404'){
					$itemf->iso_id = $ver;
					$loteRef = ItensSolicitacoesController::getLote( $item['iso_id'] );
				}
				elseif($ver == '400'){
					$loteRef = ItensSolicitacoesController::getLote( $item['iso_id'] );
				}

				$itemf->iso_dataesterilizacao   = $iso->iso_dataesterilizacao;
				$itemf->iso_horaesterilizacao   = $iso->iso_horaesterilizacao;
				$itemf->iso_datalimite          = $iso->iso_datalimite;
				$itemf->iso_lote                = $iso->iso_lote;
				$itemf->iso_status              = '1';
				$itemf->iso_idmetodo			= $iso->iso_idmetodo;
				$itemf->iso_idequipamentoet		= $iso->iso_idequipamentoet;
				$itemf->iso_nivelrpreparo       = $_SESSION['usu_nivel'];
				$itemf->iso_refrpreparo         = $_SESSION['usu_referencia'];
				$itemf->update();
					
				// marca produto como "pronto"
				if($_POST['ctx'] == 'pn'){
					
					ProdutosController::setStatus($item->iso_idproduto, '1');
				}else{
					ProdutosConsignadoController::setStatus($item->iso_idproduto, '1');
				}
				// apaga duplica��es, se houver
				//ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
			}	
			
			if ($loteRef != ''){	
				error_log('aqui numero 6');
				die($_POST['qrcodeQtd'] . '*' . rotinaEtiquetagem( $iso, $composto, $limiteUso ). '*' . $loteRef );		
			}
			else{
				error_log('aqui numero 7');
				die(  '*'. $_POST['qrcodeQtd'] . '*' . rotinaEtiquetagem( $iso, $composto, $limiteUso ));	
			}

		}else{
			die(  '*'. $_POST['qrcodeQtd'] . '*' . rotinaEtiquetagem( $iso, $composto, $limiteUso));
				
		}

	}

	// busca item para reimpress�o
	if (isset($_POST['acao']) && $_POST['acao'] == 'reimpressaoEtiqueta'){
		$pro = ItensSolicitacaoHelper::listaItensSolicitacaoEtiquetagemReimpressao( $_POST['qrcode'] );
		error_log('aqui numero 9');
	    die($pro);
	}

	// Altera a validade - Usado em caso de reimpress�o que o item n�o tem dataLimite
	if (isset($_POST['acao']) && $_POST['acao'] == 'alteraValidade'){
	    $data = DefaultHelper::converte_data( $_REQUEST['dataLimite'] );
	    ItensSolicitacoesController::alteraDataLimite($_REQUEST['id'], $data);
	    return;
	}

	// Etiquetagem em Massa
	elseif ($_POST['acao'] == 'etiquetagemMassa'){

	    foreach(ItensSolicitacoesController::getItensEtiquetagem() as $iso){
			$_SESSION['metodo'] = $iso->iso_idmetodo;
	        $objPro                         = ProdutosController::getProduto( $iso->iso_idproduto );   // Busca o produto
	        // $objLimiteUso                   = LimitesUsoController::getUltimoLimitesUso(); // Busca ultimo Limite de Uso Utilizado
	        $dataLimite                     = DefaultHelper::converte_data( calculaDataLimite( '2 ANOS' ) );   // Calcula data Limite de Uso
			$iso->iso_idmetodo				= $iso->iso_idmetodo;
			$iso->iso_idequipamentoet		= $iso->iso_idequipamentoet;
            $iso->iso_dataesterilizacao     = date("Y-m-d");
            $iso->iso_horaesterilizacao     = date("H:i:s");
            $iso->iso_datalimite            = $dataLimite;
            $iso->iso_lote                  = '';

			// Chama rotina de Etiquetagem
	        rotinaEtiquetagem( $iso, $objPro->pro_composto, '' );
	    }

	    die('OK');

	}

    // Rotina padr�o usada na etiquetagem dos itens
	//update para 1 itens
	function rotinaEtiquetagem( ItensSolicitacaoModel $iso, $composto, $limiteUso = ''){

	    $item = ItensSolicitacoesController::getItem( $iso->iso_id );
	    $finalizado = $item->iso_status;
	    $iso_idmetodo = ($iso->iso_idmetodo != 0) ? $iso->iso_idmetodo : "''";
	    
	    if ($finalizado != 1) {
			$_SESSION['metodo'] = $iso->iso_idmetodo;
	        $item->iso_dataesterilizacao   = $iso->iso_dataesterilizacao;
	        $item->iso_horaesterilizacao   = $iso->iso_horaesterilizacao;
	        $item->iso_datalimite          = $iso->iso_datalimite;
	        $item->iso_lote                = $iso->iso_lote;
	        $item->iso_status              = '1';
			$item->iso_idmetodo			   = $iso_idmetodo;
			$item->iso_idequipamentoet	   = $iso->iso_idequipamentoet;
	        $item->iso_nivelrpreparo       = $_SESSION['usu_nivel'];
	        $item->iso_refrpreparo         = $_SESSION['usu_referencia'];
	        echo $item->update();

	        // marca produto como "pronto"
	        if($_POST['ctx'] == 'pn'){
	        	
	        	ProdutosController::setStatus($item->iso_idproduto, '1');
	    	}else{
	    	    ProdutosConsignadoController::setStatus($item->iso_idproduto, '1');
	    	}
	        // apaga duplica��es, se houver
	        ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
	    }

	    $item2 = ItensSolicitacoesController::getItem( $iso->iso_id );
	    //echo 'status ' . $item2->iso_status;
	    // recupera idses
	    $idses = $item->iso_idses;

	    $composicao = 0;


	    // se item for composto, etiqueta todos os seus filhos inclusos na mesma solicita��o
	    if( $composto == 1){
	        $idpai = $item->iso_idproduto;
	        // array de filhos adicionados na mesma solicita��o que o pai
	        $arrAdicionados = array();
	
			foreach(ItensSolicitacoesController::getItens2("its.iso_idses = " . $idses . " AND cpro.pco_idpai = " . $idpai . " AND its.iso_status = '0'") as $ja){
	            $arrAdicionados[] = $ja->iso_idproduto;
	        }


			$item = new ItensSolicitacaoModel();
			$contagemComposto =	$item->VerificaSaidaComposto($idpai);
			// Verifica se a caixa passou pelo setor conf. de montagem, se passou somente os filhos
	        // conferidos deverão ser setados como iso_status = 1
	        $fatherInMountConf = ItensSolicitacoesController::wasItemInMountConference($idpai);
	        $fatherInMountConf = strlen($fatherInMountConf) > 0 ? true : false;


	        foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $idpai ) as $pco){

	        	if($fatherInMountConf){
	        		$filho = ItensSolicitacoesController::wasItemInMountConference($pco->pco_idfilho);
	        		$filho = strlen($filho) > 0 ? true : false;
	        		echo $filho .' ';

	        		if($filho){
	        			$status = '1';
	        		}else{
	        			$status = '1';
	        		}
	        	}
	        		
	        	
	            // se filho estiver na mesma solicita��o que pai, etiqueta filho
	            if(in_array($pco->pco_idfilho, $arrAdicionados)){
	                // s� atualiza dados se item pai n�o estiver j� finalizado
	                if ($finalizado != 1) {

	                    $item = new ItensSolicitacaoModel();
	                    $item = ItensSolicitacoesController::getItemBySolicitacaoEProduto($idses, $pco->pco_idfilho, '0');
	                    
                        $item->iso_dataesterilizacao =  date('Y-m-d') ; // DefaultHelper::converte_data($iso->iso_dataesterilizacao);
	                    $item->iso_horaesterilizacao = date("H:i:s");
	                    // $item->iso_datalimite = DefaultHelper::converte_data($iso->iso_datalimite);
                        $item->iso_datalimite = $iso->iso_datalimite;
                        
	                    $item->iso_lote = $iso->iso_lote;
	                    $item->iso_status = 1; 
						$item->iso_idmetodo	= $iso->iso_idmetodo;
						$item->iso_idequipamentoet	= $iso->iso_idequipamentoet;
	                    $item->iso_nivelrpreparo = $_SESSION['usu_nivel'];
	                    $item->iso_refrpreparo = $_SESSION['usu_referencia'];
	                    $item->update();
	                    // marca filho como "pronto"
	                    
	                    ProdutosController::setStatus($item->iso_idproduto, '1');
	                    // apaga duplica��es, se houver
	                    ItensSolicitacoesController::deleteDuplicados($item->iso_id, $item->iso_idses, $item->iso_idproduto);
	                }
	                $composicao++;
	            }
	        }


            if( $composicao == 0 ){
               

	        	//$item  = new ItensSolicitacaoModel();
	        	//$itens = $item->selectUltimaSolicitacao( $idpai );
	        	// Teste realizado no dia 23/09/2019 n�o mostra necessidade do objeto acima.
	        	 $composicao = count( $itens );
              
            }

           
	    }else{
		}

	    // verifica quantos itens ainda faltam etiquetar
	    $itens = ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_status = '0'");
	    if(sizeof($itens) > 0) {
	        // atualiza status da solicita��o: 2 (Etiquetagem)
	        $status = "2";
	    } else {
	        // atualiza status da solicita��o: 5 (Finalizada)
	        $status = "5";
	    }

	    if( $limiteUso > 0 ){
	        // grava em tb_limitesuso o �ltimo valor utilizado
	        $liu = new LimitesUsoModel();
	        $liu->limpaUltimo();
	        $liu = LimitesUsoController::getLimiteUso( $limiteUso );
	        $liu->liu_ultimo = '1';
	        $liu->update();
	    }

        // atualiza solicita��o
        $ses = new SolicitacoesModel();
        $ses = $ses->selectSolicitacao($idses);
        $ses->ses_status = $status;

        if($status == "5")
            $ses->ses_datasaida = date("Y-m-d H:i:s");
            $ses->update();
			error_log('o retorno acontece aqui'. $composicao);

 		if($contagemComposto =='404'){
			return($composicao);
		}
		else{
			return($contagemComposto);
		}
		

	}


	function calculaDataLimite( $limiteUso ){

	    // Ex: string, 30 DIAS / 2 MESES / 2 ANOS

	    $aux = split(" ", $limiteUso);
	    $qtd = $aux[0];
	    $medida = $aux[1];
		
		if($medida != "DIAS" && $medida != "MESES" && $medida != "ANOS"){
			$medida = $aux[2];
		}
		if(isset($aux[2])){
			if($medida != "DIAS" && $medida != "MESES" && $medida != "ANOS"){
				$medida = $aux[3];
			}else{
				
			}
		}	

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
 * Brothers Solu��es em T.I. � 2015
*/
?>