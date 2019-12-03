<?php

	class NomesProdutosHelper{
		
		public static function listaNomesProdutos($buscar){
			$html = "";
			foreach(NomesProdutosController::getNomesProdutosBuscar($buscar) as $a){
				$html .= "<tr>
							<td>" . $a->nop_nome . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->nop_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->nop_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaCampos($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return "<script type='text/javascript' charset='ISO-8859-1'>
							$('#txId').val('" . $obj->nop_id . "');
							$('#txNome').val('" . $obj->nop_nome . "');
                            $('#txNomeAntigo').val('" . $obj->nop_nome . "');
						</script>";
			}
		}
		
	}
?>