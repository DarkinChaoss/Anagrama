<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = ContatosController::insert($_POST);
		else
			$res = ContatosController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	elseif(isset($_GET['populate'])){
		$contato = ContatosController::getContato($_GET['id']);
		echo ContatosHelper::alimentaContato($contato);
	}
	
	elseif(isset($_GET['delete'])){
		if(ContatosController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	elseif(isset($_GET['setor'])){
		die(ContatosHelper::listaContatos($_GET['setor']));
	}
	
	elseif(isset($_GET['marcar'])){
		die(ContatosController::marcarContato($_GET['id']));
	}
?>