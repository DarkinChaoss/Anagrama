<?php
	class SaidaMateriaisHelper{
		
		public static function listaSaidas(){
			$html = "<script src='js/saidaMateriais.js'></script>";
			foreach(SaidaMateriaisController::getSaidasMateriais() as $sai ){
				if($sai->sma_tiposaida == 'S'){
					if($sai->sma_ultimolancamento != ""){

						$html .= "<tr>
									<td>" . (($sai->sma_ultimolancamento != "") ? DefaultHelper::converte_data($sai->sma_ultimolancamento) : "") . "</td>
									<td>" . $sai->sma_prontuario . "</td>
									<td>" . $sai->sma_paciente . "</td>
									<td></td>
								</tr>";
					}					
				}
			}
			return $html;
		}
		
		public static function populaCamposProntuario($obj){
			if(is_null($obj)) {
				return "<script type='text/javascript'>
							$('#txData').val('" . date("d/m/Y H:i:s") . "');
						</script>";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->sma_id . "');
							$('#txPaciente').val('" . $obj->sma_paciente . "');
							$('#txSala').val('" . $obj->sma_sala . "');
							$('#slSetor').val('" . $obj->sma_idsetor . "');
							$('#slConvenio').val('" . $obj->sma_idconvenio . "');
							$('#txData').val('" . DefaultHelper::converte_data($obj->sma_data) . "');
							listaItens(" . $obj->sma_id . ");
							// só libera botão de adicionar produto na lista se a saída estiver salva
							if($('#txId').val() != ''){
								//$('#txPaciente').attr('readonly', true);
								$('#txSala').attr('readonly', true);
								$('#slSetor option').not(':selected').attr('disabled', 'disabled');
								$('#slSetor').attr('readonly', true);
								$('#slConvenio option').not(':selected').attr('disabled', 'disabled');
								$('#slConvenio').attr('readonly', true);
								$('#btSalvar').hide();
								$('#btVoltar').hide();
								$('#btFinalizar').show();
								$('#btAdProd').show();
								$('#btAdProd').focus();
							}
						</script>";
			}
		}
		
		public static function populaCamposSaidaGeral($obj){
			if(is_null($obj)) {
				return "<script type='text/javascript'>
							$('#txData').val('" . date("d/m/Y H:i:s") . "');
						</script>";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->sma_id . "');
							$('#txPaciente').val('" . $obj->sma_paciente . "');
							$('#txData').val('" . DefaultHelper::converte_data($obj->sma_data) . "');
							listaItens(" . $obj->sma_id . ");
							// só libera botão de adicionar produto na lista se a saída estiver salva
							if($('#txId').val() != ''){
								$('#btSalvar').hide();
								$('#btVoltar').hide();
								$('#btFinalizar').show();
								$('#btAdProd').show();
								$('#btAdProd').focus();
							}
						</script>";
			}
		}
		
		public static function populaComboConvenio($id = 0){
			$select = "	<select name='convenio' id='slConvenio' class='input-xlarge'>
							<option value='0'>** Escolha **</option>";
			foreach (ConveniosController::getConvenios() as $cvn){
				$select .= "<option value='".$cvn->cvn_id."'".($cvn->cvn_id == $id ? "selected='selected'" : "").">".$cvn->cvn_nome."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		
		public static function listaItensSaida($id){
			$html = "";
			foreach(ItensSaidaController::getItensSaida("isa_idsaida = " . $id) as $a){
				if(!$a->isa_consignado){
					$pro = ProdutosController::getProduto($a->isa_idproduto);
					if($pro->pro_composto == 1 || $pro->pro_qtde != 0 || $pro->pro_composto == '' || $pro->pro_qtde != 0){

						$prod = ProdutosCompostosController::getProdutosCompostosInner("pco_idfilho = " . $a->isa_idproduto);
				
						if(empty($prod)){
							$infoProduto = $pro->pro_nome
									. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "") 
									. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
									. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
									. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");

									
							$qrcodenew = explode(".",$pro->pro_qrcode);
							
							if(count($qrcodenew) == 2){
									
								$html .= "<tr>
											<td>" . DefaultHelper::converte_data($a->isa_data) . "</td>
											<td>" . $a->isa_sala . "</td>
											<td>
													" . $pro->pro_qrcode .'.'.$a->loteref. "
													<br>
													" . $infoProduto . "
													" . (
														($a->isa_obs != "")
														? "<br><small style='color: gray;'><i>" . $a->isa_obs . "</i></small>"
														: ""
													) . "
											</td>
											<td>" . $a->isa_qte . "</td>
											<td>" . $a->isa_lote . "</td>
											<td>" . DefaultHelper::converte_data($a->isa_validade) . "</td>
										<td>" . $a->isa_reuso . "</td>
										<td style='text-align: center;'><input type='checkbox' class='ckItemSaida' alt='" . $a->isa_id . "'></td>
									</tr>";	
							}else{
								$html .= "<tr>
											<td>" . DefaultHelper::converte_data($a->isa_data) . "</td>
											<td>" . $a->isa_sala . "</td>
											<td>
													" . $pro->pro_qrcode . "
													<br>
													" . $infoProduto . "
													" . (
														($a->isa_obs != "")
														? "<br><small style='color: gray;'><i>" . $a->isa_obs . "</i></small>"
														: ""
													) . "
											</td>
											<td>" . $a->isa_qte . "</td>
											<td>" . $a->isa_lote . "</td>
											<td>" . DefaultHelper::converte_data($a->isa_validade) . "</td>
										<td>" . $a->isa_reuso . "</td>
										<td style='text-align: center;'><input type='checkbox' class='ckItemSaida' alt='" . $a->isa_id . "'></td>
									</tr>";									
							}
						}else{
						}

					}
				}
			}
			return $html;
		}

		// cleverson matias
		public static function listaItensSaidaConsignados($id){
			$html = "";
			foreach(ItensSaidaController::getItensSaida("isa_idsaida = " . $id) as $a){
				if($a->isa_consignado){
					$pro = ProdutosConsignadoController::getProdutoConsignado($a->isa_idproduto);
					$infoProduto = $pro->pro_nome
							. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "") 
							. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
							. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
							. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");
					$html .= "<tr>
								<td>" . DefaultHelper::converte_data($a->isa_data) . "</td>
								<td>" . $a->isa_sala . "</td>
								<td>
										" . $pro->pro_qrcode . "
										<br>
										" . $infoProduto . "
										" . (
											($a->isa_obs != "")
											? "<br><small style='color: gray;'><i>" . $a->isa_obs . "</i></small>"
											: ""
										) . "
								</td>
								<td>" . $a->isa_lote . "</td>
								<td>" . DefaultHelper::converte_data($a->isa_validade) . "</td>
								<td>" . '' . "</td>
								<td style='text-align: center;'><input type='checkbox' class='ckItemSaida' alt='" . $a->isa_id . "'></td>
							</tr>";
				}
			}
			return $html;
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