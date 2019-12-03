<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8, 9));

	include "view/helper/cabecalho.php";

?>
		
	<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>	


	<h1>Custos de Produtos</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span4">
				<label>Data:</label>
				<input type="text" name="data" class="input-small data" maxlength="10" required autocomplete="off">
				 <label>
						Equipamento: 
						<?php 
						echo  $peo = SolicitacoesHelper::populaComboEEsterilizacao();
							
						?>
					</label>
			</div>
			

			
			<div class="pull-right">
				<button class="btn btn-primary"><i class="icon-search icon-white"></i> Pesquisar</button>
			</div>
		</div>
	</form>
	
	<hr>
	
	<?php
		if(isset($_REQUEST['data']) && $_REQUEST['data'] != "") {

			$whereqe = '';

			if($_REQUEST['eqEsterilizacao'] != 0 && $_REQUEST['eqEsterilizacao'] != ''){
				$whereqe = ' AND iso_idequipamento = '.$_REQUEST["eqEsterilizacao"].'';
			}else{
				$whereqe = '';
			}

			$lista = "	<label class='parametro'>Data: " . $_REQUEST['data'] . "</label>
						<br>
						<table class='tableLinhas'>
							<thead>
								<tr>
									<th>Lotes na data</th>
								</tr>
							</thead>
							<tbody>";
			$i = 0;
			foreach (ItensSolicitacoesController::getLotes_equipamentos("iso_dataesterilizacao = '" . DefaultHelper::converte_data($_REQUEST['data']) . "' ".$whereqe." ") as $iso) {
				$lista .= "	<tr>
								<td><a href='?lote=" . $iso->iso_loteequipamento . "&gerar=1'>" . $iso->iso_loteequipamento . "</a></td>
							</tr>";
				$i ++;
			}
			if ($i == 0) {
				$lista .= "	<tr>
								<td align='center'>Nenhum registro encontrado.</td>
							</tr>";
			}
			$lista .= "		</tbody>
						</table>";
			echo $lista;
		}
	?> 


			<a href='#' id='btPrint' class='btn pull-right onlyScreen hide'><i class='icon-print'></i> Imprimir</a>
	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressÃ£o -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		if($_REQUEST['gerar'] == 1){

			$linhasPorPag = 40;
			$arrIso = ItensSolicitacoesController::CustoControleEsterilizacao("iso_loteequipamento = '" . $_REQUEST['lote'] . "' GROUP BY pro_nome", "pro_nome");
			
			print_r($arraIso);
			

			$totalPags = ceil(sizeof($arrIso) / $linhasPorPag);
			if (sizeof($arrIso) % $linhasPorPag > $linhasPorPag - 10) {
				$rodapeSeparado = true;
				$totalPags ++;
			} else {
				$rodapeSeparado = false;
			}
			
			


			$html = "		<div class='onlyScreen'>";
			$htmlPrint = "	<div class='onlyPrint'>";
			
			$cabeca = "	<script>
							$('#btPrint').show();
						</script>" . cabecalhoPagina(1, $totalPags, true);
			$html .= $cabeca;
			$htmlPrint .= $cabeca;
			
			$i = 0;
			$totalItens = 0;
			foreach ($arrIso as $iso){
			
				$custo = $iso->custo;
				$real = DefaultHelper::real($custo);
				

				$i++;
				$linha = "	<tr>
								<td class='center'>" . $iso->n . "</td>
								<td>" . $iso->iso_pro_nome . "</td>
								<td>" . 'R$ '  . $real . "</td>
								
							</tr>";
				$html .= $linha;
				$htmlPrint .= $linha;
				if ($i % $linhasPorPag == 0) {
					$htmlPrint .= "	</tbody>
							</table>
							<div style='page-break-after: always'></div>" . cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
				}
				$totalItens += $iso->n;
				$totalcusto += $iso->custo;
				$formato_real = DefaultHelper::real($totalcusto);
				
			}
			// rodapÃ©
			if($i != 0){
				$pe = "		</tbody>
						</table>
						<br>
						<b>Total: " . $totalItens . "</b>
						<div style='float:right;'>
							<b>Total: " .'R$ ' . $formato_real . "</b>
						</div>
						";


				$peDados = "<br>
							<br>
							<div class='row-fluid'>
								<div class='span6'><label class='campoPreencher'>Programa:</label></div>
							</div>
							<div class='row-fluid'>
								<div class='span6'><label class='campoPreencher'>Início da Esterilização:</label></div>
								<div class='span6'><label class='campoPreencher'>Termino da Esterilização:</label></div>
							</div>
							<div class='row-fluid'>
								<div class='span6'><label class='campoPreencher'>Tempo de Esterilização:</label></div>
								<div class='span6'><label class='campoPreencher'>Responsável:</label></div>
							</div>
							<div class='row-fluid'>
								<div class='span12'><label class='campoPreencher'>Observações:</label></div>
							</div>
							<div class='row-fluid'>
								<div class='span12'><label class='campoPreencher'>&nbsp</label></div>
							</div>";
				$html .= $pe;
				$htmlPrint .= $pe 
							. (($rodapeSeparado) 
									? "<div style='page-break-after: always'></div>" . cabecalhoPagina($totalPags, $totalPags, false) 
									: "") 
							. $peDados;
			} else {
				$pe = "		<tr><td colspan='3' align='center'>Nenhum registro encontrado.</td></tr>
							</tbody>
						</table>";
				$html .= $pe;
				$htmlPrint .= $pe;
			}
			
			$html .= "		</div>";
			$htmlPrint .= "	</div>";
			
			echo $html . $htmlPrint;
		}
		?>
	</div>
	
<?php
	function cabecalhoPagina($pag, $pags, $comTable) {
		$html .= "	<div class='onlyPrint'>
						<div>
							<img src='img/tms.png' width='100px' class='pull-left'>
							<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
						</div>
						<h4>Custo do Produto</h4>
						<br><br>
					</div>
					<br>
					<label class='parametro'>Lote: " . (($_REQUEST['lote'] != "") ? $_REQUEST['lote'] : "Nenhum") . "</label>
					<label class='parametro pull-right onlyPrint'>Página. " . $pag . "/" . $pags . "</label>
					<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", As " . date("H:i") . "</label>
					<br>";
		if ($comTable) {
			$html .= "<table class='tableLinhas'>
						<thead>
							<tr>
								<th width='70px' style='text-align: center;'>Quantidade</th>
								<th>Descrição do material</th>
								<th width='50px'>Custo</th>

							</tr>
						</thead>
						<tbody>";
		}
		return $html;
	}
	
	
?>














<?php
	include "view/helper/rodape.php";
?>




?>

