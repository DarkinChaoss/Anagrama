<?php
	class UsuariosHelper{
		
		public static function listaUsuarios(){
			$html = "<script src='js/usuarios.js'></script>";
			foreach(UsuariosController::getUsuarios() as $a){
				$btEditar = (($a->usu_nivel == 5) ? "<td width='70'></td>" : "<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->usu_id . "'>Editar</a></td>");
				$btApagar = (($a->usu_nivel == 5) ? "<td width='70'></td>" : "<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->usu_id . "'>Apagar</a></td>");
				$html .= "<tr>
							<td>" . $a->usu_login . "</td>
							<td>" . UsuariosHelper::niveis($a->usu_nivel) . "</td>
							<td> " . UsuariosHelper::getReferencia($a->usu_nivel, $a->usu_referencia) . "</td>
							" . $btEditar . "
							" . $btApagar . "
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				$comboRef = UsuariosHelper::getComboRef($obj->usu_nivel);
				return "<script type=\"text/javascript\">
							$(\"#txId\").val(\"" . $obj->usu_id . "\");
							$(\"#txLoginU\").val(\"" . $obj->usu_login . "\");
							$(\"#txSenhaU\").val(\"" . $obj->usu_senha . "\");
							$(\"#slNivel\").val(\"" . $obj->usu_nivel . "\");
							$(\"#slReferencia\").html(\"" . $comboRef . "\");
							document.getElementById(\"slReferencia\").disabled = false;
							$(\"#slReferencia\").val(\"" . $obj->usu_referencia . "\");
						</script>";
			}
		}
		
		public static function niveis($nivel){
			switch ($nivel){
				case 2:
					$str = "CONFERENTE";
					break;
				case 3:
					$str = "ETIQUETADOR";
					break;
				case 4:
					$str = "ADMINISTRADOR";
					break;
				case 5:
					$str = "MASTER CLIENT";
					break;
				case 6:
					$str = "ARSENAL";					
					break;
				case 7:
					$str = "CIRCULANTE";					
					break;					
				default:
					break;
			}
			return $str;
		}
		
		public static function listaNiveis(){
			$html = "	<option value='-'>** Escolha **</option>
						<option value='2'>CONFERENTE</option>
						<option value='3'>ETIQUETADOR</option>
						<option value='4'>ADMINISTRADOR</option>
						<option value='6'>ARSENAL</option>
						<option value='7'>CIRCULANTE</option>";
			return $html;
		}
		
		public static function getReferencia($nivel, $referencia){
			switch ($nivel){
				case 2:
					$obj = ConferentesController::getConferente($referencia);
					$ref = $obj->cnf_nome;
					break;
				case 3:
					$obj = EtiquetadoresController::getEtiquetador($referencia);
					$ref = $obj->eti_nome;
					break;
				case 4:
					$obj = ResponsaveisTecnicosController::getRTecnico($referencia);
					$ref = $obj->rte_nome;
					break;
				case 5:
					$obj = ClientesController::getCliente($referencia);
					$ref = $obj->cli_nome;
					break;
				case 6:
					$obj = ArsenaisController::getArsenal($referencia);
					$ref = $obj->ars_nome;
					break;
				case 7:
					$obj = CirculantesController::getCirculante($referencia);
					$ref = $obj->cir_nome;
					break;										
				default:
					break;
			}
			return $ref;
		}
		
		public static function getComboRef($nivel){
			$comboRef = "<option value='0'>** Escolha **</option>";
			switch ($nivel){
				case 2:
					foreach(ConferentesController::getConferentes() as $a){
						$comboRef .= "<option value='" . $a->cnf_id . "'>" . $a->cnf_nome . "</option>";
					}
					break;
				case 3:
					foreach(EtiquetadoresController::getEtiquetadores() as $a){
						$comboRef .= "<option value='" . $a->eti_id . "'>" . $a->eti_nome . "</option>";
					}
					break;
				case 4:
					foreach(ResponsaveisTecnicosController::getRTecnicos() as $a){
						$comboRef .= "<option value='" . $a->rte_id . "'>" . $a->rte_nome . "</option>";
					}
					break;
				case 5:
					foreach(ClientesController::getClientes() as $a){
						$comboRef .= "<option value='" . $a->cli_id . "'>" . $a->cli_nome . "</option>";
					}
					break;
				case 6:
					foreach(ArsenaisController::getArsenais() as $a){
						$comboRef .= "<option value='" . $a->ars_id . "'>" . $a->ars_nome . "</option>";
					}
					break;
				case 7:
					foreach(CirculantesController::getCirculantes() as $a){
						$comboRef .= "<option value='" . $a->cir_id . "'>" . $a->cir_nome . "</option>";
					}
					break;										
				default:
					break;
			}
			return $comboRef;
		}
		
		public static function populaAlterarSenha($ref){
			return "<script type='text/javascript'>
						$('#txId').val('" . $_SESSION['usu_id'] . "');
						$('#txLoginU').val('" . $_SESSION['usu_login'] . "');
						$('#txReferencia').val('" . $ref . "');
					</script>";
		}
		
	}
?>