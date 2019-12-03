<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = ArsenaisController::insert($_POST);
		else
			$res = ArsenaisController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(ArsenaisController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/arsenal.js"></script>
	
	<h1>
		Arsenais
		<small>Novo registro</small>
	</h1>
	
	<form id="formArsenal">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
		<br>
		<label>Contato:</label>
		<input type="text" name="contato" id="txContato" maxlength="50" class="input-xlarge">
		<br>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$arsenal = ArsenaisController::getArsenal($_GET['id']);
		echo ArsenaisHelper::populaCampos($arsenal);
	}
	
	include "helper/rodape.php";
?>