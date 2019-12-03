<?php
	class ProdutosCompostosHelper{
		
		public function listaProdutosFilhosCount($idproduto, $modo, $idses = 0, $idsol = 0){
			// se idses for informado, elimina na lista os filhos já inseridos na solicitação
			if($idses != 0){
				$arrAdicionados = array();
				foreach(ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_idproduto <> " . $idproduto . " AND iso_status = '0'", "") as $ja){
					$arrAdicionados[] = $ja->iso_idproduto;
				}
			}
			
			foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $idproduto) as $a){
				if($idses == 0 || ($idses != 0 && in_array($a->pco_idfilho, $arrAdicionados) == false)){
					$pro = ProdutosController::getProduto($a->pco_idfilho);

					if($pro->pro_descarte != '*'){
						$arrTeste[] = $pro;	 
					}
				}
			}
				
			$count = count($arrTeste);
			return $count;			
		}
	
		public static function listaProdutosFilhos($idproduto, $modo, $idses = 0, $idsol = 0){ 
		// $modo determina se botão de excluir será exibido (1) ou não (2)
			if($modo == 1){$extraTh = "<th width='30'></th>";}else{$extraTh = "";}

			$html = "<table id='listProdf' class='table table-hover'>
						<thead>
							<tr>
								<th>Produto</th>
								<th width='100'>QRCode</th>
								<th width='30'></th>
								<th width='30'></th>
			                    <th width='30'></th>
			                    ".$extraTh."
							</tr>
						</thead>
						<tbody>";
			// se idses for informado, elimina na lista os filhos já inseridos na solicitação
			if($idses != 0){
				$arrAdicionados = array();
				foreach(ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_idproduto <> " . $idproduto . " AND iso_status = '0'", "") as $ja){
					$arrAdicionados[] = $ja->iso_idproduto;
				}
				

			}
			
			foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $idproduto) as $a){
				if($idses == 0 || ($idses != 0 && in_array($a->pco_idfilho, $arrAdicionados) == false)){
					$pro = ProdutosController::getProduto($a->pco_idfilho);
					
					$arrTeste[] = $pro;

					// produtos descartados não aparecem na lista
					if($pro->pro_descarte != '*'){
						if($modo == 1)
							$btRemover = "<td><a class='btn btn-danger removeFilho' id='remove_" . $a->pco_id . "' title='Remover " . $pro->pro_nome . " desta composição'><i class='icon-remove icon-white'></i></a></td>";
						else
							$btRemover = "";
						if($pro->pro_perdido == 'S')
							$btPerdido = "";
						else
							$btPerdido = "<a class='btn btn-warning filhoPerdido' id='perdido_" . $pro->pro_id . "' title='Marcar como perdido'>?</a>";
						$id_nome_produto = NomesProdutosController::getIdByName($pro->pro_nome);
						$small_img = file_exists('img_pro/pro'.$id_nome_produto.'_small.png') ? 'img_pro/pro'.$id_nome_produto.'_small.png' : 'img_pro/placeholder_small.png';

						$html .= "<tr idsol='{$idsol}' id='linhaFilho_" . $pro->pro_id . "' " . (($pro->pro_perdido == 'S') ? "class='perdido'" : "") . ">
									<td>"
										. $pro->pro_nome
										. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "")
										. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
										. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
										. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "") .
									"</td>
									<td>" . $pro->pro_qrcode . "</td>
									<td>
									<a id='".$pro->pro_id."' href='javascript:modalSubs()' class='btn btn-primary btnSubstituir configBtn' title='Substituir QrCode' href='#mdl-substituir'> <i class='icon-edit icon-white'></i> </a>
									</td>
									<td>" . $btPerdido . "</td>
									".$btRemover."
									<td>
										<img class='pro_img_kids' onclick='proKids(".$id_nome_produto.")' src='".$small_img."'>
										<div style='position:fixed; top: 8em; left: 35vw' id='show_image".$id_nome_produto."'></div>
									</td>
								</tr>";
					}
				}
			}
			// <div style='width=100%' class='pro_img_kids' onclick='proKids(".$id_nome_produto.")'><i class='fas fa-image' style='font-size:2.5em; color:#7660bb;transform: scaleY(1.1) translate(-2px, -3px) scaleX(0.9);'></i></div>
			// 							<div style='position:fixed; top: 8em; left: 35vw' id='show_image".$id_nome_produto."'></div>

				
			$count = count($arrTeste);
			
			$html .= "	</tbody>
					</table>";
			return $html;
		}


		public static function listaProdutosFilhosprint($idproduto, $modo, $idses = 0, $idsol = 0){ // $modo determina se botão de excluir será exibido (1) ou não (2)
			$html = "
			<link rel='stylesheet' href='css/bootstrap.css'>
			<link rel='stylesheet' href='css/print.css'>
			<script src='js/print.js'></script>
			<table class='table table-hover'>
						<thead>
							<tr>
								<th style='width:10px !important; padding:0 !important; margin:0 !important'>Qtde.</th>
								<th style='width:auto !important; margin:0 !important; padding:0 !important; padding-left: 16px !important'>Produto</th>
								<th width='30'></th>
								<th width='30'></th>
			                    <th width='30'></th>
							</tr>
						</thead>
						<tbody>";
			// se idses for informado, elimina na lista os filhos já inseridos na solicitação
			if($idses != 0){
				$arrAdicionados = array();
				foreach(ItensSolicitacoesController::getItens("iso_idses = " . $idses . " AND iso_idproduto <> " . $idproduto . " AND iso_status = '0'", "") as $ja){
					$arrAdicionados[] = $ja->iso_idproduto;
				}
			}

			$nomes_produtos = array();
			
			foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $idproduto) as $a){
				if($idses == 0 || ($idses != 0 && in_array($a->pco_idfilho, $arrAdicionados) == false)){
					$pro = ProdutosController::getProduto($a->pco_idfilho);
					array_push($nomes_produtos, $pro->pro_nome);
					$indexes = array_keys($nomes_produtos, $pro->pro_nome); //array(0, 1)

					// quantidade de vezes que esse nome de produto aparece na caixa
					$count = ProdutosCompostosController::getRepetition($pro->pro_id, $a->pco_idpai);

					error_log('clever ' . $count);
					if(count($indexes) > 1){
						continue;
					}

					error_log(print_r($count));
					// produtos descartados não aparecem na lista
					if($pro->pro_descarte != '*'){
						if($modo == 1)
							$btRemover = "";
						else
							$btRemover = "";
						if($pro->pro_perdido == 'S')
							$btPerdido = "";
						else
							$btPerdido = "";

						$html .= "<tr style='border:none !important;' idsol='{$idsol}' id='linhaFilho_" . $pro->pro_id . "' " . (($pro->pro_perdido == 'S') ? "class='perdido'" : "") . ">
									<td style='width:10px !important; border:none !important; font-size:10px !important; padding: 0 !important; margin:0 !important'>" . $count . "</td>
									<td style='width:auto !important; border:none !important; font-size:7pt !important; padding: 0 !important; padding-left: 16px !important'; margin:0 !important'>"

										. $pro->pro_nome
										. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "")
										. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
										. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
										. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "") .
									"</td>
									<td style='border:none !important; font-size:8px !important; padding: 0 !important; margin:0 !important'>
									</td>
									<td style='border:none !important; font-size:8px !important; padding: 0 !important; margin:0 !important'>" . $btPerdido . "</td>
									<td style='border:none !important; font-size:8px !important; padding: 0 !important; margin:0 !important'>" . $btRemover . "</td>
								</tr>";
					}
				}
			}
			$html .= "	</tbody>
					</table>";
			return $html;
		}

	}
?>