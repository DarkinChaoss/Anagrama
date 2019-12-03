<?php
	class ResponsaveisTecnicosHelper{
		
		public static function listaResponsaveisTecnicos(){
			$html = "<script src='js/responsaveisTecnicos.js'></script>";
			foreach(ResponsaveisTecnicosController::getRTecnicos() as $a){
				// verifica se pessoa não possui usuário e libera botão de criação
				$usu = UsuariosController::getUsuarios("usu_nivel = '4' AND usu_referencia = " . $a->rte_id);
				if(empty($usu))
					$btNewUser = "<a class='btn new_user' title='Criar usuário para " . $a->rte_nome . "' id='" . $a->rte_id . "_4'><i class='icon-glass'></i></a>";
				else
					$btNewUser = "";
				//
				if($a->rte_admin == 1)
					$somenteAdmin = "<br><small><i>Somente administrador</i></small>";
				else
					$somenteAdmin = "";
				$html .= "<tr>
							<td>" . $a->rte_nome . $somenteAdmin . "</td>
							<td>" . $a->rte_contato . "</td>
							<td>" . $a->rte_coren . "</td>
							<td width='30'>" . $btNewUser . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->rte_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->rte_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->rte_id . "');
							$('#txNome').val('" . $obj->rte_nome . "');
							$('#txContato').val('" . $obj->rte_contato . "');
							$('#txCoren').val('" . $obj->rte_coren . "');
							" . (($obj->rte_admin == 1) ? "$('#ckAdmin').attr('checked', 'true');" : "") . ";
							" . (($obj->rte_permissao == 1) ? "$('#ckPermissao').attr('checked', 'true');" : "") . "
						</script>";
			}
		}
		
	}
?>