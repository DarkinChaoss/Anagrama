<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/nomesProdutos.js"></script>
	
	<form class="form-search pull-right">
		<a href="nomesProdutos" class="hide" id="btLimparBusca">Limpar busca</a>&nbsp;
		<input type="text" class="input-medium search-query" name="buscar" id="txBuscar" autofocus autocomplete="off">
		<button type="submit" class="btn" id="btBuscar"><i class="icon-search"></i></button>
	</form>
	<h1>Nomes de produtos</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nome</th>
				<th colspan="2"><a href="#" id="new_name" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody id="lista">
			<?php
			echo NomesProdutosHelper::listaNomesProdutos($_REQUEST['buscar']);
			?>
		</tbody>
	</table>
	
<?php
	if(isset($_REQUEST['buscar'])) {
		echo "	<script>
					$('#txBuscar').val('" . $_REQUEST['buscar'] . "');
					$('#btLimparBusca').show();
				</script>";
	}
	
	include "helper/rodape.php";
?>