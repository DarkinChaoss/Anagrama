<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	include "view/helper/cabecalho.php";
?>

	<h1>Produtos Descartados</h1>

	<form>
		<div class="row-fluid">
			<div class="span7">

				<label>
					Periodo:
    				<input type="text" name="data1" class="input-small data" maxlength="10" autocomplete="off">
    				&nbsp;até&nbsp;
    				<input type="text" name="data2" class="input-small data" maxlength="10" autocomplete="off">
				</label>
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
		if( $_REQUEST['gerar'] == 1 ){

		    $dataInicio = DefaultHelper::converte_data($_REQUEST['data1']);
		    $dataFinal = DefaultHelper::converte_data($_REQUEST['data2']);
		    $arrProdutos = ProdutosController::getProdutosDescartados($dataInicio, $dataFinal);

		    echo '
		      <script> $("#btPrint").show(); </script>

		      <div class="onlyPrint">
		          <div class="row-fluid">
					<img src="img/tms.png" width="100px" class="pull-left">
					<img src="img/logoEmpresa.png" width="120px" class="pull-right">
		   		 </div>
                 <h4>Relatório de Produtos Descartados'.( $dataInicio != '//'? '<br> Entre '.$_REQUEST['data1'].' e '.$_REQUEST['data2'].'.'  : '.' ).' </h4>

		      </div>
		      <table class="tableLinhas">
		        <tr>
					<th>Produto</th>
					<th width="80px">QRCode</th>
					<th width="250px">Motivo Descarte</th>
					<th width="130px">Data Descarte</th>
				</tr>
		    ';

		    foreach ( $arrProdutos as $produto){
                echo '<tr>
				        <td> '.$produto->pro_nome.' </td>
        			    <td> '.$produto->pro_qrcode.' </td>
        			    <td> '.$produto->pro_oco_nome.' </td>
        			    <td> '.DefaultHelper::converte_data($produto->pro_opr_data).' </td>
				    </tr>';
		    }

		    if(sizeof($arrProdutos) == 0){
		        echo '<tr> <td colspan="4"> Nenhum registro encontrado ! </td> </tr>';
		    }

            echo '
		           <tr>
					<th colspan="3">Totdal de Produtos no Relatório</th>
			        <th>'.sizeof($arrProdutos).'</th>
				</tr>
                </table>
            ';

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