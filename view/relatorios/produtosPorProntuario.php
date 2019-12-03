<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Relatório de Produtos por prontuário</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span4">
				<label>Prontuário:</label>
				<input type="text" class="input-large" name="prontuario">
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
			
			$sma = SaidaMateriaisController::getSaidaMateriaisByProntuario($_REQUEST['prontuario']);
			if ($sma->sma_id > 0) {
				$html .= "	<div class='onlyPrint'>
								<div class='row-fluid'>
									<img src='img/tms.png' width='100px' class='pull-left'>
									<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
								</div>
								<h4>Relatório de Produtos por prontuário</h4>
							</div>
							<br>
							<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
							<br>";
		
				$html .= "	<table class='tableLinhas' style='font-size: 1em;'>
								<tr>
									<td>Prontuário: " . $sma->sma_prontuario . "</td>
									<td colspan='2'>Paciente: " . $sma->sma_paciente . "</td>
								</tr>
								<tr>
									<td width='26%'>Sala: " . $sma->sma_sala . "</td>
									<td width='37%'>Setor: " . $sma->sma_set_nome . "</td>
									<td width='37%'>Convenio: " . $sma->sma_cvn_nome . "</td>
								</tr>
							</table>
	
							<br>
	
							<table class='tableLinhas'>
								<tr>
									<th width='140'>Data</th>
									<th>Produto</th>
									<th width='110'>Lote esterilização</th>
									<th width='100'>Val. esterilização</th>
									<th width='30'>Reuso</th>
								</tr>";
				foreach(ItensSaidaController::getItensSaida("isa_idsaida = " . $sma->sma_id) as $isa){
					$pro = ProdutosController::getProduto($isa->isa_idproduto);
					$infoProduto = $pro->pro_nome
									. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "")
									. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
									. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
									. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");
					$html .= "	<tr>
									<td>" . DefaultHelper::converte_data($isa->isa_data) . "</td>
									<td>" . $pro->pro_qrcode . "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;" . $infoProduto . "</td>
									<td>" . $isa->isa_lote . "</td>
									<td>" . DefaultHelper::converte_data($isa->isa_validade) . "</td>
									<td align='center'>" . $isa->isa_reuso . "</td>
								</tr>";
				}
				$html .=  "</table>";
			}
			else {
				$html .= "<div style='width: 100%; text-align: center;'>Prontuário <b>" . $_REQUEST['prontuario'] . "</b> não encontrado.</div>";
			}
			
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