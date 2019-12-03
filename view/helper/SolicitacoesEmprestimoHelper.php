<?php
	class SolicitacoesEmprestimoHelper{
		
		public static function listaSolicitacoesEmprestimo($where){
			$html = "";
			foreach(SolicitacoesEmprestimoController::getSolicitacoesEmprestimo($where) as $a){
				$html .= "<tr>
							<td>" . $a->mai_cod . "</td>
							<td>" . $a->mai_nome . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->mai_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->mai_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->mai_id . "');
							$('#txCod').val('" . $obj->mai_cod . "');
							$('#txNome').val('" . $obj->mai_nome . "');
						</script>";
			}
		}
		
	}
?>