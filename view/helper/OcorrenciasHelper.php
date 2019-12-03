<?php
	class OcorrenciasHelper{
		
		public static function listaOcorrencias(){
			$html = "<script src='js/ocorrencias.js'></script>";
			foreach(OcorrenciasController::getOcorrencias() as $a){
				$html .= "<tr>
							<td>" . $a->oco_nome . "</td>
							<td>" . $a->oco_descricao . "</td>
							<td>" . (($a->oco_descarte == 'S') ? "Sim" : "Não") . "</td>
							" . (($a->oco_sigla == 'X') 
									? "	<td width='70'></td>
										<td width='70'></td>" 
									: "	<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->oco_id . "'>Editar</a></td>
										<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->oco_id . "'>Apagar</a></td>") . "
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->oco_id . "');
							$('#txNome').val('" . $obj->oco_nome . "');
							$('#txDescricao').val('" . $obj->oco_descricao . "');
							$('#ckDescarte').val('" . $obj->oco_descarte . "');
							$('#slEfeitoEspecial').val('" . $obj->oco_efeitoespecial . "');
							" . (($obj->oco_descarte == 'S')
									? "document.getElementById('ckDescarte').checked = true;"
									: "document.getElementById('ckDescarte').checked = false;") . "
						</script>";
			}
		}
		
		public static function populaComboEfeitoEspecial(){
			$select = "	<select name='efeitoespecial' id='slEfeitoEspecial' class='input-xlarge' style='width: 50%;'>
							<option value=''>Nenhum</option>
							<option value='R'>Anula um reuso na próxima etiquetagem</option>
                            <option value='S'>Substitui&ccedil;&atilde;o de QRCode</option>
							<!--option value='P'>Marca de vermelho</option-->
						</select>";
			return $select;
		}
		
		public static function populaComboOcorrencias(){
			$select = "<select name='ocorrencia' id='slOcorrencia' maxlength='50' class='input-xlarge'>
							<option value='0'>** Escolha **</option>";
			foreach (OcorrenciasController::getOcorrencias() as $a){
				$select .= "<option value='".$a->oco_id."'>".$a->oco_nome."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		
		public static function getDescricao($id){
			$oco = OcorrenciasController::getOcorrencia($id);
			return $oco->oco_descricao;
		}
		
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/
?>