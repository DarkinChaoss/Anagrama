<?php
	class OcorrenciasProdutosHelper{
		
		public static function listaOcorrenciasByProduto($idproduto){
			$html = "<table class='table table-hover'>
						<thead>
							<tr>
								<th colspan='2'>Ocorrências já registradas para esse produto</th>
							</tr>
						</thead>
						<tbody>";
			foreach(OcorrenciasProdutosController::getOcorrenciasProdutos("opr_idproduto = " . $idproduto) as $a){
				$oco = OcorrenciasController::getOcorrencia($a->opr_idocorrencia);
				$obs = ($a->opr_obs == "") ? "" : "<br><i style='font-size: 0.8em;'>" . $a->opr_obs . "</i>";
				$html .= "<tr>
							<td>" . $oco->oco_sigla . " - " . $oco->oco_nome . $obs . "</td>
							<td>" . DefaultHelper::converte_data($a->opr_data) . "</td>
						</tr>";
			}
			$html .= "	</tbody>
					</table>";
			return $html;
		}
		
	}
?>