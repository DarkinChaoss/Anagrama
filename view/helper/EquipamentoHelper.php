<?php
	class EquipamentoHelper{
		
		public static function listaEquipamento(){
			$html = "<script src='js/equipamento.js'></script>";
			foreach(EquipamentoController::getEquipamento() as $a){

						$enzimatico = $a->eq_enzimatico;
						$neutro = $a->eq_neutro;
						
						if ($enzimatico =='') {
							$enzimatico = '-';
						}
						
						if ($neutro =='') {
							$neutro = '-';
						}


				$html .= "<tr>
							<td>" . $a->eq_descricao . "</td>
							<td>" . $enzimatico . "</td>
							<td>" . $neutro . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->eq_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->eq_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			$checkedex = '';
			$checkedet = '';
			$checkedred = '';
			$checkedcom = '';
			if(is_null($obj)){
				return "";
				
			} else {
				//var_dump($obj->eq_formatoimp);
				if($obj->eq_equitipo == 'EX'){$checkedex = "true";
				}else{
					$checkedet = "true";
				}
				
				if($obj->eq_formatoimp == 'RED'){$checkedred = "true";
				}else{
					$checkedcom = "true";
				}
				
				
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->eq_id . "');
							$('#txDescricao').val('" . $obj->eq_descricao . "');
							$('#txenzimatico').val('" . $obj->eq_enzimatico . "');
							$('#txneutro').val('" . $obj->eq_neutro . "');
							$('#txex').prop('checked', ".$checkedex." );
							$('#txet').prop('checked', ".$checkedet." );
							$('#txRed').prop('checked', ".$checkedred." );
							$('#txCom').prop('checked', ".$checkedcom." );
						</script>";
			}
		}
		
	}
?>