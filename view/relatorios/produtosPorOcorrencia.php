<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 1, 2, 4, 5, 9));

	include "view/helper/cabecalho.php";

?>

	<h1>Relatório de Produtos por ocorrência</h1>

	<form>
		<div class="row-fluid">
			<div class="span4">
				<label>Ocorrência:</label>
				<?php
					echo OcorrenciasHelper::populaComboOcorrencias();
				?>
			</div>
			<div class="span4">
				<?php
				    /* if($_SESSION['usu_nivel'] != 1){
                        echo '
		                      <label>Cliente:</label>'.
                                    ClientesHelper::populaComboClientes()
		                      .'<br>
	                    ';
				    } */
				?>

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
		if($_REQUEST['gerar'] == 1 && $_REQUEST['ocorrencia'] != 0){
			$oco = OcorrenciasController::getOcorrencia($_REQUEST['ocorrencia']);
			$html = "	<script>
							$('#btPrint').show();
						</script>
						<div class='onlyPrint'>
							<div class='row-fluid'>
								<img src='img/tms.png' width='100px' class='pull-left'>
								<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
							</div>
							<h4>Relatório de Produtos por ocorrência</h4>
						</div>
						<br>
						<label class='parametro'>
							Ocorrência: " . $oco->oco_nome . "
						</label>
						";
			//<label class='parametro'>	Cliente:
			// SE FOR CLIENTE
			/* if($_SESSION['usu_nivel'] == 1){
			    $_REQUEST['cliente'] = $_SESSION['usu_referencia'];
			}

		    if($_REQUEST['cliente'] == 0){
		        $html .= " Todos";
		    } else {
		        $cli = ClientesController::getCliente($_REQUEST['cliente']);
		        $html .= 	" " . $cli->cli_nome;
		    } </label>*/


			$html .= "	<label class='parametro'>
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
						<br>
						<table class='tableLinhas'>
							<tr>
								<th width='150px'>Data ocorrência</th>
								<th width='200px'>Produto</th>
								<th width='80px'>QRCode</th>
								<th width='200px'>Grupo</th>
								<!-- th>Hospital</th -->
							</tr>";
			$i = 0;
			$whereCliente = ""; ///($_REQUEST['cliente'] == 0) ? "" : " AND cli_id = " . $_REQUEST['cliente'] . " ";
			$data1 = (($_REQUEST['data1'] == "") ? "" : "DATE_FORMAT(opr_data, '%Y-%m-%d') >= '" . DefaultHelper::converte_data($_REQUEST['data1']) . "'");
			$data2 = (($_REQUEST['data2'] == "") ? "" : "DATE_FORMAT(opr_data, '%Y-%m-%d') <= '" . DefaultHelper::converte_data($_REQUEST['data2']) . "'");
			$and = (($_REQUEST['data1'] != "" && $_REQUEST['data2'] != "") ? " AND " : "");
			foreach (OcorrenciasProdutosController::relOcorrenciasProdutos("opr_idocorrencia = " . $_REQUEST['ocorrencia'] . $whereCliente . $and . $data1 . $and . $data2, "opr_data DESC") as $opr){
				$html .= "	<tr>
								<td>" . DefaultHelper::converte_data($opr->opr_data) . "</td>
								<td>" . $opr->opr_pro_nome . "</td>
								<td>" . $opr->opr_pro_qrcode . "</td>
								<td>" . $opr->opr_gma_nome . "</td>

							</tr>";
				$i++;
				//<td>" . $opr->opr_cli_nome . "</td>
			}
			if($i == 0){
				$html .= "	<tr><td colspan='5' align='center'>Nenhum registro encontrado.</td></tr>";
			}
			$html .= "</table>";
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
 * Brothers Soluções em T.I. © 2014
*/
?>