<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Métodos de esterilização</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="300">Nome</th>
				<th>Descrição</th>
				<th colspan="2"><a href="metodos_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo MetodosHelper::listaMetodos();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>