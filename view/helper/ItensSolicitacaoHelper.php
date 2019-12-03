<?php
	class ItensSolicitacaoHelper{

        public static function listaItensSolicitacao($id_ses){
			$html = "";
			$cont = 0;
			foreach(ItensSolicitacoesController::getItensTelaSolicitacao($id_ses) as $iso){
				
				$cont ++;						
				
				// botão para produto composto
				if($iso->iso_pro_composto && $iso->iso_ses_status < 2){

					// se for composto tem q pegar o id do item de solicitacao dessa caixa o ultimo ainda pendente desta solicitacao

					$btComposto = "<a href='#telaListaProdutos' class='btn compor' title='Compor {$iso->iso_pro_nome}' 
									onclick='comporPai({$iso->iso_idproduto} , {$iso->iso_id} )' data-toggle='modal'>
										<i class='icon-inbox'></i>
									</a>";
				}
				else
					$btComposto = "";
				//
				$detail = '"'.$iso->pro_detailproduct.'"';
				
				if($iso->pro_detailproduct){
					$btDetail = "<a href='#telaDetail' class='btn' title='Detalhe do produto' 
									onclick='comporDetail({$iso->iso_idproduto})' data-toggle='modal'>
										<i class='fas fa-exclamation'></i>
									</a>";
				}else{
					$btDetail = "";
				}
				
				$infoProduto = $iso->iso_pro_nome
								. (($iso->iso_pro_calibre != "") ? ", " . $iso->iso_pro_calibre : "")
								. (($iso->iso_pro_curvatura != "") ? ", " . $iso->iso_pro_curvatura : "")
								. (($iso->iso_pro_comprimento != "") ? ", " . $iso->iso_pro_comprimento : "")
								. (($iso->iso_pro_diametrointerno != "") ? ", " . $iso->iso_pro_diametrointerno : "");

								
				if($iso->iso_pro_qtde < 1){
					$iso->iso_pro_qtde = 1;
				} 
				

				$html .= "	<tr id=".$iso->iso_pro_qrcode.">
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode.">" . $iso->iso_pro_qrcode. "</td>
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode.">" . $infoProduto . "</td>
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode.">" . $iso->iso_pro_qtde . "</td>
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode.">" . DefaultHelper::converte_data( $iso->iso_data ) . "</td>
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode.">" . $btDetail . "</td>
								<td style='background:".$iso->color."' class=".$iso->iso_pro_qrcode." id='Removeprod'>" . $btComposto . "<a class='btn btn-danger remove pull-right' id='" . $iso->iso_id . "'>Remover</a></td>
							</tr>";
			}
			if($html == ""){
				$html = "<tr><td colspan='8'>Nenhum item solicitado.</td></tr>";
			}
			return $html . "*;*" . $cont;
		}

        public static function listaItensSolicitacaoConsignados($id_ses){
			$html = "";
			$cont = 0;
			foreach(ItensSolicitacoesController::getItensTelaSolicitacaoConsignado($id_ses) as $iso){
				
				
				$cont ++;

				// botão para produto composto
				if($iso->iso_pro_composto && $iso->iso_ses_status < 2){

					// se for composto tem q pegar o id do item de solicitacao dessa caixa o ultimo ainda pendente desta solicitacao

					$btComposto = "<a href='#telaListaProdutos' class='btn compor' title='Compor {$iso->iso_pro_nome}' 
									onclick='comporPai({$iso->iso_idproduto} , {$iso->iso_id} )' data-toggle='modal'>
										<i class='icon-inbox'></i>
									</a>";
				}
				else
					$btComposto = "";

				//
				$detail2 = '"'.$iso->pro_detailproduct.'"';
				
				if($iso->pro_detailproduct){
					$btDetail = "<a href='#telaDetailcomposto' class='btn' title='Detalhe do produto composto' 
									onclick='comporDetail2({$iso->iso_idproduto})' data-toggle='modal'>
										<i class='fas fa-exclamation'></i>
									</a>";
				}else{
					$btDetail = "";
				}
								
				
				$infoProduto = $iso->iso_pro_nome
								. (($iso->iso_pro_calibre != "") ? ", " . $iso->iso_pro_calibre : "")
								. (($iso->iso_pro_curvatura != "") ? ", " . $iso->iso_pro_curvatura : "")
								. (($iso->iso_pro_comprimento != "") ? ", " . $iso->iso_pro_comprimento : "")
								. (($iso->iso_pro_diametrointerno != "") ? ", " . $iso->iso_pro_diametrointerno : "");
				$html .= "	<tr class='count-consignados'>
								<td>" . $iso->iso_pro_qrcode . "</td>
								<td>" . $infoProduto . ' <b>Qtde: ' . $iso->iso_pro_qtde . "</b></td>
								<td>" . DefaultHelper::converte_data( $iso->iso_data ) . "</td>
								<td>" . $btDetail . "</td>
								<td>" . $btComposto . "<a class='btn btn-danger remove pull-right' id='" . $iso->iso_id . "'>Remover</a></td>
							</tr>";
			}
			if($html == ""){
				$html = "<tr><td colspan='8'>Nenhum item solicitado.</td></tr>";
			}
			return $html . "*;*" . $cont;
		}
		
		
		public static function listaItensSolicitacaoEtiquetagem($id_ses){
			$html = "";
			foreach(ItensSolicitacoesController::getItens("iso_idses = " . $id_ses) as $a){
				$pro = ProdutosController::getProduto($a->iso_idproduto);
				if($a->iso_status != '0') {
					$ok = "style = 'background: #adfe9d;'";
					$okImg = "<i class='icon-ok'></i>";
				} else {
					$ok = "";
					$okImg = "";
				}
				$html .= "<tr>
							<td $ok>" . $pro->pro_qrcode . "</td>
							<td $ok>" . $pro->pro_nome . "</td>
							<td $ok>$okImg</td>
						</tr>";
			}
			return $html;
		}

		public static function listaItensEtiquetagem($search){
			$html = "";
			foreach(ItensSolicitacoesController::getItensEtiquetagem($search) as $iso){
				/////$pro = ProdutosController::getProduto($iso->iso_idproduto);
				/////$ses = SolicitacoesController::getSolicitacao($iso->iso_idses);
				/////$set = SetoresController::getSetor($ses->ses_idsetor);
				$html .= "<tr>
							<td>" . $iso->iso_pro_qrcode . "</td>
							<td class='qrcode'>" . $iso->iso_pro_nome . "</td>
							<td class='qte'>" . $iso->iso_qte . "</td>
							<td>" . DefaultHelper::converte_data($iso->iso_data) . "</td>
							<td style='display:flex !important; align-itens: center;'>
								"/*<p class='btn' title='Etiquetar' onclick='etiquetabtn(" . '"' .$iso->iso_pro_qrcode. '"' . ")'><i class='fas fa-tag'></i></p>"*/."
								<label class='control control-checkbox' style='margin: 4px; margin-left: 14px;'>
								<button class='btn bt-sm btn-success' value='".$iso->iso_pro_qrcode ."'><i class='fa printer'></i> Imprimir</button>
        						</label>
							</td>
						</tr>";
			}
			if($html == ""){
				$html = "<tr><td colspan='5'>Nenhum item solicitado.</td></tr>";
			}
			return $html;
		}

		// cleverson matias -> lista itens consignados da etiquetagem
		public static function listaItensEtiquetagemConsignados(){
			$html = '';
			foreach(ItensSolicitacoesController::getItensEtiquetagemConsignados() as $iso){
				/////$pro = ProdutosController::getProduto($iso->iso_idproduto);
				/////$ses = SolicitacoesController::getSolicitacao($iso->iso_idses);
				/////$set = SetoresController::getSetor($ses->ses_idsetor);
					$html .= "<tr class='count-consignados-etiquetagem'>
								<td>" . $iso->iso_pro_qrcode . "</td>
								<td>" . $iso->iso_pro_nome . "</td>
								<td>" . DefaultHelper::converte_data($iso->iso_data) . "</td>
								<td><p class='btn' title='Etiquetar' onclick='etiquetabtn(" . '"' .$iso->iso_pro_qrcode. '","pc"' . ")'><i class='fas fa-tag'></i></p></td>
							</tr>";
				
			}
			if($html == ""){
				$html = "<tr><td colspan='5'>Nenhum item solicitado.</td></tr>";
			}
			return $html;
		}		
		
		// Monta Lista de itens - Etiquetagem -> Reimpressão
		public static function listaItensSolicitacaoEtiquetagemReimpressao($QrCode){
		    $arrPros = ProdutosController::getProdutos( 'pro_qrcode = "'. $QrCode .'" ');
		    $pro = $arrPros[0];
		    $isos = ItensSolicitacoesController::getItens( 'iso_idproduto = ' . $pro->pro_id, 'iso_nreuso' );

		    $html = '';
		    foreach( $isos as $iso){
		        $data = DefaultHelper::converte_data($iso->iso_datalimite);

		        $html .= '
                    <tr>
                        <td width="97"> '.$iso->iso_lote.' </td>
                        <td width="50"> '.$iso->iso_nreuso.' </td>
                        <td width="90"> '.DefaultHelper::converte_data($iso->iso_dataesterilizacao).' </td>
                        <td width="90"> '.( $data == '-' ? '<input name="dataLimite[]" type="text" class="data" style="width:75px;">' : $data ).' </td>
                        <td width="15"> <a href="#" id="'.$iso->iso_id.'" class="btReimprimir"> <i class="icon-print"> </i> </a> </td>
                    </tr>
                ';
		    }

		    if( sizeof($isos) == 0 ){
		        $html = ' <tr> <td colspan="5" width="497px"> Nenhum registro encontrado ! </td> </tr> ';
		    }

		    die( utf8_encode( $html ) ); // Evitar problemas de acentuação
		}
	}
?>