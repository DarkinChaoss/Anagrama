<?php
	class MetodosHelper{
		
		public static function listaMetodos(){
			$html = "<script src='js/metodos.js'></script>";
			foreach(MetodosController::getMetodos() as $a){
				$html .= "<tr>
							<td>" . $a->met_nome . "</td>
							<td>" . $a->met_descricao . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->met_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->met_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->met_id . "');
							$('#txNome').val('" . $obj->met_nome . "');
							$('#txDescricao').val('" . $obj->met_descricao . "');
						</script>";
			}
		}
		
	}
?>