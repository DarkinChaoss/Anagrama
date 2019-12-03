<?php
	class SetoresHelper{
		
		public static function listaSetores(){
			$html = "<script src='js/setores.js'></script>";
			foreach(SetoresController::getSetores() as $a){
				$html .= "<tr>
							<td>" . $a->set_nome . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->set_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->set_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->set_id . "');
							$('#txNome').val('" . $obj->set_nome . "');
							$('#slFazSolicitacao').val('" . $obj->set_fazsolicitacao . "');
						</script>";
			}
		}
		
		public static function populaComboSetores($id = 0){
			$select = "<option value='0'>** Escolha **</option>";
			foreach (SetoresController::getSetores() as $set){
				$select .= "<option value='".$set->set_id."'".($set->set_id == $id ? "selected='selected'" : "").">".$set->set_nome."</option>";
			}
			return $select;
		}
		
	}
?>