<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 8));

	include "helper/cabecalho.php";

?>
<script src="js/consignado.js"></script>

<form class="form-search pull-right" id="formBusca">
	<a href="consignados" class="hide" id="btLimparBusca">Limpar busca</a>&nbsp;
	<input type="text" class="input-medium search-query" name="buscar" id="txBuscar" autofocus autocomplete="off">
	<button type="submit" class="btn" id="btBuscar"><i class="icon-search"></i></button>
	<br><br>
</form>	
<h1>Consignados</h1>

<table class="table table-hover">
	<thead>
		<tr>
			<th width="200">Nome</th>
			<th width="100">QRCode</th>
			<th width="100">Quantidade</th>
			<th></th>
			<th colspan="3"><a href="consignados_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
		</tr>
	</thead>
	<tbody id="lista">
		<?php
		echo ProdutosConsignadosHelper::listaProdutosConsignados($_REQUEST['buscar'], 1, $_REQUEST['descart']);
		?>
	</tbody>
</table>
<div id="paginacao">
	<?php
	echo ProdutosConsignadosHelper::paginacao($_REQUEST['buscar'], 1, $_REQUEST['descart']);
	?>
</div>

<?php
	function cabecalhoPagina($pag, $pags, $comTable) {
		$html .= "	<div class='onlyPrint'>
						<div>
							<img src='img/tms.png' width='100px' class='pull-left'>
							<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
						</div>
						<h4>Composição da caixa</h4>
						<br><br>
					</div>
					<br>
					<br>";
		return $html;
	}


	if($_REQUEST['buscar'] != "")
		echo "	<script>
					$('#txBuscar').val('" . $_REQUEST['buscar'] . "');
					$('#btLimparBusca').show();
				</script>";
	if($_REQUEST['descart'] == 'S')
		echo "	<script>
					$('#ckDescartados').attr('checked', true);
				</script>";

	include "helper/rodape.php";
?>