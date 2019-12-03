<?php
	class CirculantesHelper{
		
		public static function listaCirculantes(){
			$html = "<script src='js/circulante.js'></script>";
			foreach(CirculantesController::getCirculantes() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '7' AND usu_referencia = " . $a->cir_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->cir_nome . "' id='" . $a->cir_id . "_7'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->cir_nome . "</td>
							<td>" . $a->cir_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->cir_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->cir_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->cir_id . "');
							$('#txNome').val('" . $obj->cir_nome . "');
							$('#txContato').val('" . $obj->cir_contato . "');
						</script>";
			}
		}
		
	}
?>