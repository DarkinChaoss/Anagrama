<?php
	class MateriaisInternosHelper{
		
		public static function listaMateriaisInternos($buscar){
			$html = "";
			foreach(MateriaisInternosController::getMateriaisInternosBuscar($buscar) as $a){
				$html .= "<tr>
							<td>" . $a->mai_cod . "</td>
							<td>" . $a->mai_nome . "</td>
							<td>" . $a->mai_qtde . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->mai_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->mai_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->mai_id . "');
							$('#txCod').val('" . $obj->mai_cod . "');
							$('#txNome').val('" . $obj->mai_nome . "');
							$('#txQtde').val('" . $obj->mai_qtde . "');
						</script>";
			}
		}
		
		public static function populaNomesMateriais(){
			$itens = "";
			foreach(MateriaisInternosController::getMateriaisInternos() as $n){
				if($itens != "")
					$itens .= ", ";
				$itens .= "'" . $n->mai_nome . "'";
			}
			$rtn = "	<script type='text/javascript'>";
			if($itens == ""){
				$rtn .= "	document.getElementById('txNomeMaterial').disabled = true;
							$('#txNomeMaterial').val('Não há materiais internos cadastrados!');";
			} else {
				$rtn .= "	var itens = [" . $itens . "];   
							$('#txNomeMaterial').typeahead({source: itens});";
			}
			$rtn .= "	</script>";
			return $rtn;
		}
		
	}
?>