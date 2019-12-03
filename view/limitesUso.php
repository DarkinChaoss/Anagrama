<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Limites de uso</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Descrição</th>
				<th colspan="2"><a href="limitesUso_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo LimitesUsoHelper::listaLimitesUso();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>