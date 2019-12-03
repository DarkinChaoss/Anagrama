<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/materiaisInternos.js"></script>
	
	<form class="form-search pull-right">
		<a href="materiaisInternos" class="hide" id="btLimparBusca">Limpar busca</a>&nbsp;
		<input type="text" class="input-medium search-query" name="buscar" id="txBuscar" autofocus>
		<button type="submit" class="btn" id="btBuscar"><i class="icon-search"></i></button>
	</form>
	<h1>Materiais internos</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="150">Código</th>
				<th>Nome</th>
				<th width="150">Qtde. em estoque</th>
				<th colspan="2"><a href="materiaisInternos_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody id="lista">
			<?php
			echo MateriaisInternosHelper::listaMateriaisInternos($_REQUEST['buscar']);
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