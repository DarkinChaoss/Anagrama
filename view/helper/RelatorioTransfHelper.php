<?php

if(isset($_POST['acao']) && $_POST['acao'] == 'buscar'){
	$itens = ItensSaidaController::selectIntensBySolicitacao($_POST['id']); //O id é da solicitação
	echo json_encode($itens);
}