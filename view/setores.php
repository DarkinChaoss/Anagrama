<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Setores</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nome</th>
				<th colspan="2"><a href="setores_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo SetoresHelper::listaSetores();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>