<?php
	class ProdutosConsignadosHelper{
	    
	    public static function geraCodigoProdutoConsignados(){
	        $codigo = date("ymdHis") . $_SESSION['usu_id'];
	        return $codigo;
	    }

		public static function listaProdutosConsignados($buscar, $pag = 0, $descart){
			$html = "";
			if($pag != 0){
				$inicio = ($pag - 1) * 10;
				$qtde = 10;
				$limit = " LIMIT " . $inicio . ", " . $qtde;
			} else {
				$limit = "";
			}
			if($descart != "S")
				$where = "(pro_descarte IS NULL OR pro_descarte = '')";
			else
				$where = "";
			foreach(ProdutosConsignadoController::getProdutosConsignadosBuscar($buscar, $limit, $where) as $p ){
				// botão para produto composto
				if($p->pro_composto){
					$btComposto = "<a href='#telaComposicao' class='btn bt_composto' title='Visualizar composição de " . $p->pro_nome . "' id='" . $p->pro_id . "' data-toggle='modal'><i class='icon-inbox'></i></a>";
					// contagem de filhos
					$pco = ProdutosCompostosController::getProdutosCompostosInnerCount("pco_idpai = " . $p->pro_id . " AND pro_descarte <> '*'");
					if($pco == 0)
						$compoe = "<span style='font-size: 0.7em;'>COMPOSIÇÃO: VAZIO</span>";
					else
						$compoe = "<span style='font-size: 0.7em;'>COMPOSIÇÃO: " . $pco . (($pco == 1) ? " ITEM" : " ITENS") . "</span>";
				} else {
					$btComposto = "";
					// identifica o produto pai
					$pco = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = " . $p->pro_id);
					if(count($pco) > 0){
						$pai = ProdutosController::getProduto($pco[0]->pco_idpai);
						$compoe = "<i class='icon-inbox'></i> <span style='font-size: 0.7em;'>" . $pai->pro_nome . " ( " . $pai->pro_qrcode . " )</span>";
					} else {
						$compoe = "";
					}
				}
				// verifica se possui imagem de rótulo salva
				$temRotulo = "";
				if(is_file("img/rotulos/" . $p->pro_id . ".jpg"))
					$temRotulo = "<i class='icon-camera pull-right'></i>";
				//
				$editar = (($p->pro_descarte == '*') ? "<label style='color: red;'>Descartado</label>" : "<a class='btn btn-success edit' title='Editar' id='" . $p->pro_id . "'>Editar</a>");
				$apagar = ($_SESSION['usu_nivel'] == 1 || ItensSolicitacoesController::getReprocessamentoItem($p->pro_id, '1') > 0) ? "" : "<a class='btn btn-danger delete' title='Apagar' id='" . $p->pro_id . "'>Apagar</a>";
				$reuso = ItensSolicitacoesController::getReprocessamentoItem($p->pro_id);
				$p->pro_qtde != null ? $reuso = '-' : $reuso =  $reuso . "/" . $p->pro_maxqtdprocessamento;
				$html .= "<tr>
							<td>" . $temRotulo . $p->pro_nome . "</td>
							<td>" . $p->pro_qrcode . "</td>
							<td>" . $p->pro_qtde . "</td>
							<td>" . $compoe . "</td>
							<td width='30'>" . $btComposto . "</td>
							<td width='70'>" . $editar . "</td>
							<td width='70'>" . $apagar . "</td>
						</tr>";
			}
			return $html;
		}
		
		public static function paginacao($buscar, $pag, $descart){
			if($descart != "S")
				$where = "(pro_descarte IS NULL OR pro_descarte = '')";
			else
				$where = "";
			$n = ProdutosConsignadoController::getProdutosConsignadoBuscarCount($buscar, "", $where);
			$pags = ceil($n / 10);
			$html = "	<input type='hidden' id='pagAtiva' value='" . $pag. "'>
						<div class='pagination pagination-right'>
					  		<ul>
					  			<li" . (($pag == 1) ? " class='disabled'" : "") . " pag='prev'><a><i class='icon-chevron-left'></i></a></li>";
			if($pag < 9){
				$i = 1;
			} else {
				$html .= "		<li pag='1'><a>1</a></li>
								<li class='disabled'><a>...</a></li>";
				$i = $pag - 4;
			}
			for($max = $pag + 5; $i <= $pags && $i <= $max; $i++){
				$html .= "		<li" . (($i == $pag) ? " class='active'" : "") . " pag='" . $i . "'><a>" . $i . "</a></li>";
			}
			if($i <= $pags){
				$html .= "		" . (($i < $pags) ? "<li class='disabled'><a>...</a></li>" : "") . "
								<li pag='" . $pags . "'><a>" . $pags . "</a></li>";
			}
			$html .= "			<li" . (($pag == $pags) ? " class='disabled'" : "") . " pag='next'><a><i class='icon-chevron-right'></i></a></li>
							</ul>
						</div>";
			return $html;
		}
		
		public static function listaProdutosEtiquetagem(){
			$html = "<script src='js/consignado.js'></script>";
			foreach(ProdutosController::getProdutos() as $p ){
				$s = SetoresController::getSetor($p->pro_idsetor); 
				$html .= "<tr>
							<td>" . $p->pro_qrcode . "</td>
							<td>" . $p ->pro_nome . "</td>
							<td>" . $s->set_nome . "</td>
						</tr>";
			}
			return $html;
		}
		
		public static function populaComboSetor($id = 0){
			$select = "<select name='setor' id='txSetor' maxlength='50' class='input-xlarge'>
							<option value='0'>** Escolha **</option>";
			foreach (SetoresController::getSetores() as $s){
				$select .= "<option value='".$s->set_id."'".($s->set_id == $id ? "selected='selected'" : "").">".$s->set_nome."</option>";
			}
			$select .="</select>";
			return $select;
		}
		
		public static function populaComboGMaterial($id = 0){
			$select = "<select name='grupomaterial' id='txGrupomaterial' maxlength='30' class='input-xlarge'>
							<option value='0'>** Escolha **</option>";
			foreach (GruposMateriaisController::getGruposMateriais() as $gm){
				$select .= "<option value='".$gm->gma_id."'".($gm->gma_id == $id ? "selected='selected'" : "").">".$gm->gma_nome."</option>";
			}
			$select .="</select>";
			return $select;
		}
		
		public static function populaCampos($obj){
			if( ! is_null($obj)) {
		// Permissão para alteração de produtos - usu_id	     
                 $arr_usuPer = array(89, 134);
             
				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->pro_id . "');
							$('#txQrcode').val('" . $obj->pro_qrcode . "');
							" . (($obj->pro_qtde != null) ? "$('#ckQtde').attr('checked', 'true');" : "") . "
							$('#txQtde').val('" . $obj->pro_qtde . "');

							//$('#txtSetor').html('" . $obj->pro_idsetor . "');

							$('#txNome').val('" . $obj->pro_nome . "');
							$('#txCurvatura').val('" . $obj->pro_curvatura . "');
							$('#txCalibre').val('" . $obj->pro_calibre . "');
							$('#txComprimento').val('" . $obj->pro_comprimento . "');
							$('#txDiametrointerno').val('" . $obj->pro_diametrointerno . "');
							$('#txFabricante').val('" . $obj->pro_fabricante . "');
							$('#txNumSerie').val('" . $obj->pro_numserie . "');
							$('#txDatafabricacao').val('" . DefaultHelper::converte_data( $obj->pro_datafabricacao )."');
							$('#txMarca').val('" . $obj->pro_marca . "');
							$('#txAnvisa').val('" . $obj->pro_anvisa . "');
							$('#txLotefabricacao').val('" . $obj->pro_lotefabricacao . "');
							$('#txReferencias').val('" . $obj->pro_referencias . "');
							$('#txValidacaofabricacao').val('" . DefaultHelper::converte_data($obj->pro_validacaofabricacao) . "');
							$('#txQtdmaxima').val('" . $obj->pro_maxqtdprocessamento . "');
							$('#txAlerta').val('" . $obj->pro_alerta . "');
							$('#txAlertaMsg').val('" . $obj->pro_alertamsg . "');
							$('#txCusto').val('" . number_format($obj->pro_custo,2,',','.') . "');
							$('#txControle').val('" . $obj->pro_controle . "');
							$('#txNumeroEntrada').val('" . $obj->pro_numeroentrada . "');
							$('#txNumeroSaida').val('" . $obj->pro_numerosaida . "');
							$('#txValidadeEsterilizacao').val('" . DefaultHelper::converte_data( $obj->pro_validadeesterilizacao )."');
							$('#txData').val('" . DefaultHelper::converte_data($obj->pro_data) . "');
							$('#txAlertaMsg').val('" . $obj->pro_alertamsg . "');
							$('#mdl-substituir').find('input[name=idproduto]').val('" . $obj->pro_id . "');
							$('#mdl-substituir').find('input[name=qrcode_atual]').val('" . $obj->pro_qrcode . "');
							
							" . (($obj->pro_composto == 1) ? "$('#ckComposto').attr('checked', 'true');" : "") . "
							" . ((ItensSolicitacoesController::getReprocessamentoItem($obj->pro_id, '1') > 0 && !in_array($_SESSION['usu_id'], $arr_usuPer) ) ? "$('#btSalvar').hide();" : "") . "
							$('#btRotulo').show();
						</script>";						
			} else {
				return "";
			}
		}
		
		public static function populaNomesProdutos(){
			$itens = "";
			foreach(NomesProdutosController::getNomesProdutos() as $n ){
				if($itens != "")
					$itens .= ", ";
					$itens .= "'" . DefaultHelper::removerAcentos($n->nop_nome). "'";
			}
			$rtn = "	<script type='text/javascript' charset='ISO-8859-1'>";
			if($itens == ""){
				$rtn .= "	document.getElementById('txNome').disabled = true;
							$('#txNome').val('Não há nomes de produtos cadastrados!');";
			} else {
				$rtn .= "	var itens = [" . $itens . "];   
							$('#txNome').typeahead({source: itens});";
			}
			$rtn .= "	</script>";
			return $rtn;
		}
		
	}
?>