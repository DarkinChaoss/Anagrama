<?php
	class TransferenciaEstoqueHelper{

		public static function listaItens($tes_id, $qrcode, $lote){
			$html = "";
			foreach(ItensTransferenciaController::getItensTransferencia($tes_id, $qrcode, $lote) as $isa ){	
				$verify = ProdutosCompostosController::getProdutosCompostos2(" pco_idfilho = ".$isa->isa_idproduto." ");
					
				$filho = '';
				if($isa->isa_id > 0){
					if($verify->pco_idfilho == $isa->isa_idproduto){
						$filho = 'filhocomposto';
					}else{
						$filho = 'not';
					}
					$idqte = $isa->pro_id.$isa->loteref;
					$valueqte = $isa->pro_id.'.'.$isa->loteref;
					$html .= "<tr class='".$filho."'>
								<td class='qrcode'>" . $isa->isa_qrcode . "</td>
								<td>" . $isa->isa_produto . "</td>
								<td>" . $isa->isa_setorOrigem . "</td>
								<td class='qte' value='{$valueqte}' id='{$idqte}'>" . $isa->isa_qte . "</td>
								<td width='70'>
								<div class='btn-group'  style='margin-top:px; display: flex; width:350px;'>
									<a class='btn btn-danger delete'  style='margin-right: 10px' title='Apagar' id='" . $isa->isa_id . "'>Apagar</a>
									<br>
									<a class='btn btn-edit edit' title='Editar' value= '" . $isa->isa_qrcode. "' id='". $idqte . "'>Diminuir -1</a>
								</div>
								</td>
								
							</tr>";
				}
			}
			return $html;
		}
	}
?>

<?php
/*
 * Desenvolvido por Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2013
*/
?>