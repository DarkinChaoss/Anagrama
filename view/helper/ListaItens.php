<?php
	$strHtml = ItensSolicitacaoHelper::listaItensSolicitacao($_POST['id']);
	if (empty($strHtml)){
		die("error");
	}
		die($strHtml);
?>