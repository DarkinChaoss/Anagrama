<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 8));

	include "helper/cabecalho.php";
?>
	<script src="js/produtos.js"></script>

	<form class="form-search pull-right" id="formBusca">
		<a href="produtos<?php echo ($_REQUEST['descart'] == 'S') ? "?descart=".$_REQUEST['descart'] : "" ; ?>" class="hide" id="btLimparBusca">Limpar busca</a>&nbsp;
		<input type="text" class="input-medium search-query" name="buscar" id="txBuscar" autofocus autocomplete="off" >
		<button type="submit" class="btn" id="btBuscar"><i class="icon-search"></i></button>
		<br><br>
		<label class="checkbox pull-right">
			<input type="checkbox" name="descart" id="ckDescartados" value="S"> Mostrar produtos descartados
	    </label>
	</form>
	<h1>Produtos</h1>

	<table class="table table-hover">
		<thead>
			<tr>
				<th width="200">Nome</th>
				<th width="100">QRCode</th>
				<th width="100">Quantidade</th>
				<th width="60">Reuso</th>
				<th></th>
				<th colspan="3"><a href="produtos_new" class="btn btn-primary pull-right"><i class="icon-plus icon-white"></i> Novo registro</a></th>
			</tr>
		</thead>
		<tbody id="lista">
			<?php
			echo ProdutosHelper::listaProdutos($_REQUEST['buscar'], 1, $_REQUEST['descart']);
			?>
		</tbody>
	</table>
	<div id="paginacao">
		<?php
		echo ProdutosHelper::paginacao($_REQUEST['buscar'], 1, $_REQUEST['descart']);
		?>
	</div>

	<!-- Tela Produto Composto -->
	<form id="formComposicao">
		<div id="telaComposicao" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaComposicao" class="close" data-dismiss="modal">X</a>
				<h3>Produto Composto</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<input type="hidden" id="txIdPai" value="0">
				<input type="hidden" id="txIdSetor" value="0">
				<div id="divQtdeItens" class="pull-right" style="font-weight: bold;"></div>
				<label>QRCode: <span id="txQrcode"></span></label>
				<br>
				<label>Produto: <span id="txProduto"></span></label>
				<br>
				<button id="btPrintcomposto" class="btn btn-success float-right onlyScreen hide">Imprimir</button>
				<br>
				<br>
				<div id="divProdutosFilhos" style="width: 100%; height: 300px; background: #dfdfdf; border: 1px solid #cbcbcb; overflow: auto;">
				</div>
				<!--Esta div serve apenas para imprimir os produtos compostos-->
				<div id="containerdivPrintcomposto">
				    <?php 
						$html = "		<div class='onlyScreen'>";
						$htmlPrint = "	<div class='onlyPrint'>";
						
						$cabeca = "	<script>

										$('#btPrintcomposto').show();
									</script>" . cabecalhoPagina(1, 1, true);
						$html .= $cabeca;
						$htmlPrint .= $cabeca;

						$html .= "		</div>";
						$htmlPrint .= "	</div>";
						
						echo $html . $htmlPrint;	
					?>
					<div  class="onlyPrint">
				        <div id="divQtdeItenscomposto" class="pull-right" style="font-weight: bold;"></div>	
						<label>Produto: <span id="txProdutocomposto"></span></label>
						<br>					
						<label>QRCode: <span id="txQrcodecomposto"></span></label>
						<br>
					</div>
					<div id="divPrintcomposto" class="onlyPrint">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#telaAdicionarProduto" class="btn btn-primary" id="btAdicionarProduto"><i class="icon-plus icon-white"></i> Adicionar produto</a>
				<a href="#" class="btn btn-danger" id="btFecharComposto" data-dismiss="modal"><i class="icon-remove icon-white"></i> Fechar</a>
			</div>
		</div>
	</form>

	<!-- Tela Adicionar Produto -->
	<form id="formAdicionarProduto">
		<div id="telaAdicionarProduto" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaAdicionarProduto" class="close" data-dismiss="modal">X</a>
				<h3>Adicionar Produto</h3>
			</div>
			<br>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<label>
					QRCode:
					<input type="text" id="txQrcodeAdicionar" maxlength="15" class="input-large" onkeypress="return noenter()" value="" autofocus>
				</label>
				<br>
				<div id="divRestoItem" class="hide">
				</div>
			</div>
			<div class="modal-footer">
				<a id="lbProdutoNaoCadastrado" class="hide" style="color: red; font-weight: bold;">Produto não cadastrado! </a>
				<a href="#" class="btn btn-success hide pula" id="btConfirmarAdicionar"><i class="icon-ok icon-white"></i> Confirmar</a>
				<a href="#" class="btn btn-danger" id="btCancelarAdicionar" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
	</form>

	<div class="modal hide fade" id="mdl-substituir">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Substituir QRCode</h4>
				</div>
				<div class="modal-body">
					<form id='frm-substituir' role="form">
						<div class="form-group">

							<input type="hidden" name="idproduto" class="form-control">
							<input type="hidden" name="inserir" class="form-control">
							<input type="hidden" name="qrcode_atual" class="form-control">

							<div class="alert alert-danger">
								<h3>Motivo da Substitui&ccedil;&atilde;o!</h3>
								<p>Por motivos de seguran&ccedil;a e hist&oacute;rico ser&aacute; gerado uma ocorr&ecirc;ncia com o motivo da substitui&ccedil;&atilde;o do QRCode.</p>
							</div>
							<div class="form-group">
								<label for="txMotivoSubs">Motivo:</label>
								<textarea name="motivo_substituicao" required="required" class="form-control configTxtMotivo" rows="3" id="txMotivoSubs"></textarea>
							</div>
							<div class="form-group">
								<label for="">Novo QRCode</label>
								<input type="text" class="form-control" id="newQRCode" name="newQRCode" required="required" placeholder="informe o QRCode" autofocus/>
							</div>
							<div class="checkbox">
								<label>
									<input type="checkbox" id='chkAumentar' name="aumentarProcessamento" value="1"/>
									Aumentar a quantidade m&aacute;xima de uso deste produto
								</label>
							</div>

						</div>
						<button type="submit" class="btn btn-primary hide">Submit</button>
						<button type="reset" class="btn btn-warning hide">Submit</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="$('#mdl-substituir').find('button[type=reset]').click();" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
					<button type="button" onclick="$('#mdl-substituir').find('button[type=submit]').click();" class="btn btn-primary pull-right">Salvar</button>
				</div>
			</div>
		</div>
	</div>

<?php
	function cabecalhoPagina($pag, $pags, $comTable) {
		$html .= "	<div class='onlyPrint'>
						<div>
							<img src='img/tms.png' width='100px' class='pull-left'>
							<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
						</div>
						<h4>Composição da caixa</h4>
						<br><br>
					</div>
					<br>
					<br>";
		return $html;
	}


	if($_REQUEST['buscar'] != "")
		echo "	<script>
					$('#txBuscar').val('" . $_REQUEST['buscar'] . "');
					$('#btLimparBusca').show();
				</script>";
	if($_REQUEST['descart'] == 'S')
		echo "	<script>
					$('#ckDescartados').attr('checked', true);
				</script>";

	include "helper/rodape.php";
?>

