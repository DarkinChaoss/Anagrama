<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8));
	
	include "helper/cabecalho.php";
?>
	
	<script src="js/ocorrencias.js"></script>
	
	<h1>
		Lançar Ocorrência
		<a href="#" class="btn btn-danger pull-right hide" id="btVoltar"><i class="icon-arrow-left icon-white"></i> Voltar</a>
	</h1>
	
	<form id="formProduto">
		<input type="hidden" id="idProduto">
		<label>Produto (QRCode):</label>
		<input type="text" name="qrcode" id="txQrcode" class="input-medium" onkeypress="return noenter()" autofocus>
		<a id="lbProdutoNaoCadastrado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto não cadastrado!</a>
		<a id="lbProdutoDescartado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto descartado!</a>
		<br>
	</form>
	
	<!-- Tela Lançar Ocorrência -->
	<form id="formLancarOcorrencia">
		<div id="tlaLancarOcorrencia" class="modal hide fade">
			<div class="modal-header">
				<h3>Lançar Ocorrência</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 500px;">
				<h4 id="produtoAlvo"></h4>
				<h5 id="produtoPai"></h5>
				<div id="divOcorrenciasProduto" style="width: 100%; height: 150px; background: #dfdfdf; border: 1px solid #cbcbcb; overflow: auto;"></div>
				<br>
				<label>
					Nova ocorrência:
					<?php
					echo OcorrenciasHelper::populaComboOcorrencias();
					?>
				</label>
				<label>Descrição: <span id="descricaoOcorrencia"></span></label>
				<br>
				<label id="lbObs" class="hide">
					Obs.:<br>
					<input type="text" class="input-block-level" name="obs" id="txObs">
				</label>
				<label id="lbProduct" class="hide">
					Produto pai:<br>
					<input type="text" class="input-block-level" name="produtopai" readonly id="ProdutoPai">
					<input type="hidden" class="input-block-level" name="produtopaiid" id="ProdutoPaiId">
				</label>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" id="btConfirmarOcorrencia"><i class="icon-ok icon-white"></i> Confirmar</a>
				<a href="#" class="btn btn-danger" id="btCancelarOcorrencia" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
	</form>
	
<?php
	include "helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2015
*/
?>