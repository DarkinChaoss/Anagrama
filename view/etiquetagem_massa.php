<?php

	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 8));
	include "helper/cabecalho.php";

?>
	<script src="js/etiquetagem.js?<?php echo time() ?>"></script>

	<h1>
		Etiquetagem em massa
		<a href="" id="btEtiquetagemMassa" class="btn btn-primary pull-right"> Etiquetar </a>
		<img src="img/loading.gif" width="17px" id="imgLoading" class="pull-right hide" style="margin: 5px 15px 0 0;">
	</h1>

	<h5>Materiais de solicitações pendentes a serem etiquetadas</h5>
	<div id="divPedidoEtiquetagem">
		<table class="table table-hover">
			<thead>
				<tr>
					<th width="150">QRCode</th>
					<th>Nome</th>
					<th width="200">Inserido na solicitação</th>
				</tr>
			</thead>
			<tbody id="listaItens">
				<?php
				echo ItensSolicitacaoHelper::listaItensEtiquetagem();
				?>
			</tbody>
		</table>
	</div>

<?php
	include "helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2015
*/
?>