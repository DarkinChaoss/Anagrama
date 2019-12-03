<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));

	include "view/helper/cabecalho.php";
?>

	<h1>Relatório de Esterilizações por data</h1>

	<form>
		<div class="row-fluid">
			<div class="span2">
				<label class="radio">
					<input type="radio" name="tipo" value="a" checked>
					Analítico
				</label>
				<label class="radio">
					<input type="radio" name="tipo" value="s">
					Sintético
				</label>
			</div>

			<div class="span4">
				<label>Intervalo:</label>
				<input type="text" name="data1" class="input-small data" maxlength="10" required autocomplete="off">
				&nbsp;até&nbsp;
				<input type="text" name="data2" class="input-small data" maxlength="10" required autocomplete="off">
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
			$html = "	<script>
							$('#btPrint').show();
						</script>";
			$html .= "	<div class='onlyPrint'>
							<div class='row-fluid'>
								<img src='img/tms.png' width='100px' class='pull-left'>
								<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
							</div>
							<h4>Relatório de Esterilizações por data</h4>
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
									<th width='120px'>Lote</th>
									<th colspan='2'>Data de esterilização / Etiquetagem</th>
								</tr>";
				$i = 0;
				$dataAnterior = "";
				$totalData = 0;
				$totalItensData = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;
				$data1 = (($_REQUEST['data1'] == "") ? "" : "iso_dataesterilizacao >= '" . DefaultHelper::converte_data($_REQUEST['data1']) . "'");
				$data2 = (($_REQUEST['data2'] == "") ? "" : "iso_dataesterilizacao <= '" . DefaultHelper::converte_data($_REQUEST['data2']) . "'");
				$and = (($_REQUEST['data1'] != "" && $_REQUEST['data2'] != "") ? " AND " : "");
				$vazio = (($_REQUEST['data1'] == "" && $_REQUEST['data2'] == "") ? "ses_del IS NULL" : "");
				foreach (SolicitacoesController::relSolicitacao("" . $data1 . $and . $data2 . $vazio . " AND iso_dataesterilizacao <> '0000-00-00'", "iso_dataesterilizacao, set_nome") as $ses){
					/////$set = SetoresController::getSetor($ses->ses_idsetor);
					//$itens = ItensSolicitacoesController::getItens("iso_idses = " . $ses->ses_id);
					//$dia = split(" ", $ses->ses_datasaida);
					//$dia = DefaultHelper::converte_data($dia[0]);
					$dia = $ses->ses_iso_dataesterilizacao;
					$dia = DefaultHelper::converte_data($dia);
					$dataEsterilizacao = date('d/m/Y H:i', strtotime( $ses->ses_iso_data) ) ;
					$dataEtiquetagem = date('d/m/Y', strtotime( $ses->ses_iso_dataesterilizacao) ) ;
					$horaEtiquetagem = date('H:i', strtotime( $ses->ses_iso_horaesterilizacao) ) ;

					// total em cada data
					if($dataAnterior != $dia && $i != 0){
						$html .= "	<tr>
										<td colspan='5' class='bold right'>Total de itens esterilizados em " . $dataAnterior . ": </td>
										<td class='bold left'>" . $totalData . "</td>
									</tr>";
						$totalData = 0;
						$totalItensData = 0;
					}
					$html .= "	<tr>
									<td>" . $ses->ses_set_nome . "</td>
									<td>" . $ses->ses_pro_nome . "</td>
									<td>" . $ses->ses_pro_qrcode . "</td>
									<td>" . $ses->ses_iso_lote . "</td>
									<td>" . $dataEsterilizacao . "</td>
				                    <td>" . $dataEtiquetagem ." ".$horaEtiquetagem."</td>
								</tr>";
					$dataAnterior = $dia;
					$totalData ++;
					//$totalItensData += sizeof($itens);
					$totalItensData ++;
					$totalGeral ++;
					//$totalItensGeral += sizeof($itens);
					$totalItensGeral ++;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td colspan='5' class='bold right'>Total de itens esterilizados em " . $dataAnterior . ": </td>
									<td class='bold left'>" . $totalData . "</td>
								</tr>
								<tr>
									<td colspan='5' class='bold right dark'>Total de itens esterilizados no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else {
					$html .= "<tr><td colspan='6' align='center'>Nenhum registro encontrado.</td></tr>";
				}

			} else { // SINTÉTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th>Data</th>
									<th>Setor</th>
									<th width='250px'>Total de esterilizações do setor na data</th>
								</tr>";
				$i = 0;
				$setorAnterior = "";
				$dataAnterior = "";
				$totalData = 0;
				$totalItensData = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;
				$data1 = (($_REQUEST['data1'] == "") ? "" : "iso_dataesterilizacao >= '" . DefaultHelper::converte_data($_REQUEST['data1']) . "'");
				$data2 = (($_REQUEST['data2'] == "") ? "" : "iso_dataesterilizacao <= '" . DefaultHelper::converte_data($_REQUEST['data2']) . "'");
				$and = (($_REQUEST['data1'] != "" && $_REQUEST['data2'] != "") ? " AND " : "");
				$vazio = (($_REQUEST['data1'] == "" && $_REQUEST['data2'] == "") ? "ses_del IS NULL" : "");
				foreach (SolicitacoesController::relSolicitacao("" . $data1 . $and . $data2 . $vazio . " AND iso_dataesterilizacao <> '0000-00-00'", "iso_dataesterilizacao, set_nome") as $ses){
					/////$set = SetoresController::getSetor($ses->ses_idsetor);
					//$itens = ItensSolicitacoesController::getItens("iso_idses = " . $ses->ses_id);
					//$dia = split(" ", $ses->ses_datasaida);
					//$dia = DefaultHelper::converte_data($dia[0]);
					$dia = $ses->ses_iso_dataesterilizacao;
					$dia = DefaultHelper::converte_data($dia);
					// total em cada data
					if(($dataAnterior != $dia || $setorAnterior != $ses->ses_set_nome) && $i != 0){
						$html .= "	<tr>
										<td>" . $dataAnterior . "</td>
										<td>" . $setorAnterior . "</td>
										<td>" . $totalData . "</td>
									</tr>";
						$totalData = 0;
						$totalItensData = 0;
					}
					$setorAnterior = $ses->ses_set_nome;
					$dataAnterior = $dia;
					$totalData ++;
					//$totalItensData += sizeof($itens);
					$totalItensData ++;
					$totalGeral ++;
					//$totalItensGeral += sizeof($itens);
					$totalItensGeral ++;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td>" . $dataAnterior . "</td>
									<td>" . $setorAnterior . "</td>
									<td>" . $totalData . "</td>
								</tr>
								<tr>
									<td class='bold right dark' colspan='2'>Total de esterilizações no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else {
					$html .= "<tr><td colspan='5' align='center'>Nenhum registro encontrado.</td></tr>";
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