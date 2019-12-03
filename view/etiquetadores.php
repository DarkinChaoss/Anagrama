<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Etiquetadores</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="350">Nome</th>
				<th>Contato</th>
				<th colspan="3"><a href="etiquetadores_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo EtiquetadoresHelper::listaEtiquetadores();
			?>
		</tbody>
	</table>
	
<?php
	include "usuarioExpresso.php";
	
	include "helper/rodape.php";
?>