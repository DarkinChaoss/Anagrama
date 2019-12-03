<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Usuários</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="150">Login</th>
				<th width="200">Nível de acesso</th>
				<th>Referente à</th>
				<th colspan="2"><a href="usuarios_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			     echo UsuariosHelper::listaUsuarios();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>