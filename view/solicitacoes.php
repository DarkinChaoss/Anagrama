<?php

	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 8));
	
	include "helper/cabecalho.php";
?>
	
	<script src="js/solicitacoes.js?112"></script>
	
	<h1>Solicitações de esterilização</h1>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th colspan="2">Setor</th>
			</tr>
		</thead>
		<tbody id="lista">
			<?php
			echo SolicitacoesHelper::listaSolicitacoesSetores();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>