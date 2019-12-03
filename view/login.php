<?php
	if(isset($_POST['login'])){
		if(AutenticaController::login(strtoupper($_POST['login']), strtoupper($_POST['senha'])))
			die($_SESSION['usu_login']);
		else
			die("ERRO");
	}
	
	elseif(isset($_POST['logout'])){
		AutenticaController::logout();
	}
?>