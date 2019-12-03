<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = MetodosController::insert($_POST);
		else
			$res = MetodosController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(MetodosController::delete($_GET['id']))
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

	<script src="js/metodos.js"></script>
	
	<h1>
		Métodos de esterilização
		<small>Novo registro</small>
	</h1>
	
	<form id="formMetodo">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
		<br>
		<label>Descrição:</label>
		<textarea rows="3" name="descricao" id="txDescricao" maxlength="200" class="input-block-level"></textarea>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$metodo = MetodosController::getMetodo($_GET['id']);
		echo MetodosHelper::populaCampos($metodo);
	}
	
	include "helper/rodape.php";
?>