<?php
	class ConferentesHelper{
		
		public static function listaConferentes(){
			$html = "<script src='js/conferentes.js'></script>";
			foreach(ConferentesController::getConferentes() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '2' AND usu_referencia = " . $a->cnf_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->cnf_nome . "' id='" . $a->cnf_id . "_2'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->cnf_nome . "</td>
							<td>" . $a->cnf_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->cnf_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->cnf_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->cnf_id . "');
							$('#txNome').val('" . $obj->cnf_nome . "');
							$('#txContato').val('" . $obj->cnf_contato . "');
						</script>";
			}
		}
		
	}
?>