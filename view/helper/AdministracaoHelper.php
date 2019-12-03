<?php
	class AdministracaoHelper{
		
		public static function listaAdministracao(){
			$html = "<script src='js/administracao.js'></script>";
			foreach(AdministracaoController::getAdministracaos() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '9' AND usu_referencia = " . $a->adm_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->adm_nome . "' id='" . $a->adm_id . "_9'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->adm_nome . "</td>
							<td>" . $a->adm_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->adm_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->adm_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->adm_id . "');
							$('#txNome').val('" . $obj->adm_nome . "');
						</script>";
			}
		}
		
	}
?>