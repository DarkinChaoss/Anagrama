<?php
	class TurnosTrabalhoHelper{
		
		public static function listaTurnosTrabalho(){
			$html = "";
			foreach(TurnosTrabalhoController::getTurnosTrabalho() as $a){
				$html .= "<tr>
							<td>" . $a->tur_nome . "</td>
							<td>" . $a->tur_inicio . "</td>
							<td>" . $a->tur_fim . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->tur_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->tur_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->tur_id . "');
							$('#txNome').val('" . $obj->tur_nome . "');
							$('#txInicio').val('" . $obj->tur_inicio . "');
							$('#txFim').val('" . $obj->tur_fim . "');
						</script>";
			}
		}
		
	}
?>