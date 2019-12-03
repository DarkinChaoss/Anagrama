<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	
	<h1>Seção de Esterilização</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span4">
				<label>Ano:</label>
				<input type="text" name="ano" class="input-small" maxlength="4" required autocomplete="off">
			</div>
			
			<div class="pull-right">
				<button class="btn btn-primary" name="gerar" value="1"><i class="icon-file icon-white"></i> Gerar relatório</button>
				<a href="#" id="btPrint" class="btn hide"><i class="icon-print"></i> Imprimir</a>
			</div>
		</div>
	</form>
	
	<hr>
	
	<?php
		if(isset($_REQUEST['data']) && $_REQUEST['data'] != "") {
			$lista = "	<label class='parametro'>Ano: " . $_REQUEST['ano'] . "</label>
						<br>
						<table class='tableLinhas'>
							<thead>
								<tr>
									<th>Lotes na data</th>
								</tr>
							</thead>
							<tbody>";
			$i = 0;
			foreach (ItensSolicitacoesController::getLotes("iso_dataesterilizacao = '" . DefaultHelper::converte_data($_REQUEST['data']) . "'") as $iso) {
				$lista .= "	<tr>
								<td><a href='?lote=" . $iso->iso_lote . "&gerar=1'>" . $iso->iso_lote . "</a></td>
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
							<h4>Seção de Esterilização de Materiais</h4>
						</div>
						<br>
						<label class='parametro'>Ano: " . $_REQUEST['ano'] . "</label>
						<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
						<br>";
			
			$arr = ItensSolicitacoesController::relSecaoEsterilizacao($_REQUEST['ano']);
			
			// Métodos
			$arrMetodos = array();
			$arrTotais = array();
			foreach ($arr as $a) {
				foreach ($a["arr"] as $b) {
					$arrMetodos[$b["metodo"]] = array($b["metodo"] => ($arrMetodos[$b["metodo"]][$b["metodo"]] == 1) ? 2 : 1);
					$arrTotais[$b["metodo"]] = array("caixas" => 0, "pacotes" => 0);
				}
			}
			$cols = sizeof($arrMetodos) * 2;
			$wCol = 85 / $cols;
			
			$html .= "	<table class='tableLinhas'>
							<tr>
								<th width='15%' rowspan='2'>Mês</th>";
			foreach ($arrMetodos as $m) {
				$html .= "		<th colspan='2' style='text-align: center;'>" . key($m) . "</th>";
			}
			$html .= "		</tr>
							<tr>";
			foreach ($arrMetodos as $m) {
				$html .= "	<th style='text-align: center; width: " . $wCol . "%;'>Caixas</th>
							<th style='text-align: center; width: " . $wCol . "%;'>Pacotes</th>";
			}
			$html .= "		</tr>";
			
			foreach ($arr as $a) {
				$html .= "	</tr>";
				$html .= "		<td>" . $a["mes"] . "</td>";
				
				// Mês vazio
				if (sizeof($a["arr"]) == 0) {
					for ($i = $cols; $i > 0; $i --) {
						$html .= "	<td></td>";
					}
				}
				// Mês com dados
				else {
					foreach ($arrMetodos as $m) {
						$nMet = 0;
						foreach ($a["arr"] as &$b) {
							if ($nMet < 2) {
								if ($b["metodo"] == key($m) && $b["caixas"] > 0) {
									$html .= "	<td style='text-align: center;'>" . $b["caixas"] . "</td>";
									$arrTotais[key($m)]["caixas"] += $b["caixas"];
									$b["caixas"] = 0;
									$nMet ++;
								} elseif ($b["metodo"] == key($m) && $b["pacotes"] > 0) {
									if ($nMet == 0) {
										$html .= "	<td></td>";
										$nMet ++;
									}
									$html .= "	<td style='text-align: center;'>" . $b["pacotes"] . "</td>";
									$arrTotais[key($m)]["pacotes"] += $b["pacotes"];
									$nMet ++;
								}
							}
						}
						for ($i = $nMet; $i < 2; $i ++) {
							$html .= "	<td></td>";
						}
					}
				}
				$html .= "	</tr>";
			}
			
			// Totais
			$html .= "		<tr style='background: #cfcfcf;'>
								<td><b>Totais</b></td>";
			foreach ($arrMetodos as $m) {
				$html .= "		<td style='text-align: center;'><b>" . $arrTotais[key($m)]["caixas"] . "</b></td>
								<td style='text-align: center;'><b>" . $arrTotais[key($m)]["pacotes"] . "</b></td>";
			}
			$html .=  "		</tr>
						</table>";
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