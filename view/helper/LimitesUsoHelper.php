<?php
	class LimitesUsoHelper{
		
		public static function listaLimitesUso(){
			$html = "<script src='js/limitesUso.js'></script>";
			foreach(LimitesUsoController::getLimitesUso() as $a){
				$html .= "<tr>
							<td>" . $a->liu_description . ' ' . $a->liu_qtde . ' ' . $a->liu_periodo ."</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->liu_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->liu_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->liu_id . "');
							$('#txDescricao').val('" . $obj->liu_description . "');
							$('#txQtde').val('".$obj->liu_qtde."');
							$('#slMedida').val('". $obj->liu_periodo ."');
						</script>";
			}
		}
		
	}
?>