<?php
	if(isset($_POST['cleanUnfinished'])){
		SaidaMateriaisController::cleanUnfinished($_POST['cleanUnfinished']);
		exit;
	}
	

	// busca produto para troca de estoque

	if($_POST['action'] == "Remove_from_transf"){
		$id_in_solicitacao = $_POST['id_in_solicitacao'];
		$prod_id = ItensSolicitacoesController::getProdId($id_in_solicitacao);
		$delete = TransferenciaEstoqueController::deleteLastEntry($prod_id);
		print_r($delete);
	}


	if ($_POST['acao'] == "buscar"
		&& isset($_POST['qrcode'])
		&& (!empty($_POST['qrcode']) || $_POST['qrcode'] != "" || $_POST['qrcode'] != " ")){
		$iso = new ItensSaidaModel();
		if($_POST['reflote'] ==''){
			$teste = new ItensSaidaModel();
			$prod = $teste->verificaProd($_POST['qrcode']);
			
			
			
			
			if ($_POST['verifica_filhotrans'] === 'transferencia_sistema') {

				$prof = ProdutosController::getProdutoByQrCode($_POST['qrcode']);
				$setor = SetoresController::getCmeId();
				$id_pai = ProdutosCompostosController::getFatherId($prof->pro_id);
				// $SetorOrigem_b = ItensTransferenciaController::getSetorOrigin($id_pai);				
				// $SetorOrigem_b = $SetorOrigem_b == '' ? 0 : $SetorOrigem_b;

				$arrDadosItenFilho = array(
					"smaID"=> ItensTransferenciaController::getLastSmaIdFromFather($id_pai),
					"idPro"=> $prof->pro_id,
					"idSetorDestino"=> $setor,
					"idSetor"=> ItensTransferenciaController::getSetorOrigin($prof->pro_id),//último setor do
					"loteref"=> $_SESSION['loteRef']
				);
				if($arrDadosItenFilho['idPro'] != ''){
					ItensTransferenciaController::insert($arrDadosItenFilho);
				}
				
				die('ok');
			}else{

				//$salTransIten = ItensTransferenciaController::insert($arrDadosIten);					
			}				
		

			if ($_POST['verifica_filhotrans'] === 'produtopai_sistema') {

			} else{
				if($prod['pro_status'] == 0){
					die('PRODNOT');
				}		
			}
		}
			/* 1 - achar produto
			* 2 - verifiar se item nao esta esterilizado, ou foi descartado, ou validade da esterilizacao...
			* 3 - Verificar se produto j? nao se encontra nesta tranza??o ou alguma que ainda nao tenhasido finalizada
			* 4 - Gravar na tabela tmsd_transferenciaestoque
			* 5 - Gravar Produto na tmsd_itenstranferenciaestoque
			* 6 - exibir na listagem */
			$pro = ProdutosController::getProdutoParaSolicitacao($_POST['qrcode']);
			$loteref = $_POST['reflote'];
			$setor = $_POST['setor'];

			if (!empty($pro) && $pro->pro_id > 0){
				//$descarte = OcorrenciasProdutosController::getDescarteByProduto($pro->pro_id);
				if ($pro->pro_descarte != '*'){ //verificar a necessidade de analisar a tabela de ocorrencias....
					//verificar se est? pronto para uso. se nao estiver nao permite troca de estoque
					$iso = new ItensSolicitacaoModel();
					$iso = ItensSolicitacoesController::getUltimoReprocDeItem($pro->pro_id);
					$validade = DefaultHelper::converte_data($iso->iso_datalimite);
					$venceu = (($iso->iso_datalimite < date("Y-m-d") && $iso->iso_datalimite != "") ? "S" : "N");
					

					if(($pro->pro_status == 0 && $pro->pro_reuso == 0 && $iso->iso_nreuso == ''  )
						|| ($pro->pro_status == 1 && $pro->pro_reuso != '' && $iso->iso_nreuso != '') || $pro->pro_prontos != ''){

						//produto nao esta descartado verificar se validade da esterilizacao venceu...
						if ($venceu == "N"){
							$IntISA_ = ItensTransferenciaController::getItemTransferenciaAberta("isa_idproduto = ".$pro->pro_id); //qtd do item em solicitacoes aberta
							if ($IntISA_ == 0){
								
								//verificar aqui se o produto tem o tesID ou n?o e se n?o tem pula para baixo e cadastra s? o produto
								if ($_POST['tesID'] == 0){

									$smaTrans = TransferenciaEstoqueController::insert();	
									if ($smaTrans > 0){

										//salvar em tabela itens...., procurar ultimo setor que o produto se encontra
										/* 1 - Verificar qual solicitacao de esterilizacao mais nova
										* 2 - Verificar qual ? a sa?da mais nova
										* 3 - Compara as datas e pegar o id do setor de quem for o mais novo */
										$idSes = SolicitacoesController::getMaiorSolicitacaoProd($pro->pro_id);
										$objSes = SolicitacoesController::getSolicitacao($idSes);

									
										$intDataSes = strtotime($objSes->ses_dataentrada);
										

										$idSma = SaidaMateriaisController::getMaiorSaidaProd($pro->pro_id);
										$objSma = SaidaMateriaisController::getSaidaMateriais($idSma);

										
										$IntDataSma = strtotime($objSma->sma_ultimolancamento);

										
										//verificar se veio data de saida, se nao veio, pegar direto a de esteriliacao
										$SetorOrigem = ItensTransferenciaController::getSetorOrigin($pro->pro_id);
								
										$SetorOrigem = $SetorOrigem == '' ? 0 : $SetorOrigem;

										//$proII = ProdutosController::getidSetor($pro->pro_id);
										
										//+++++++++++++++++++++++++ TRANSFERÊNCIA MANUAL +++++++++++++++++++++++++++++++++++
										
											if ($loteref != ''){
		
												$arrDadosIten = array(	"smaID"=>$smaTrans,
												"idPro"=>$pro->pro_id,
												"idSetor"=>$setor,
												"loteref"=>$loteref,
												"reuso"=>($iso->iso_nreuso == '' ? 0 : $iso->iso_nreuso));
											}
											else{
												
												$arrDadosIten = array(	
												"smaID"=>$smaTrans,
												"idPro"=>$pro->pro_id,
												"idSetor"=>$SetorOrigem,
												"idSetorDestino" => $SetorOrigem,
												"loteref"=>$loteref,
												"reuso"=>($iso->iso_nreuso == '' ? 0 : $iso->iso_nreuso));
											}

											if ($_POST['verifica_filhotrans'] === 'produtopai_sistema') {

												// transferêncial automática
												$arrDadosIten = array(	
												"smaID"=>$smaTrans,
												"idPro"=>$pro->pro_id,
												"idSetor"=>$SetorOrigem,
												"idSetorDestino" => SetoresController::getCmeId(),
												"loteref"=>$loteref,
												"reuso"=>($iso->iso_nreuso == '' ? 0 : $iso->iso_nreuso));
												
												ItensTransferenciaController::insert($arrDadosIten);
											}else{
												
												// transferência manual
												$salTransIten = ItensTransferenciaController::insert($arrDadosIten);
											}

											
										
											////////////////////////////////////////////////////////////////////////////
											// $arr_prod_ids = ItensSolicitacoesController::getItensFilhos($pro->pro_id);
											// foreach ($arr_prod_ids as $pro_id_filho) {
											// 	$arrDadosItenf = array(	
											// 	"smaID"=>$smaTrans,
											// 	"idPro"=>$pro_id_filho,
											// 	"idSetor"=>$SetorOrigem,
											// 	"idSetorDestino" => 0,
											// 	"loteref"=>$loteref,
											// 	"reuso"=>($iso->iso_nreuso == '' ? 0 : $iso->iso_nreuso));

											// 	 ItensTransferenciaController::insert($arrDadosItenf);
											// }

											////////////////////////////////////////////////////////////////////////////
											
									
										if ($salTransIten > 0){

											if ($pro->pro_composto == "1"){

												//salvando produtos filhos
												//$aFilhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = ".$pro->pro_id);

												// Buscar dos itens da solicitação os filhos que estiverem
												// estatus = 1 para este pai.
												$aFilhos = ItensSaidaController::getSonsByFather($pro->pro_id); 

												
												//Corrigir isso aqui pq esta dando erro quando transfere por transferencia normal
												foreach($aFilhos as $filho){
													
													//verifica se filho ja nao entrou na lista...
													$IntISA_ = ItensTransferenciaController::getItemTransferenciaAberta("isa_idproduto = ".$filho);
													if ($IntISA_ == 0){

														//VERIFICAR OS FILHOS SE NAO TEM DESCARTE E/OU NAO ESTAO PRONTO PARA USO e/ou esta com ocorrencia de perdido...
														$proFilho = ProdutosController::getProduto($filho);
														$descarte = OcorrenciasProdutosController::getDescarteByProduto($proFilho->pro_id);
														if ($descarte == 0){

															//verificar se est? pronto para uso, validade e numero de reuso...
															$isof = new ItensSolicitacaoModel();

															$isof = ItensSolicitacoesController::getUltimoReprocDeItem($filho);
															$validade = DefaultHelper::converte_data($isof->iso_datalimite);
															$venceuF = (($isof->iso_datalimite < date("Y-m-d") && $isof->iso_datalimite != "") ? "S" : "N");
															if(($proFilho->pro_status == 0 && $isof->iso_nreuso == ''  ) || ($proFilho->pro_status == 1 && $isof->iso_nreuso != '')){
																//produto nao esta descartado verificar se validade da esterilizacao venceu...
																if ($venceuF == "N"){
																	$idSes = SolicitacoesController::getMaiorSolicitacaoProd($proFilho->pro_id);
																	$objSes = SolicitacoesController::getSolicitacao($idSes);
																	$intDataSes = strtotime($objSes->ses_dataentrada);
																	$idSma = SaidaMateriaisController::getMaiorSaidaProd($proFilho->pro_id);
																	$objSma = SaidaMateriaisController::getSaidaMateriais($idSma);
																	$IntDataSma = strtotime($objSma->sma_ultimolancamento);
																	//verificar se veio data de saida se nao veio pegar direto a de esteriliacao
																	if ($intDataSes > $IntDataSma){
																		$SetorOrigemFilho = $objSes->ses_idsetor;
																	}else{
																		$SetorOrigemFilho = $objSma->sma_idsetor;
																	}

																	$arrDadosIten = array("smaID"=>$smaTrans,
																						"idPro"=>$filho,
																						"idSetor"=>$SetorOrigemFilho,
																						"idSetorDestino" => $SetorOrigemFilho,
																						"reuso"=>($isof->iso_nreuso == '' ? 0 : $isof->iso_nreuso));
																	$salTransIten = ItensTransferenciaController::insert($arrDadosIten);
																	if ($salTransIten <= 0){
																		die("ERROIX");//NAO FOI POSSIVEL SALVAR FILHOS DE PRODUTO COMPOSTO
																	}
																}
															}
														}
													}
												}//fim do foreach
											}
											die("$smaTrans"); //TUDO CERTO NA PRIMEIRA INSERCAO
										}else{
											die("$smaTrans"); //NAO FOI POSSIVEL SALVAR ITEM NA SOLICITACAO DE TRANSFERENCIA
										}
									}else{
										die('ERROVII');//NAO FOI POSSIVEL INICIAR (SALVAR) TRANSFERENCIA
									}
								}else if ($_POST['tesID'] > 0){

									//salvar apenas produto

									$idSes = SolicitacoesController::getMaiorSolicitacaoProd($pro->pro_id);
									$objSes = SolicitacoesController::getSolicitacao($idSes);
									$intDataSes = strtotime($objSes->ses_dataentrada);
									$idSma = SaidaMateriaisController::getMaiorSaidaProd($pro->pro_id);
									$objSma = SaidaMateriaisController::getSaidaMateriais($idSma);
									$IntDataSma = strtotime($objSma->sma_ultimolancamento);
									//verificar se veio data de saida se nao veio pegar direto a de esteriliacao
									if ($intDataSes > $IntDataSma){
										$SetorOrigem = $objSes->ses_idsetor;
									}else{
										$SetorOrigem = $objSma->sma_idsetor;
									}

									//$proII = ProdutosController::getidSetor($pro->pro_id);
									if ($loteref != ''){
										$arrDadosIten = array("smaID"=>$_POST['tesID'],"idPro"=>$pro->pro_id,"idSetor"=>$setor, "loteref"=>$loteref);
									}
									else{
										$arrDadosIten = array("smaID"=>$_POST['tesID'],"idPro"=>$pro->pro_id,"idSetor"=>$SetorOrigem, "loteref"=>$loteref);
									}

									
								$salTransIten = ItensTransferenciaController::insert($arrDadosIten);

									
									if ($salTransIten > 0){
										//error_log("retornando " . $salTrans . ", " . $salTransIten);
										if ($pro->pro_composto == "1"){
											//salvando produtos filhos
											$aFilhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = ".$pro->pro_id);
											foreach($aFilhos as $filho){
												//verifica se filho ja nao entrou na lista...
												$IntISA_F = 0;
												$IntISA_F = ItensTransferenciaController::getItemTransferenciaAberta("isa_idproduto = ".$filho->pco_idfilho);
												if ($IntISA_F == 0){
													//VERIFICAR OS FILHOS SE NAO TEM DESCARTE E/OU NAO ESTAO PRONTO PARA USO e/ou esta com ocorrencia de perdido...
													$proFilho = ProdutosController::getProduto($filho->pco_idfilho);
													$descarte = OcorrenciasProdutosController::getDescarteByProduto($proFilho->pro_id);
													if ($descarte == 0){
														//verificar se est? pronto para uso, validade e numero de reuso...
														$isof = new ItensSolicitacaoModel();
														$isof = ItensSolicitacoesController::getUltimoReprocDeItem($filho->pco_idfilho);
														$validade = DefaultHelper::converte_data($isof->iso_datalimite);
														$venceuF = (($isof->iso_datalimite < date("Y-m-d") && $isof->iso_datalimite != "") ? "S" : "N");
														if(($proFilho->pro_status == 0 && $isof->iso_nreuso == ''  ) || ($proFilho->pro_status == 1 && $isof->iso_nreuso != '')){
															//produto nao esta descartado verificar se validade da esterilizacao venceu...
															if ($venceuF == "N"){
																$idSes = SolicitacoesController::getMaiorSolicitacaoProd($proFilho->pro_id);
																$objSes = SolicitacoesController::getSolicitacao($idSes);
																$intDataSes = strtotime($objSes->ses_dataentrada);
																$idSma = SaidaMateriaisController::getMaiorSaidaProd($proFilho->pro_id);
																$objSma = SaidaMateriaisController::getSaidaMateriais($idSma);
																$IntDataSma = strtotime($objSma->sma_ultimolancamento);
																//verificar se veio data de saida se nao veio pegar direto a de esteriliacao
																if ($intDataSes > $IntDataSma){
																	$SetorOrigemFilho = $objSes->ses_idsetor;
																}else{
																	$SetorOrigemFilho = $objSma->sma_idsetor;
																}

																$arrDadosIten = array("smaID"=>$_POST['tesID'],
																					"idPro"=>$filho->pco_idfilho,
																					"idSetor"=>$SetorOrigemFilho,
																					"reuso"=>($isof->iso_nreuso == '' ? 0 : $isof->iso_nreuso));
																$salTransIten = ItensTransferenciaController::insert($arrDadosIten);
																if ($salTransIten <= 0){
																	die("ERROIX");//NAO FOI POSSIVEL SALVAR FILHOS DE PRODUTO COMPOSTO
																}
															}
														}
													}
												}
											} //FIM DO FOREACH
										} //FIM DO PRODUTO COMPOSTO
										die("".$_POST['tesID'].""); //TUDO CERTO
									}else{
										die("$smaTrans"); //ERRO AO INSERIR ITEN DA TRANSFERENCIA
									}
								}/*else{
									die('ERROVI'); //ID DA SOLICITACAO NAO ENCONTRADO RESERVADO... POIS ID ZERO OU VAZIO ? A MESMA COISA
								}*/
							}else{
								die('ERROV'); //PRODUTO JA SE ENCONTRA EM UMA TRANSFERENCIA NAO FINALIZADA
							}
						/*-- */
						}else{
							die("ERROIV");	//PRAZO DE VALIDADE EXPIRADA
						}
					}else{
						die("ERROIII");  //PRODUTO NAO PRONTO PARA USO
					}
				}else{
					die("ERROII"); //PRODUTO DESCARTADO
				}
			} else {
				die("ERRO"); //PRODUTO NAO ENCONTRADO
			}
	}

	// lista produto para transferencia
	if ($_POST['acao'] == "listarItens" && isset($_POST['tesID'])){
        $html = TransferenciaEstoqueHelper::listaItens($_POST['tesID'], $_POST['qrcode'], $_POST['reflote']);
        die($html);
	}

	if(isset($_GET['delete'])){
		error_log("chamando Control...");
		if(ItensTransferenciaController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}

	if ($_POST['acao'] == 'transferir'){


		if(TransferenciaEstoqueController::updateSetorDestino($_POST)){
			 if (ItensTransferenciaController::updateSetDestino($_POST)){
				die('OK');

			}else{
				die("ERRO2");
			}
		}else{
			die("ERRO1");
		}
	}

	if ($_POST['acao'] == 'updateqte'){
		$iso = new ItensSaidaModel();

		$newqte = $iso->updateQte($_POST['tesID'],  $_POST['loteref'], $_POST['pro_id'], $_POST['qte'], $_POST['setor'], $_POST['saida'], $_SESSION['usu_masterclient']);
		die($newqte);
	}
	
	
	if ($_POST['acao'] == 'getQte'){
		$iso = new ItensSaidaModel();
		$qte = $iso->getQte( $_POST['reflote'], $_POST['qrcode'], $_POST['setor'],  $_SESSION['usu_masterclient']);

		if($qte =='404'){
			die('404');
		}
		else{
			$json = json_encode($qte);
			die($json);
		}
	}

	if ($_POST['acao'] == 'verificaqte'){
		$iso = new ItensSaidaModel();
		$newqte = $iso->verificaqte($_POST['tesID'],  $_POST['reflote']);
		$json = json_encode($newqte);
		die($json);
	}

	if ($_POST['acao'] == 'verificaProd'){
		$iso = new ItensSaidaModel();
		$prod = $iso->verificaProd($_POST['qrcode']);
		$json = json_encode($prod);
		die($json);
	}

	if ($_POST['acao'] == 'getcombo'){
		$iso = new ItensSaidaModel();
		$newqte = $iso->getcombo($_POST['qrcode'],  $_POST['reflote'], $_SESSION['usu_masterclient']);
		$json = json_encode($newqte);
		die($json);
	}

	if ($_POST['acao'] == 'Verificatrans'){
		$iso = new ItensSaidaModel();
		$res = $iso->verificaSaida($_POST['qrcode'], $_POST['reflote']);
		if ($res == 0){
		  die("0"."*;*");
		} 
		elseif($res == 1){
			die("1"."*;*");
		}
	}


	if ($_POST['acao'] == 'criar' && $_POST['setID'] > 0){
		// utilizado por solicitacao de material [solicitacoes.js]...
		$trans = TransferenciaEstoqueController::insert();
		if ($trans>0){
			
			$dados = array("tesID"=>$trans,"setID"=>$_POST['setID']);
			$upTrans = TransferenciaEstoqueController::updateSetorDestino($dados);
			if($upTrans){
				error_log("auterou o destino ");
				die("$trans");
			}else{
				die('ERRO');
			}
		}else{
			die('ERRO');
		}
	}

?>