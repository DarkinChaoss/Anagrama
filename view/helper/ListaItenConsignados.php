<?php
	$strHtml = ItensSolicitacaoHelper::listaItensSolicitacaoConsignados($_POST['id']);
	if (empty($strHtml)){
		die("error");
	}
		die($strHtml);
?>