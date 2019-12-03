<?php
	class EtiquetadoresHelper{
		
		public static function listaEtiquetadores(){
			$html = "<script src='js/etiquetadores.js'></script>";
			foreach(EtiquetadoresController::getEtiquetadores() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '3' AND usu_referencia = " . $a->eti_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->eti_nome . "' id='" . $a->eti_id . "_3'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->eti_nome . "</td>
							<td>" . $a->eti_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->eti_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->eti_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->eti_id . "');
							$('#txNome').val('" . $obj->eti_nome . "');
							$('#txContato').val('" . $obj->eti_contato . "');
						</script>";
			}
		}
		
	}
?>