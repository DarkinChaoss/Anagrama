<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Relatório de Esterilizações por validade</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span2">
				<label class="radio">
					<input type="radio" name="tipo" value="a" checked>
					Analítico
				</label>
			</div>
			
			<div class="span4">
				<label>Intervalo:</label>
				<input type="text" name="data1" class="input-small data" maxlength="10" autocomplete="off">
				&nbsp;até&nbsp;
				<input type="text" name="data2" class="input-small data" maxlength="10" autocomplete="off">
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
							<h4>Relatório de Esterilizações por validade</h4>
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
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th width='250px'>Produto</th>
									<th width='150px'>QRCode</th>
									<th width='70px'>Solicitação</th>
									<th width='130px'>Lote</th>
									<th>Data de esterilização</th>
									<th>Validade</th>
								</tr>";
				$i = 0;
				$arr = array(); // guarda os produtos já contados para não repetir
				/////$iso = new ItensSolicitacaoModel();
				/////$pro = new ProdutosModel();
				$data1Convertida = DefaultHelper::converte_data($_REQUEST['data1']);
				$data2Convertida = DefaultHelper::converte_data($_REQUEST['data2']);
				// busca no banco sem filtro de data, pois o filtro deve ser aplicado sobre tudo para considerar apenas os itens mais recentes
				foreach (SolicitacoesController::relSolicitacao("iso_status = '1' AND iso_datalimite >= '" . $data1Convertida . "' AND iso_datalimite <= '" . $data2Convertida . "'", "iso_datalimite DESC") as $ses){
					/////$iso = ItensSolicitacoesController::getItem($ses->ses_iso_id);
					if(!in_array($ses->ses_iso_id, $arr)){
						/*
						if(
						 ($_REQUEST['data1'] == "" || ($_REQUEST['data1'] != "" && $ses->ses_iso_datalimite >= $data1Convertida))
						 &&
						 ($_REQUEST['data2'] == "" || ($_REQUEST['data2'] != "" && $ses->ses_iso_datalimite <= $data2Convertida))
						){
						*/
							$html .= "	<tr>
											<td>" . $ses->ses_pro_nome . "</td>
											<td>" . $ses->ses_pro_qrcode . "</td>
											<td>" . $ses->ses_id . "</td>
											<td>" . $ses->ses_iso_lote . "</td>
											<td>" . DefaultHelper::converte_data($ses->ses_iso_dataesterilizacao) . "</td>
											<td>" . DefaultHelper::converte_data($ses->ses_iso_datalimite) . "</td>
										</tr>";
							$i++;
						//}
						$arr[] = $ses->ses_iso_id;
					}
				}
				if($i == 0){
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
 * Brothers Soluções em T.I. © 2015
*/
?>