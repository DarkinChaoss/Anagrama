<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = ProducaoController::insert($_POST);
		else
			$res = ProducaoController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(ProducaoController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5, 8));
	
	include "helper/cabecalho.php";
?>

	<script src="js/producao.js"></script>
	
	<h1>
		Produ��o
		<small>Novo registro</small>
	</h1>
	
	<form id="formProducao">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$producao = ProducaoController::getProducao($_GET['id']);

		echo ProducaoHelper::populaCampos($producao);
	}
	
	include "helper/rodape.php";
?>