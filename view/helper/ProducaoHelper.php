<?php
	class ProducaoHelper{
		
		public static function listaProducao(){
			$html = "<script src='js/producao.js'></script>";
			foreach(ProducaoController::getProducaos() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '8' AND usu_referencia = " . $a->pcao_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->pcao_nome . "' id='" . $a->pcao_id . "_8'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				$html .= "<tr>
							<td>" . $a->pcao_nome . "</td>
							<td>" . $a->pcao_contato . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->pcao_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->pcao_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->pcao_id . "');
							$('#txNome').val('" . $obj->pcao_nome . "');
						</script>";
			}
		}
		
	}
?>