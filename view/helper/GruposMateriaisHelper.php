<?php
	class GruposMateriaisHelper{
		
		public static function listaGruposMateriais(){
			$html = "<script src='js/gruposMateriais.js'></script>";
			foreach(GruposMateriaisController::getGruposMateriais() as $a){
				$html .= "<tr>
							<td>" . $a->gma_nome . "</td>
							<td>" . $a->gma_obs . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->gma_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->gma_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->gma_id . "');
							$('#txNome').val('" . $obj->gma_nome . "');
							$('#txObs').val('" . $obj->gma_obs . "');
						</script>";
			}
		}
		
	}
?>