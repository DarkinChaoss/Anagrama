<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Relatório de Esterilizações por lote</h1>
	
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
				<label>Filtrar:</label>
				<input type="text" class="input-large" name="filtro" autocomplete="off">
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
							<h4>Relatório de Esterilizações por lote</h4>
						</div>
						<br>
						<label class='parametro'>Tipo: " . (($_REQUEST['tipo'] == "a") ? "Analítico" : "Sintético") . "</label>
						<label class='parametro'>Filtro: " . (($_REQUEST['filtro'] != "") ? '"'.$_REQUEST['filtro'].'"' : "Nenhum") . "</label>
						<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
						<br>";
			
			if($_REQUEST['tipo'] == "a"){ // ANALÍTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th width='70px'>Número</th>
									<th width='400px'>Setor</th>
									<th width='120px'>Lote</th>
									<th>Data de esterilização</th>
									<th width='100px'>Qtde. de itens</th>
								</tr>";
				$i = 0;
				$loteAnterior = "";
				$totalLote = 0;
				$totalItensLote = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;
				foreach (SolicitacoesController::relSolicitacao("iso_lote LIKE '%" . $_REQUEST['filtro'] . "%' GROUP BY iso_lote", "iso_lote, ses_id") as $ses){
					/////$set = SetoresController::getSetor($ses->ses_idsetor);
					/////$itens = ItensSolicitacoesController::getItens("iso_idses = " . $ses->ses_id);
					$itens = ItensSolicitacoesController::getCountItens("iso_idses = " . $ses->ses_id . " AND iso_lote = '" . $loteAnterior . "'");
					// total em cada lote
					if($loteAnterior != $ses->ses_iso_lote && $i != 0){
						$html .= "	<tr>
										<td colspan='2' class='bold right'>Total de solicitações em " . $loteAnterior . ": </td>
										<td class='bold left'>" . $totalLote . "</td>
										<td class='bold right'>Total de itens: </td>
										<td class='bold left'>" . $totalItensLote . "</td>
									</tr>";
						$totalLote = 0;
						$totalItensLote = 0;
					}
					$html .= "	<tr>
									<td>" . $ses->ses_id . "</td>
									<td>" . $ses->ses_set_nome . "</td>
									<td>" . $ses->ses_iso_lote . "</td>
									<td>" . DefaultHelper::converte_data($ses->ses_datasaida) . "</td>
									<td>" . $itens . "</td>
								</tr>";
					$loteAnterior = $ses->ses_iso_lote;
					$totalLote ++;
					$totalItensLote += $itens;
					$totalGeral ++;
					$totalItensGeral += $itens;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td colspan='2' class='bold right'>Total de solicitações em " . $loteAnterior . ": </td>
									<td class='bold left'>" . $totalLote . "</td>
									<td class='bold right'>Total de itens: </td>
									<td class='bold left'>" . $totalItensLote . "</td>
								</tr>
								<tr>
									<td colspan='2' class='bold right dark'>Total de solicitações no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
									<td class='bold right dark'>Total de itens: </td>
									<td class='bold left dark'>" . $totalItensGeral . "</td>
								</tr>";
				} else {
					$html .= "<tr><td colspan='5' align='center'>Nenhum registro encontrado.</td></tr>"; 
				}
				
			} else { // SINTÉTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th>Lote</th>
									<th>Total de solicitações</th>
									<th>Total de itens esterilizados</th>
								</tr>";
				$i = 0;
				$loteAnterior = "";
				$totalLote = 0;
				$totalItensLote = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;
				foreach (SolicitacoesController::relSolicitacao("iso_lote LIKE '%" . $_REQUEST['filtro'] . "%' GROUP BY ses_id", "iso_lote, ses_id") as $ses){
					/////$set = SetoresController::getSetor($ses->ses_idsetor);
					/////$itens = ItensSolicitacoesController::getItens("iso_idses = " . $ses->ses_id);
					$itens = ItensSolicitacoesController::getCountItens("iso_idses = " . $ses->ses_id);
					// total em cada setor
					if($loteAnterior != $ses->ses_iso_lote && $i != 0){
						$html .= "	<tr>
										<td>" . $loteAnterior . "</td>
										<td>" . $totalLote . "</td>
										<td>" . $totalItensLote . "</td>
									</tr>";
						$totalLote = 0;
						$totalItensLote = 0;
					}
					$loteAnterior = $ses->ses_iso_lote;
					$totalLote ++;
					$totalItensLote += $itens;
					$totalGeral ++;
					$totalItensGeral += $itens;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td>" . $loteAnterior . "</td>
									<td>" . $totalLote . "</td>
									<td>" . $totalItensLote . "</td>
								</tr>
								<tr>
									<td class='bold right dark'>Total de solicitações e itens no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
									<td class='bold left dark'>" . $totalItensGeral . "</td>
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