<?php
	class ConveniosHelper{
		
		public static function listaConvenios(){
			$html = "<script src='js/convenios.js'></script>";
			foreach(ConveniosController::getConvenios() as $a){
				$html .= "<tr>
							<td>" . $a->cvn_nome . "</td>
							<td>" . $a->cvn_obs . "</td>
							<td>" . $a->cvn_ativo . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->cvn_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->cvn_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->cvn_id . "');
							$('#txNome').val('" . $obj->cvn_nome . "');
							$('#txObs').val('" . $obj->cvn_obs . "');
							$('#slAtivo').val('" . $obj->cvn_ativo . "');
						</script>";
			}
		}
		
	}
?>