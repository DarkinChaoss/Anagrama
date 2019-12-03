<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Grupos de materiais</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="300">Nome</th>
				<th>Observação</th>
				<th colspan="2"><a href="gruposMateriais_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo GruposMateriaisHelper::listaGruposMateriais();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>