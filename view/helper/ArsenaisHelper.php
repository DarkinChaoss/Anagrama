<?php
	class ArsenaisHelper{
		
		public static function listaArsenais(){
			$html = "<script src='js/arsenal.js'></script>";
			foreach(ArsenaisController::getArsenais() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '6' AND usu_referencia = " . $a->ars_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->ars_nome . "' id='" . $a->ars_id . "_6'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->ars_nome . "</td>
							<td>" . $a->ars_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->ars_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->ars_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->ars_id . "');
							$('#txNome').val('" . $obj->ars_nome . "');
							$('#txContato').val('" . $obj->ars_contato . "');
						</script>";
			}
		}
		
	}
?>