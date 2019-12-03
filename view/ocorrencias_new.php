<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = OcorrenciasController::insert($_POST);
		else
			$res = OcorrenciasController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(OcorrenciasController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/ocorrencias.js"></script>
	
	<h1>
		Ocorrências
		<small>Novo registro</small>
	</h1>
	
	<form id="formOcorrencia">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<!--label>Sigla:</label>
		<input type="text" name="sigla" id="txSigla" maxlength="1" class="input-mini" autofocus>
		<br-->
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge">
		<br>
		<label>Descrição:</label>
		<textarea rows="3" name="descricao" id="txDescricao" maxlength="200" class="input-block-level"></textarea>
		<label>Efeito especial:</label>
		<?php 
			echo OcorrenciasHelper::populaComboEfeitoEspecial();
		?>
		<br><br>
		<label class="checkbox">
			<input type="checkbox" name="descarte" id="ckDescarte" value="N"> Esta ocorrência causa descarte do produto.
		</label>
		<br>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$metodo = OcorrenciasController::getOcorrencia($_GET['id']);
		echo OcorrenciasHelper::populaCampos($metodo);
	}
	
	include "helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/
?>