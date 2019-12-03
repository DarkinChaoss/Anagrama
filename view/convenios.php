<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Convênios</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="250">Nome</th>
				<th>Observação</th>
				<th width="50">Ativo</th>
				<th colspan="2"><a href="convenios_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo ConveniosHelper::listaConvenios();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>