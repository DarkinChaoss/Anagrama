<?php
/*foreach ($_SESSION as $key => $value) {
    print($key.' - '.$value.'<br>');
};

foreach ($_COOKIE as $key => $value) {
    print($key.' - '.$value.'<br>');
};*/

	class SolicitacoesHelper{
		
		public static function listaSolicitacoes($buscar, $pag = 0){
			$html = "";
			if($pag != 0){
				$inicio = ($pag - 1) * 10;
				$qtde = 10;
				$limit = " LIMIT " . $inicio . ", " . $qtde;
			} else {
				$limit = "";
			}
			foreach(SolicitacoesController::getSolicitacoesBuscar($buscar, $limit) as $a ){
				$set = SetoresController::getSetor($a->ses_idsetor);
				$html .= "<tr>
							<td>" . $a->ses_id . "</td>
							<td>" . $set->set_nome . "</td>
							<td>" . DefaultHelper::converte_data($a->ses_dataentrada) . "</td>
							<td width='70'><a class='btn btn-success edit' title='Editar' id='" . $a->ses_id . "'>Editar</a></td>
							<td width='70'><a class='btn btn-danger delete' title='Apagar' id='" . $a->ses_id . "'>Apagar</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function listaSolicitacoesSetores(){
			$html = "";
			foreach(SetoresController::getSetores("set_fazsolicitacao = 'S'") as $a ){
				$html .= "<tr>
							<td>" . $a->set_nome . "</td>
							<td><a class='btn btn-primary nova' id='" . $a->set_id . "'><i class='icon-plus icon-white'></i> Nova solicitação</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function paginacao($buscar, $pag){
			$arr = SolicitacoesController::getSolicitacoesBuscar($buscar);
			$pags = ceil(sizeof($arr) / 10);
			$html = "	<input type='hidden' id='pagAtiva' value='" . $pag. "'>
						<div class='pagination pagination-right'>
					  		<ul>
					  			<li" . (($pag == 1) ? " class='disabled'" : "") . " pag='prev'><a><i class='icon-chevron-left'></i></a></li>";
			for($i = 1; $i <= $pags; $i++){
				$html .= "<li" . (($i == $pag) ? " class='active'" : "") . " pag='" . $i . "'><a>" . $i . "</a></li>";
			}
			$html .= "			<li" . (($pag == $pags) ? " class='disabled'" : "") . " pag='next'><a><i class='icon-chevron-right'></i></a></li>
							</ul>
						</div>";
			$html .= DefaultHelper::permissoesReduzidas(2);
			return $html;
		}
		
		public static function populaCampos($obj){
			if(!is_null($obj)) {

				return "<script type='text/javascript'>
							$('#txId').val('" . $obj->ses_id . "');
							$('#slSetor').val('" . $obj->ses_idsetor . "');
							document.getElementById('slSetor').disabled = 'true';
							listaItens(" . $obj->ses_id . ");
							listaItensConsignados(" . $obj->ses_id . ");
							$('#txIdSol').val(" . $obj->ses_id . ");
							//$('#tlaProduto').modal();
							$('#btAdProd').click();
							//goSolicitacao();
						</script>";				

			} else {
				return "";
			}
		}
		
		public static function populaComboSetor($id = 0, $class=0){

			if ($class == 1){
				$select = "	<select name='setor' id='slSetorQte' class='input-xlarge'>
					    	<option value='0'>** Escolha **</option>";
			}
			else{
			$select = "	<select name='setor' id='slSetor' class='input-xlarge'>
						<option value='0'>** Escolha **</option>";
			}
			foreach (SetoresController::getSetores() as $set){
				$select .= "<option value='".$set->set_id."' " . (($set->set_id == $id) ? "selected='selected'" : "" ) . ">".$set->set_nome."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		
		public static function populaComboRTecnico($id = 30){
			$select = "	<select name='rTecnico' id='slRTecnico' maxlength='50' class='input-xlarge'>
							<!--option value='0'>** Escolha **</option-->";
			foreach (ResponsaveisTecnicosController::getRTecnicos("rte_coren <> '' AND (rte_admin <> '1' OR rte_admin IS NULL)") as $t){
				$select .= "<option value='".$t->rte_id."'".($t->rte_id == $id ? "selected='selected'" : "").">".$t->rte_nome."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		
		public static function populaComboMEsterilizacao($id = 0){
			$select = "	<select name='metEsterilizacao' id='slMEsterilizacao' maxlength='30' class='input-large'>
							<option value='0'>** Escolha **</option>";
			foreach (MetodosController::getMetodos() as $mt){
				$select .= "<option value='".$mt->met_id."'".($mt->met_id == $id ? "selected='selected'" : "").">".$mt->met_nome."</option>";
			}
			$select .= "</select>";
			return $select;
		}

		public static function populaComboEEsterilizacao($id = 0){
			$select = "	<select name='eqEsterilizacao' id='slEEsterilizacao' maxlength='30' class='input-large'>
							<option value='0'>** Escolha **</option>";
			foreach (EquipamentoController::getEquipamento() as $eq){
				
				$select .= "<option value='".$eq->eq_id."'".($eq->eq_id == $id ? "selected='selected'" : "").">".$eq->eq_descricao."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		
		
		//This is the new function for a method equipamento it's the same but with a different name 
		
			public static function populaComboEEsterilizacaoet($id = 0){
			$select = "	<select name='eqEsterilizacaoet' id='slEEsterilizacaoet' maxlength='30' class='input-large'>
							<option value='0'>** Escolha **</option>";
			foreach (EquipamentoController::getEquipamento() as $eq){
				
				$select .= "<option value='".$eq->eq_id."'".($eq->eq_id == $id ? "selected='selected'" : "").">".$eq->eq_descricao."</option>";
			}
			$select .= "</select>";
			return $select;
		}
		


		public static function listaSolicitacoesAndamentoSetor($where){
			$html = "<script src='js/solicitacoes.js'></script>";
			foreach(SolicitacoesController::getSolicitacoes($where) as $a ){
				switch ($a->ses_status) {
					case 0:	$status = "<span class='badge'>Pendente</span>";
							break;
					case 1:	$status = "<span class='badge badge-warning'>Conferência</span>";
							break;
					case 2:	$status = "<span class='badge badge-warning'>Etiquetagem</span>";
							break;
					case 3:	$status = "<span class='badge badge-warning'>Esterilização</span>";
							break;
					case 4:	$status = "<span class='badge badge-warning'>Expedição</span>";
							break;
					default: break;
				}
				$html .= "<tr>
							<td>" . $a->ses_id . "</td>
							<td>" . DefaultHelper::converte_data($a->ses_dataentrada) . "</td>
							<td>" . $status . "</td>
							<td width='30'><button class='btn detalhes' id='" . $a->ses_id . "' title='Detalhes da solicitação nº " . $a->ses_id . "'><i class='icon-list'></i></button></td>
						</tr>";
			}
			return $html;
		}
		
		public static function listaSolicitacoesSetor($buscar){
			$html = "<script src='js/solicitacoes.js'></script>";
			$where = "ses_idsetor = " . $_SESSION['usu_referencia'];
			if($buscar != "") {
				$where .= " AND ses_id = " . $buscar;
				$html .= "<script type='text/javascript'>
							$('#btLimparBusca').show();
						</script>";
			}
			foreach(SolicitacoesController::getSolicitacoes($where, "ses_id DESC") as $a ){
				switch ($a->ses_status) {
					case 0:	$status = "<span class='badge'>Pendente</span>";
							break;
					case 1:	$status = "<span class='badge badge-warning'>Conferência</span>";
							break;
					case 2:	$status = "<span class='badge badge-warning'>Etiquetagem</span>";
							break;
					case 3:	$status = "<span class='badge badge-warning'>Esterilização</span>";
							break;
					case 4:	$status = "<span class='badge badge-warning'>Expedição</span>";
							break;
					case 5:	$status = "<span class='badge badge-success'>Finalizada</span>";
							break;
					default: break;
				}
				$html .= "<tr>
							<td>" . $a->ses_id . "</td>
							<td>" . DefaultHelper::converte_data($a->ses_dataentrada) . "</td>
							<td>" . $status . "</td>
							<td></td>
							<td width='110'><a class='btn detalhes' id='" . $a->ses_id . "'><i class='icon-list'></i> Detalhes</a></td>
						</tr>";
			}
			return $html;
		}
		
		public static function listaMateriaisSolicitacaoSetor($id){
			$where = "mat_idses = " . $id;
			$html = "";
			foreach(MateriaisController::getMateriais($where) as $a ){
				$html .= "<tr>
							<td>" . $a->mat_qtde . "</td>
							<td>" . $a->mat_material . "</td>
							<td><a href='#' class='btn btn-danger removeMaterial' id='" . $a->mat_id . "'><i class='icon-remove icon-white'></i></a></td>
						</tr>";
			}
			return utf8_encode($html);
		}
		
		public static function populaDetalhes($id){
			$rtn = "";
			$ses = new SolicitacoesModel();
			$ses = SolicitacoesController::getSolicitacao($id);
			// progresso
			$porcentagem = 0;
			switch ($ses->ses_status) {
				case '0': $porcentagem = 15;
						break;
				case '1': $porcentagem = 33;
						break;
				case '2': $porcentagem = 51;
						break;
				case '3': $porcentagem = 69;
						break;
				case '4': $porcentagem = 85;
						break;
				case '5': $porcentagem = 100;
						break;
				default: break;
			}
			if($ses->ses_status == 'x') {
				$progresso = "<span class='badge badge-danger'>Cancelada</span>";
			} else {
				$progresso = "<b>Progresso</b><br><br>";
				$progresso .= "<div class='progress progress-striped" . (($ses->ses_status == '5') ? "" : " active") . "'>";
				$progresso .= "		<div class='bar' style='width: " . $porcentagem ."%;'></div>";
				$progresso .= "</div>";
				$progresso .= "<span class='badge'>Pendente</span>";
				$progresso .= "<span class='badge badge-warning'>Conferência</span>";
				$progresso .= "<span class='badge badge-warning'>Etiquetagem</span>";
				$progresso .= "<span class='badge badge-warning'>Esterilização</span>";
				$progresso .= "<span class='badge badge-warning'>Expedição</span>";
				$progresso .= "<span class='badge badge-success'>Finalizada</span>";
			}
			// materiais - Setor
			$where = "mat_idses = " . $id;
			$materiais = "";
			$qtdeMateriais = 0;
			foreach(MateriaisController::getMateriais($where) as $a){
				$materiais .= "	<tr><td>" . $a->mat_qtde . "</td><td>" . $a->mat_material . "</td></tr>";
				$qtdeMateriais += $a->mat_qtde;
			}
			// itens - Sterilab
			$where = "iso_idses = " . $id;
			$itens = "";
			$qtdeItens = 0;
			foreach(ItensSolicitacoesController::getItens($where) as $a){
				$produto = ProdutosController::getProduto($a->iso_idproduto);
				$itens .= "	<tr><td>" . $produto->pro_nome . "</td><td>" . $produto->pro_qrcode . "</td></tr>";
				$qtdeItens ++;
			}
			$tbItens = "<h5>Itens em execução: " . $qtdeItens . "</h5>";
			$tbItens .= "<table class='table table-hover'><thead>";
			$tbItens .= "<tr><th>Produto</th><th width='150'>QRCode</th></tr>";
			$tbItens .= "</thead>";
			$tbItens .= "<tbody>" . $itens ."</tbody></table>";
			
			$rtn .= "<script type='text/javascript'>
						$('#txDataEntrada').text('" . DefaultHelper::converte_data($ses->ses_dataentrada) . "');
						$('#progressoSolicitacao').html(\"" . $progresso . "\");
						$('#listaMateriais').html(\"" . $materiais . "\");
						$('#txQtdeMateriais').text(\"" . $qtdeMateriais . "\");
						" . (($qtdeItens == 0) ? "" : "$('#listaItens').html(\"" . $tbItens . "\");") .  "
					</script>";
			return $rtn;
		}
		
		// Rotina que verifica solicitações não-finalizadas mas que já tiveram todos seus itens etiquetados 
		public static function corrigeStatusFalho(){
			foreach (SolicitacoesController::getSolicitacoes("ses_status > '0' AND ses_status < '5'") as $ses){
				// itens que ainda faltam etiquetar
				$itens0 = ItensSolicitacoesController::getCountItens("iso_idses = " . $ses->ses_id . " AND iso_status = '0'");
				// itens já etiquetados
				$itens1 = ItensSolicitacoesController::getCountItens("iso_idses = " . $ses->ses_id . " AND iso_status = '1'");
				// se houver mais itens pra etiquetar e pelo menos algum foi etiquetado
				if($itens0 == 0 && $itens1 > 0) {
					error_log("STATUS FALHO: ses = ".$ses->ses_id);
					$ses->ses_status = '5';
					$ses->update();
				}
			}
		}

	}
?>