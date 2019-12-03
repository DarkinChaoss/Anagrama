<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Relatório de Produtos por nome</h1>
	
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
							<h4>Relatório de Produtos por nome</h4>
						</div>
						<br>
						<label class='parametro'>Tipo: " . (($_REQUEST['tipo'] == "a") ? "Analítico" : "Sintético") . "</label>
						<label class='parametro'>Filtro: " . (($_REQUEST['filtro'] != "") ? '"'.$_REQUEST['filtro'].'"' : "Nenhum") . "</label>
						<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
						<br>";
			
			if($_REQUEST['tipo'] == "a"){ // ANALÍTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th width='150px'>Produto</th>
									<th width='80px'>QRCode</th>
									<th>Grupo</th>
									<th width='50px'>Reuso</th>
									<th width='50px'>Restante</th>
									<th width='70px'>Validade</th>
									<th>Setor</th>
								</tr>";
				$i = 0;
				$nomeAnterior = "";
				$totalNome = 0;
				$totalGeral = 0;
				foreach (ProdutosController::relProdutos("pro_nome LIKE '%" . $_REQUEST['filtro'] . "%'", "pro_nome, pro_idsetor", true) as $pro){
					//$gma = GruposMateriaisController::getGrupoMateriais($pro->pro_idgrupomateriais);
					//$reuso = ItensSolicitacoesController::getReprocessamentoItem($pro->pro_id);
					//$set = SetoresController::getSetor($pro->pro_idsetor);
					
					// total em cada nome
					if($nomeAnterior != $pro->pro_nome && $i != 0){
						$html .= "	<tr>
										<td colspan='6' class='bold right'>Total de produtos " . $nomeAnterior .  ": </td>
										<td class='bold left'>" . $totalNome . "</td>
									</tr>";
						$totalNome = 0;
					}
					$html .= "	<tr>
									<td>" . $pro->pro_nome . "</td>
									<td>" . $pro->pro_qrcode . "</td>
									<td>" . $pro->pro_gma_nome . "</td>
									<td>" . $pro->pro_reuso . "/" . $pro->pro_maxqtdprocessamento . "</td>
									<td>" . $pro->pro_restante. "</td>
									<td>" . DefaultHelper::converte_data($pro->pro_validacaofabricacao) . "</td>
									<td>" . $pro->pro_set_nome . "</td>
								</tr>";
					$nomeAnterior = $pro->pro_nome;
					$totalNome++;
					$totalGeral++;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td colspan='6' class='bold right'>Total de produtos " . $nomeAnterior . ": </td>
									<td class='bold left'>" . $totalNome . "</td>
								</tr>
								<tr>
									<td colspan='6' class='bold right dark'>Total de produtos no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else {
					$html .= "<tr><td colspan='7' align='center'>Nenhum registro encontrado.</td></tr>"; 
				}
				
			} else { // SINTÉTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th>Produto</th>
									<th>Total de produtos</th>
								</tr>";
				$i = 0;
				$nomeAnterior = "";
				$totalNome = 0;
				$totalGeral = 0;
				foreach (ProdutosController::relProdutos("pro_nome LIKE '%" . $_REQUEST['filtro'] . "%'", "pro_nome, pro_idsetor") as $pro){
					// total em cada produto
					if($nomeAnterior != $pro->pro_nome && $i != 0){
						$html .= "	<tr>
										<td>" . $nomeAnterior . "</td>
										<td>" . $totalNome . "</td>
									</tr>";
						$totalNome = 0;
					}
					$nomeAnterior = $pro->pro_nome;
					$totalNome++;
					$totalGeral++;
					$i++;
				}
				// último total
				if($i != 0){
					$html .= "	<tr>
									<td>" . $nomeAnterior . "</td>
									<td>" . $totalNome . "</td>
								</tr>
								<tr>
									<td class='bold right dark'>Total de produtos no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else {
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