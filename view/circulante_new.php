<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = CirculantesController::insert($_POST);
		else
			$res = CirculantesController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(CirculantesController::delete($_GET['id']))
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

	<script src="js/circulante.js"></script>
	
	<h1>
		Circulantes
		<small>Novo registro</small>
	</h1>
	
	<form id="formCirculante">
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
		$circulante = CirculantesController::getArsenal($_GET['id']);
		echo CirculantesHelper::populaCampos($circulante);
	}
	
	include "helper/rodape.php";
?>