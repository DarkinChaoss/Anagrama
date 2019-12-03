<?php
	if (isset($_POST['descricao'])) {
		
		if(empty($_POST['id']))
			
			$res = EquipamentoController::insert($_POST);
			
		else
			$res = EquipamentoController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(EquipamentoController::delete($_GET['id']))
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

	<script src="js/equipamento.js"></script>
	
	<h1>
		Equipamentos
		<small>Novo registro</small>
	</h1>
	
	<form id="formEquipamento">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Descrição:</label>
		<input type="text" name="descricao" id="txDescricao" class="input" autofocus>
		<label>Lote Enzimático:</label>
		<input type="text" name="enzimatico" id="txenzimatico" class="input" autofocus>
		<label>Lote Neutro:</label>
		<input type="text" name="neutro" id="txneutro" class="input" autofocus><br>
		<input type="radio" name="equipamento" id="txex" value="ex"> Expurgo <input type="radio" name="equipamento" id="txet" value="et"> Etiquetagem<br><br> 
        <input type="radio" name="imprime" id="txRed" value="Red"> Reduzido <input type="radio" name="imprime" id="txCom" value="Com"> Completo
		<br><br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$equipamento = EquipamentoController::getEqupipamento($_GET['id']);
		
		echo EquipamentoHelper::populaCampos($equipamento);
	}
	
	include "helper/rodape.php";
?>