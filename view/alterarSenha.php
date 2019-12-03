<?php
	if (isset($_POST['senhaAtual'])) {
		$res = UsuariosController::alterarSenha($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	include "helper/cabecalho.php";
?>
	
	<script src="js/usuarios.js"></script>
	
	<h1>Alterar senha de usuário</h1>
	
	<form id="formAlterarSenha" class="row-fluid">
		<div class="span5">
			<input type="hidden" name="id" id="txId" class="input-mini" readonly>
			<label>Login:</label>
			<input type="text" name="login" id="txLoginU" maxlength="20" class="input-large" readonly>
			<br>
			<label>Referente à:</label>
			<input type="text" name="referencia" id="txReferencia" maxlength="50" class="input-xlarge" readonly>
			<br>
			<label>Nível de acesso:</label>
			<select name="nivel" id="slNivel" class="input-large" disabled="disabled">
				<?php 
				echo "	<option value='2' " . (($_SESSION['usu_nivel'] == 2) ? "selected" : "") . ">REPROCESSADOR</option>
						<option value='3' " . (($_SESSION['usu_nivel'] == 3) ? "selected" : "") . ">ETIQUETADOR</option>
						<option value='4' " . (($_SESSION['usu_nivel'] == 4) ? "selected" : "") . ">ADMINISTRADOR</option>
						<option value='5' " . (($_SESSION['usu_nivel'] == 5) ? "selected" : "") . ">MASTER CLIENT</option>";
				?>
			</select>
		</div>
		<div class="span5">
			<label>Senha atual:</label>
			<input type="password" name="senhaAtual" id="txSenhaAtual" maxlength="20" class="input-large" autofocus>
			<br>
			<label>Nova senha:</label>
			<input type="password" name="novaSenha" id="txNovaSenha" maxlength="20" class="input-large">
			<br>
			<label>Confirme a nova senha:</label>
			<input type="password" name="novaSenha2" id="txNovaSenha2" maxlength="20" class="input-large">
			<br><br>
			<a href="#" class="btn btn-success" id="btAlterar"><i class="icon-ok icon-white"></i> Alterar</a>
			<a href="#" class="btn btn-danger" id="btCancelarAlterar"><i class="icon-remove icon-white"></i> Cancelar</a>
		</div>
	</form>
	
<?php
	// popula campos
	$usuario = UsuariosController::getUsuario($_SESSION['usu_id']);
	$ref = UsuariosHelper::getReferencia($_SESSION['usu_nivel'], $usuario->usu_referencia);
	echo UsuariosHelper::populaAlterarSenha($ref);
	
	include "helper/rodape.php";
?>