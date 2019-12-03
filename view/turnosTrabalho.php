<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/turnosTrabalho.js"></script>
	
	<h1>Turnos de trabalho</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="300">Nome</th>
				<th>Início</th>
				<th>Fim</th>
				<th colspan="2"><a href="turnosTrabalho_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody id="lista">
			<?php
			echo TurnosTrabalhoHelper::listaTurnosTrabalho();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>