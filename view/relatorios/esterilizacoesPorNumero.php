<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Relat�rio de Esteriliza��es por n�mero</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span2">
				<label class="radio">
					<input type="radio" name="tipo" value="a" checked>
					Anal�tico
				</label>
			</div>
			
			<div class="span4">
				<label>Filtrar:</label>
				<input type="text" class="input-large" name="filtro">
			</div>
			
			<div class="pull-right">
				<button class="btn btn-primary" name="gerar" value="1"><i class="icon-file icon-white"></i> Gerar relat�rio</button>
				<a href="#" id="btPrint" class="btn hide"><i class="icon-print"></i> Imprimir</a>
			</div>
		</div>
	</form>
	
	<hr>
	
	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impress�o -->
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
							<h4>Relat�rio de Esteriliza��es por n�mero</h4>
						</div>
						<br>
						<label class='parametro'>Tipo: " . (($_REQUEST['tipo'] == "a") ? "Anal�tico" : "Sint�tico") . "</label>
						<label class='parametro'>Filtro: " . (($_REQUEST['filtro'] != "") ? '"'.$_REQUEST['filtro'].'"' : "Nenhum") . "</label>
						<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", �s " . date("H:i") . "</label>
						<br>";
			
			if($_REQUEST['tipo'] == "a"){ // ANAL�TICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th width='70px'>N�mero</th>
									<th width='400px'>Setor</th>
									<th width='120px'>Lote</th>
									<th>Data de esteriliza��o</th>
									<th width='100px'>Qtde. de itens</th>
								</tr>";
				$i = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;
				$where = (($_REQUEST['filtro'] != "") ? "ses_id = " . $_REQUEST['filtro'] : "ses_del IS NULL");
				foreach (SolicitacoesController::relSolicitacao($where . " GROUP BY ses_id", "ses_id") as $ses){
					$set = SetoresController::getSetor($ses->ses_idsetor);
					$itens = ItensSolicitacoesController::getItens("iso_idses = " . $ses->ses_id);
					$html .= "	<tr>
									<td>" . $ses->ses_id . "</td>
									<td>" . $set->set_nome . "</td>
									<td>" . $ses->ses_iso_lote . "</td>
									<td>" . DefaultHelper::converte_data($ses->ses_datasaida) . "</td>
									<td>" . sizeof($itens) . "</td>
								</tr>";
					$totalGeral ++;
					$totalItensGeral += sizeof($itens);
					$i++;
				}
				// total
				if($i != 0){
					$html .= "	<tr>
									<td colspan='2' class='bold right dark'>Total de solicita��es no relat�rio: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
									<td class='bold right dark'>Total de itens: </td>
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
 * Brothers Solu��es em T.I. � 2013
*/
?>