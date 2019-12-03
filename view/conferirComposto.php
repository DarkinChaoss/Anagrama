<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 8));

	include "helper/cabecalho.php";


?>

	<h1>Confer&ecirc;ncia de Material <small>(ap&oacute;s a Esteriliza&ccedil;&atilde;o)</small></h1>

	<h4>
		<form id="formBusca">
			<label>QRCode:</label>
			<input type="text" name="qrcode" id="txqrcodebusca" class="input-medium" autofocus>
			<button title="Buscar" type="button" onclick="buscaMaterial()" class="btn btn-primary" style="margin-top: -10px;">
				<i class="icon-search icon-white"></i>
			</button>		
			<button onclick="$('#formBusca').reset();" class="btn btn-warning" style="margin-top: -10px;">
				<i class="icon-remove icon-white"></i>
			</button>
		</form>
	</h4>

	<script type="text/javascript" src='js/conferindoComposto.js'></script>

<?php

	include "helper/rodape.php";
	/*
	 * Desenvolvido por Weslen Augusto Marconcin
	 *
	 * Brothers Soluções em T.I. © 2017
	*/