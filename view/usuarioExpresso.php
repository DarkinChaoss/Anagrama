<script src="js/usuarioExpresso.js"></script>

<!-- Tela Criar Usuário, cadastro expresso direto do cadastro da pessoa -->
<form id="formCriarUsuario">
	<div id="tlaCriarUsuario" class="modal hide fade" style="width: 400px; height: 350px;">
		<div class="modal-header">
			<a id="fechaTelaCriarUsuario" class="close" data-dismiss="modal">X</a>
			<h3>Criar Usuário</h3>
		</div>
		<div class="modal-body" style="width: 2050px; height: 4050px;">
			<input type="hidden" name="referencia" id="txIdPessoa" class="input-mini">
			<input type="hidden" name="nivel" id="txNivel" class="input-mini">
			<label>Login:</label>
			<input type="text" name="login" id="txLoginX" maxlength="20" class="input-large" autofocus>
			<br>
			<label>Senha:</label>
			<input type="password" name="senha" id="txSenhaX" maxlength="20" class="input-large">
			<br>
			<label>Confirmar senha:</label>
			<input type="password" name="senha2" id="txSenhaX2" maxlength="20" class="input-large">
			<div style="margin: 50px 0 0 170px;">
				<a href="#" class="btn btn-success" id="btSalvarUsuario"><i class="icon-ok icon-white"></i> Salvar</a>
				<a href="#" class="btn btn-danger" id="btCancelarUsuario" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
	</div>
</form>