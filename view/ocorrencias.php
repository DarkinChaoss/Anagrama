<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));
	
	include "helper/cabecalho.php";
?>

	<h1>Ocorr�ncias</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="200">Nome</th>
				<th>Descri��o</th>
				<th width="50">Descarte</th>
				<th colspan="2"><a href="ocorrencias_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo OcorrenciasHelper::listaOcorrencias();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Solu��es em T.I. � 2013
*/
?>