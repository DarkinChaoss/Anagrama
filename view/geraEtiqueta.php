<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	//error_log("- - - > PERMISSAO");
	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 8));

	//error_log("- - - > CABECALHO");
	include "helper/cabecalho.php";
	//<input type="text" id="txQrcode" class="input-medium pull-right" placeholder="Buscar produto..." onkeypress="return noenter()" autofocus/>
?>

	<script src="js/etiquetagem.js?<?php echo time() ?>"></script>

	<h1>
		Impressão de Etiqueta.
		<form id='etiqueta' name='etiquetaFree'>
		<a href="#" class="btn pull-right" id="btGeraEtiqueta"><i class="icon-print"></i> Gerar Etiqueta </a>
		<input type="text" id="txQrcodeEtiqueta" class="input-medium pull-right" placeholder="Buscar produto..." style="margin-right: 5px" autofocus/>
		<img src="img/loading.gif" width="17px" id="imgLoading" class="pull-right hide" style="margin: 5px 15px 0 0;">
		<br>
		</form>
	</h1>

	

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