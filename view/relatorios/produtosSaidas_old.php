<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));

	include "view/helper/cabecalho.php";

	$filtro = '';
	$dataInicial = date('d/m/Y');
	$dataFinal = date('d/m/Y');;
	$tipoRel = '';

	if($_REQUEST['gerar'] == 1){

	    $filtro = $_REQUEST['filtro'];
	    $dataInicial = $_REQUEST['data1'];
	    $dataFinal = $_REQUEST['data2'];
	    $tipoRel = $_REQUEST['tipo'];

	}
?>

	<h1>Controle de Saída de Materiais</h1>

	<form>
		<div class="row-fluid">
			<div class="span2">
				<label class="radio">
					<input type="radio" name="tipo" value="a" <?php echo ( $tipoRel == 'a' ? 'checked' : $tipoRel == '' ? 'checked' : ''  ) ?>>
					Analítico
				</label>
				<label class="radio">
					<input type="radio" name="tipo" value="s" <?php echo ( $tipoRel == 's' ? 'checked' : '' ) ?> >
					Sintético
				</label>
			</div>

			<div class="span4">
				<label>Setor:</label>
				<select class="input-xlarge" name="filtro">
					<option value="0"> Todos </option>
				<?php
    				foreach (SetoresController::getSetores() as $objSetor){
				        echo '<option value="'.$objSetor->set_id.'" '.( $objSetor->set_id == $filtro ? ' selected' : '' ).' > '.$objSetor->set_nome.' </option>';
    				}
				?>
				</select>
				<br>
				<label>Intervalo:</label>
				<input type="text" name="data1" class="input-small data" maxlength="10" value="<?php echo $dataInicial ?>">
				&nbsp;até&nbsp;
				<input type="text" name="data2" class="input-small data" maxlength="10" value="<?php echo $dataFinal ?>" >
				<br>
			</div>

			<div class="pull-right">
				<button class="btn btn-primary" name="gerar" value="1"><i class="icon-file icon-white"></i> Gerar relatório</button>
				<a href="#" id="btPrint" class="btn hide"><i class="icon-print"></i> Imprimir</a>
			</div>
		</div>
	</form>

	<hr>

	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressão -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		if($_REQUEST['gerar'] == 1){

		    $data1 = (($_REQUEST['data1'] == "") ? "" : "DATE(isa_data) >= '" . DefaultHelper::converte_data($_REQUEST['data1']) . "'");
		    $data2 = (($_REQUEST['data2'] == "") ? "" : "DATE(isa_data) <= '" . DefaultHelper::converte_data($_REQUEST['data2']) . "'");
		    $and = (($_REQUEST['data1'] != "" && $_REQUEST['data2'] != "") ? " AND " : "");
		    $vazio = (($_REQUEST['data1'] == "" && $_REQUEST['data2'] == "") ? "isa_del IS NULL" : "");

          // -----------------------------------


			$html = "	<script>
							$('#btPrint').show();
						</script>";
			$html .= "	<div class='onlyPrint'>
							<div class='row-fluid'>
								<img src='img/tms.png' width='100px' class='pull-left'>
								<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
							</div>
							<h4>Controle de Saídas de Materiais</h4>
						</div>
						<br>
						<label class='parametro'>Tipo: " . (($_REQUEST['tipo'] == "a") ? "Analítico" : "Sintético") . "</label>
						<label class='parametro'>
							Intervalo:";
			if($_REQUEST['data1'] == "" && $_REQUEST['data2'] == ""){
				$html .= " Tudo";
			} else {
				$html .= 	" " . (($_REQUEST['data1'] != "") ? $_REQUEST['data1'] : "Desde o início") . "
							até
							" . (($_REQUEST['data2'] != "") ? $_REQUEST['data2'] : "a última data");
			}
			$html .= "	</label>
						<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
						<br>";

			if($_REQUEST['tipo'] == "a"){ // ANALÍTICO
				$html .= "	<table class='tableLinhas' style='white-space: nowrap;' >
								<tr>
									<th width='60px'>Setor</th>
									<th width='250px'>Produto</th>
									<th width='130px'>QRCode</th>
				                    <th width='110px'>Saída</th>
					                <th width='30px'>Uso</th>
				                    <th>Status</th>
								</tr>";
				$i = 0;
				$dataAnterior = "";
				$totalData = 0;
				$totalItensData = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;

				$where = '';
				if( $_REQUEST['data1'] != '' && $_REQUEST['data2'] != '' ){
				    $where .= ' ( '.$data1 . $and . $data2 . $vazio . ')';
				}

				foreach (ItensSaidaController::getItensSaidaBySetor($_REQUEST['filtro'], $where) as $isa){

				    $objSma = SaidaMateriaisController::getSaidaMateriais( $isa->isa_idsaida );
				    $objProduto = ProdutosController::getProduto( $isa->isa_idproduto );
				    $objSetor = SetoresController::getSetor( $objSma->sma_idsetor );

					$dia = DefaultHelper::converte_data( date('Y-m-d', strtotime($isa->isa_data)) );
					$dataSaida = date('d/m/Y H:i:s', strtotime( $isa->isa_data ) ) ;

					// total em cada data
					if($dataAnterior != $dia && $i != 0){
						$html .= "	<tr>
										<td colspan='5' class='bold right'>Total de itens em " . $dataAnterior . ": </td>
										<td class='bold left'>" . $totalData . "</td>
									</tr>";
						$totalData = 0;
						$totalItensData = 0;
					}

					// Busca proxima solicitação do material no período
					$where = ' iso_idproduto = ' .$objProduto->pro_id
					       . ' AND iso_nreuso = ' . ($isa->isa_reuso + 1);

// 					if( $_REQUEST['data1'] != '' && $_REQUEST['data2'] != '' ){
// 					    $where .= ' AND ( DATE(iso_data) >= "'.DefaultHelper::converte_data($_REQUEST['data1']).'" AND DATE(iso_data) <= "'.DefaultHelper::converte_data($_REQUEST['data2']).'" )';
// 					}

					$arrIsos = ItensSolicitacoesController::getItens($where, ' iso_data ASC');
					$objIso = $arrIsos[0];

					// Verifica se material já foi esterilizado = se já retornou
					if( $objIso->iso_id > 0 ){
					    $status = 'Devolvido ' . DefaultHelper::converte_data($objIso->iso_data);
					}

					// Se não achou proxima esterilização
					if( !$objIso->iso_id > 0 ){ $status = 'Em uso'; }

					// Verifica se o produto está descartado
					if( $objProduto->pro_descarte == '*' ){

					    $dataInicial = date('Y-m-d', strtotime(  DefaultHelper::converte_data( $_REQUEST['data1'] ) ));
					    $dataFinal = date('Y-m-d', strtotime( DefaultHelper::converte_data( $_REQUEST['data2'] ) ));

					    $where = ' opr_idproduto = '.$objProduto->pro_id
					           . ' AND oco_descarte = "S"'
			                   . ' AND ( DATE(opr_data) >= "'.$dataInicial.'" AND DATE(opr_data) <= "'.$dataFinal.'" )';

  	                    // Verifica se descarte ocorreu entre a data solicitada
					    $arrOpr = OcorrenciasProdutosController::getOcorrenciasProdutos( $where );

                        foreach ($arrOpr as $objOpr){
                            $status = 'Descartado ' . DefaultHelper::converte_data($objOpr->opr_data);
                        }
					}

				    $html .= "	<tr>
								<td>" . $objSetor->set_nome . "</td>
								<td>" . $objProduto->pro_nome . "</td>
								<td>" . $objProduto->pro_qrcode . "</td>
								<td>" . $dataSaida . "</td>
			                    <td>" . ( $isa->isa_reuso == '' ? '1' : ($isa->isa_reuso+1) ) . "</td>
					            <td>" . $status . "</td>
							</tr>";
				    $dataAnterior = $dia;
				    $totalData ++;
				    $totalItensData ++;
				    $totalGeral ++;
				    $totalItensGeral ++;
				    $i++;

				}

				// último total
				if($i != 0){
					$html .= "	<tr>
									<td colspan='5' class='bold right'>Total de itens em " . $dataAnterior . ": </td>
									<td class='bold left'>" . $totalData . "</td>
								</tr>
								<tr>
									<td colspan='5' class='bold right dark'>Total de itens no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else {
					$html .= "<tr><td colspan='6' align='center'>Nenhum registro encontrado.</td></tr>";
				}

			} else { // SINTÉTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th>Produto</th>
									<th>Total de produtos</th>
								</tr>";
				$i = 0;
				$setorAnterior = "";
				$proAnterior = '';
				$totalPro = 0;
				$totalSetor = 0;
				$totalGeral = 0;

				$where = '';
				if( $_REQUEST['data1'] != '' && $_REQUEST['data2'] != '' ){
				    $where .= ' ( '.$data1 . $and . $data2 . $vazio . ')';
				}

				foreach (ItensSaidaController::getItensSaidaBySetor($_REQUEST['filtro'], $where, 'set_nome, pro_nome, pro_id') as $isa){

				    $objSma = SaidaMateriaisController::getSaidaMateriais( $isa->isa_idsaida );
					$set = SetoresController::getSetor( $objSma->sma_idsetor );
					$objProduto = ProdutosController::getProduto( $isa->isa_idproduto );

					// total de cada nome de produto
					if($proAnterior != $objProduto->pro_nome && $totalPro > 0){
					    $html .= "	<tr>
										<td>" . $proAnterior . "</td>
										<td>" . $totalPro . "</td>
									</tr>";
					    $totalPro = 0;
					    $proAnterior = '';
					}

					// total em cada setor
					if($setorAnterior != $set->set_nome && $totalPro > 0){

						$html .= "	<tr>
										<td>" . $proAnterior . "</td>
										<td>" . $totalPro . "</td>
									</tr>
                                    <tr>
										<td class='bold right dark'> Total de itens em " . $setorAnterior . "</td>
										<td class='bold left dark'> " . $totalSetor . "</td>
									</tr>";
						$totalSetor = 0;
						$totalPro = 0;
						$proAnterior = '';
					}

				    // Busca proxima solicitação do material no período = verifica se ele voltou
				    $where = ' iso_idproduto = ' .$objProduto->pro_id
				           . ' AND iso_nreuso = ' . ($isa->isa_reuso + 1);

// 				    if( $_REQUEST['data1'] != '' && $_REQUEST['data2'] != '' ){
// 				        $where .= ' AND ( DATE(iso_data) >= "'.DefaultHelper::converte_data($_REQUEST['data1']).'" AND DATE(iso_data) <= "'.DefaultHelper::converte_data($_REQUEST['data2']).'" )';
// 				    }

				    $arrIsos = ItensSolicitacoesController::getItens($where, ' iso_data DESC');
				    $objIso = $arrIsos[0];

				    // Busca ultima saída do material no período
				    $wherePeriodoUltimaSaida = ' AND (DATE(isa_data) >= "'.DefaultHelper::converte_data($_REQUEST['data1']).'" AND DATE(isa_data) <= "'.DefaultHelper::converte_data($_REQUEST['data2']).'")';
				    $arrUsosItem = ItensSaidaController::getItemUltimaSaida( $objProduto->pro_id, $wherePeriodoUltimaSaida );
				    $objUltimoUso = $arrUsosItem[0];
				    if($objUltimoUso->isa_reuso == ''){ $objUltimoUso->isa_reuso = 0; }

			        $usoAtual = $isa->isa_reuso;
			        $proxSol = $objIso->iso_nreuso;
			        $ultimoUso = $objUltimoUso->isa_reuso;

			        if( $usoAtual == $ultimoUso ){

		                $totalPro++;
		                $totalSetor++;
		                $totalGeral++;
		                $i++;

			        }

					$proAnterior = $objProduto->pro_nome;
					$setorAnterior = $set->set_nome;
				}

			    // último total
				if($totalSetor > 0 && $totalPro > 0 ){
					$html .= "
					           <tr>
									<td>" . $proAnterior . "</td>
									<td>" . $totalPro . "</td>
								</tr>
					           <tr>
									<td class='bold right dark'>" . $setorAnterior . "</td>
									<td class='bold left dark'>" . $totalSetor . "</td>
								</tr>";
				}

				if( $i > 0 ){
				    $html .= "
					    <tr>
							<td class='bold right dark'>Total de produtos no relatório: </td>
							<td class='bold left dark'>" . $totalGeral . "</td>
						</tr>";
				}else{
				    $html .= "<tr><td colspan='7' align='center'>Nenhum registro encontrado.</td></tr>";
				}


			}
			$html .=  "</table>";
			echo $html;
		}
		?>
	</div>

<?php
	include "view/helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2013
*/
?>