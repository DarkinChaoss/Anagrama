<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8));
	

	include "helper/cabecalho.php";
	if ( $_SERVER['REQUEST_METHOD'] == 'GET'){
		//error_log("get...");
		//verifica se h? trsnferencias nao concluidas
		//verificar este procedimento pois se outro usuario abrir a mesma tela poder? apagar a transferencia de outro q esta em andamento...,
		//a nao ser q a limpesa seja feita por usuario, se usario entrar natela limpa sua solicitacao nao finalizada.
		if (TransferenciaEstoqueController::getQtdTransferenciaNull() > 0){
			error_log("tem ha excluir");
			//limpar itens que iniciaram um transferencia mas nao concluiram...
			if (ItensTransferenciaController::limpaItensTransferencia()){
				if(!TransferenciaEstoqueController::limpaTransferencia()){
					echo"<script>alert('Erro ao limpar transferência de estoque!')</script>";					
				}
			}else{
				echo"<script>alert('Erro ao limpar intens de transferência de estoque!')</script>";	
			}			
		}
	}
?>

	<script src="js/transferenciaEstoque.js"></script>

	<h1>Transferência de Estoque</h1>
	<label>QRCode:</label>
	<input type="text" name="qrcode" class="qrcodebusca" id="txQrcode" class="input-medium" autofocus="" >
	<label>Retirado por:</label>
	<input type="text" name="retiradoPor" id="txtRetiradoPor" autocomplete="off" class="input-lg" >
	<div class="pull-right">
	<label style="margin-top: -25px;" >Setor de destino:</label>
			<?php echo SolicitacoesHelper::populaComboSetor(); ?>
			<button name="transferirestoque" id='transferirestoque'  class="btn btn-primary" style="margin-top: -10px;">Transferir</button>
	</div>
	<!---->
	<div id="telaInsereProdQtde" class="modal hide fade">
		<div class="modal-header">
			<a id="fechaTelaInsereProdQtde" class="close" data-dismiss="modal">X</a>
			<h3>Produto com quantidade<span id="txQrcodePai"></span></h3>
		</div>
		<div class="modal-body" style="width: 530px; height: 4050px;">
			<b>* Este produto contem quantidade, selecione a quantidade que <br> deseja transferir</b>
			<br>
			<h3 id="nomeprod" style="color:red;"></h3>
			<br>
			<label>Quantidade disponível</label>
			<input type="number" name="qtdedisponivel" id="qtdeDisponivel" readonly class="input-mini" value="">
			<label>Quantidade a ser transferida</label>
			<input type="number" name="qtdeescolhida" oninput="validity.valid||(value=value.replace(/\D+/g, ''))" id="qtdeEscolhida" class="input-mini" value="">
		</div>
		<div class="modal-footer">
			<div>
				<a href="#" class="btn btn-success" id="btAdicionar"><i class="icon-ok icon-white"></i> Adicionar</a>
				<a href="#" class="btn btn-danger" id="btFechar" data-dismiss="modal"><i class="icon-remove icon-white"></i> Fechar</a>
			</div>
		</div>
	</div>	

	<div id="modalSetor" class="modal hide fade">
		<div class="modal-header">
			<a id="fechaTelaInsereProdQtde" class="close" data-dismiss="modal">X</a>
			<h3>Selecionar Setor<span id="txQrcodePai"></span></h3>
		</div>
		<div class="modal-body" style="width: 530px; height: 4050px;">
			<div class="pull-center">
				<label>Escolha  qual setor voce esta:</label>
				<select name='setor' id='slSetorQte' class='input-xlarge'>
				  <option value='0'> ** Escolha **</option>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<div>
				<a href="#" class="btn btn-success" id="btnEscolher"><i class="icon-ok icon-white"></i> Escolher</a>
			</div>
		</div>
	</div>	
	
	<a id="lbProdutoNaoCadastrado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto não cadastrado!</a>
	<a id="lbItemDescartado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Este produto foi descartado!</a>
	<a id="lbItemValidadeExp" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto com validade de esterilização expirada!</a>
	<a id="lbItemNaoPronto" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto não pronto para uso!</a>
	<a id="lbItemNaoAutorizado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto já se encontra  em uma transferência não finalizada!</a>
	<!-- ERROVI INESPERADO -->
	<a id="lbTransferenciaNaoCadastrada" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Não foi possível iniciar transferência!</a>
	<a id="lbItenNaoCadastrado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Produto não pode ser cadastrado na transferência!</a>
	<a id="lbItemFNaoCadastrado" class="hide" style="color: red; font-weight: bold; margin-left: 20px;">Itens de produto composto não puderam ser cadastrados!</a>
	<br>
	<hr>
	<form name='' id='itfer'>
	<input type='hidden' name='tes_id' id='tes_id' >
		<table class="table table-hover">
		<thead>
			<tr>
				<th width="200">QRCode</th>
				<th>Produto</th>
				<th>Estoque/Setor atual</th>
				<th>Quantidade</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody id="lista_itensSE">


			<!-- Conteudo da lista -->


		</tbody>
		</table>
	</form>
<?php
	include "helper/rodape.php";
?>