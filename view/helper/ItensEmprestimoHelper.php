<?php
	class ItensEmprestimoHelper{
		
		public static function listaItens($where){
			$html = "";
			foreach(ItensEmprestimoController::getItens("", $where) as $a){
				$debito = $a->iem_qtdeentregue - ($a->iem_qtdesujo + $a->iem_qtdesemuso);
				$sem = SolicitacoesEmprestimoController::getSolicitacaoEmprestimo($a->iem_idsem);
				$mai = MateriaisInternosController::getMaterialInterno($a->iem_idmai);
				$html .= "<tr>
							<td>" . DefaultHelper::converte_data($sem->sem_data) . "<br>" . $sem->sem_nomesolicitante . "</td>
							<td>" . $mai->mai_cod . "</td>
							<td>" . $mai->mai_nome . "</td>
							<td style='font-weight: bold; text-align: center;'>" . $debito . "</td>
							<td style='text-align: center;'>" . $a->iem_qtdeentregue . "</td>
							<td style='text-align: center;'>" . $a->iem_qtdesujo . "</td>
							<td style='text-align: center;'>" . $a->iem_qtdesemuso . "</td>
							<td></td>
						</tr>";
			}
			return $html;
		}
		
		public static function listaItensDebito($buscar, $where, $idSetor = 0){
			$html = "";
			$script = "<script>";
			$cont = 0;
			$ultimoEmprest = 0;
			foreach(ItensEmprestimoController::getItens($buscar, $where) as $a){
				$debito = $a->iem_qtdeentregue - ($a->iem_qtdesujo + $a->iem_qtdesemuso);
				if($debito > 0){
					$sem = SolicitacoesEmprestimoController::getSolicitacaoEmprestimo($a->iem_idsem);
					$mai = MateriaisInternosController::getMaterialInterno($a->iem_idmai);
					$set = SetoresController::getSetor($sem->sem_idsetor);
					if($a->iem_idsem != $ultimoEmprest)
						$separador = "style='border-top: 2px solid grey;'";
					else
						$separador = "";
					$html .= "<tr " . $separador . ">
								<td>" . DefaultHelper::converte_data($sem->sem_data) . "<br>" . $sem->sem_nomesolicitante . "</td>
								<td>" . $mai->mai_cod . "</td>
								<td>" . $mai->mai_nome . "</td>
								<td style='font-weight: bold; text-align: center;'>" . $debito . "</td>
								<td style='text-align: center;'>" . $a->iem_qtdeentregue . "</td>
								<td style='text-align: center;'>" . $a->iem_qtdesujo . "</td>
								<td style='text-align: center;'>" . $a->iem_qtdesemuso . "</td>
								<td>" . (($idSetor == 0) ? "<small>" . $set->set_nome . "</small>" : "<a class='btn btn-danger' onclick='devolucaoItem(\"" . $mai->mai_nome . "\", " . $a->iem_id . ", " . $a->iem_qtdeentregue . ", " . $a->iem_qtdesujo . ", " . $a->iem_qtdesemuso . ")'>Devolução</a>") . "</td>
							</tr>";
					$cont += $debito;
					$ultimoEmprest = $a->iem_idsem;
				}
			}
			$script .= "$('#txTotalDebitos').text('" . $cont . "');";
			if($html == ""){
				$html .= "<tr><td colspan='8'>Nenhum</td></tr>";
			}
			// script para atualização de outros valores na tela
			if($idSetor != 0){
				$set = SetoresController::getSetor($idSetor);
				$setor = "de " . $set->set_nome;
			}
			$script .= "	$('#txNomeSetor').text('" . $setor . "');
						</script>";
			return $html.$script;
		}
		
		public static function listaItensNovoEmprestimo($id){
			$html = "	<table class='table table-hover'>
							<thead>
								<tr>
									<th width='50'>Código</th>
									<th>Material</th>
									<th width='50'>Qtde.</th>
									<th width='70'></th>
								</tr>
							</thead>
							<tbody>";
			$cont = 0;
			foreach(ItensEmprestimoController::getItens("", "iem_idsem = " . $id) as $a){
				$mai = MateriaisInternosController::getMaterialInterno($a->iem_idmai);
				$html .= "		<tr>
									<td>" . $mai->mai_cod . "</td>
									<td>" . $mai->mai_nome . "</td>
									<td style='text-align: center;'>" . $a->iem_qtdeentregue . "</td>
									<td><a class='btn btn-danger remove pull-right' id='" . $a->iem_id . "'>Remover</a></td>
								</tr>";
				$cont += $a->iem_qtdeentregue;
			}
			$html .= "		</tbody>
						</table>";
			return utf8_encode($html."*;*".$cont);
		}
		
	}
?>