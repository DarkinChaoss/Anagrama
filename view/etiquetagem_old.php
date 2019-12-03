<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	//error_log("- - - > PERMISSAO");
	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5));

	//error_log("- - - > CABECALHO");
	include "helper/cabecalho.php";
	//<input type="text" id="txQrcode" class="input-medium pull-right" placeholder="Buscar produto..." onkeypress="return noenter()" autofocus/>
?>

	<script src="js/placeholder_plugin.js"></script>
	<script src="js/etiquetagem.js?<?php echo time() ?>"></script>

	<div style="display: flex; justify-content:space-between; align-items: center;">
		<h1>
			Etiquetagem 
		</h1>
		<!-- cleverson matias add options for products -->
		<div style="margin-right: -2%;">
			<div class="radio" style="margin-left: 25vw; width: 170px;display: flex; justify-content: space-between;">
			  <label class="radio-inline radios" id="cs-co"><input type="radio" name="optradio" <?php if($_SESSION['ctx'] == 'pn') echo 'checked' ?> value="pn">Comuns</label>
			  <label class="radio-inline radios" id="cs-consig"><input type="radio" name="optradio" <?php if($_SESSION['ctx'] == 'pc') echo 'checked' ?> value="pc">Consignados</label>
			</div>
		</div>
		<div>
			<a href="#" class="btn pull-right" id="btReimpressao"><i class="icon-print"></i> Reimpressão </a>
			<input type="text" id="txQrcode" class="input-medium pull-right" placeholder="" style="margin-right: 5px" autofocus/>
			<img src="img/loading.gif" width="17px" id="imgLoading" class="pull-right hide" style="margin: 5px 15px 0 0;">
		</div>
		
	</div>
	
	<!-- cleverson matias quantidade total -->
	<style type="text/css">
		/* titulos e quantidade */
		.cs-tdTitle{
			background-color: #d7edeb !important;
		}
		.cs-descricao-quantidade{
			display: flex;
			justify-content: space-between;
		}

		/* navega?o interna */
		#cs-linksInternos{
		

			padding: 10px;
			z-index:999;
			position:fixed !important;
			right: 1%;
			bottom: 6%;
			display: flex;
			opacity: .8;
			display: '';
		}
		.cs-link{
	


			width:30px  !important;
			height:30px !important;
			background:#ffffff;
			margin: 5px;
			padding: 10px;
			-webkit-box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			-moz-box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			-webkit-border-radius: 100px;
			-moz-border-radius: 100px;
			border-radius: 100px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#cs-linksInternos a{
		

			display: block;
			text-decoration: none !important;
			color: #222;
			font-size: 1.5em;
		}
		.cs-flex{
			padding-right: 4%;
			display: flex;
			justify-content: space-between;
		}
		.cs-tdTitle{
			padding:0px !important;
   			height: 20px !important;
   			padding-left: 10px !important;
   			background-color: #ceeded !important;
		}
		.cs-tdTitle i{
			font-size: 23px;
		}

		.cs-spinner{
			font-size: .8em;
			-webkit-animation-name: spin;
			-webkit-animation-duration: 400ms;
			-webkit-animation-iteration-count: infinite;
			-webkit-animation-timing-function: linear;
			-moz-animation-name: spin;
			-moz-animation-duration: 400ms;
			-moz-animation-iteration-count: infinite;
			-moz-animation-timing-function: linear;
			-ms-animation-name: spin;
			-ms-animation-duration: 400ms;
			-ms-animation-iteration-count: infinite;
			-ms-animation-timing-function: linear;
    
			animation-name: spin;
			animation-duration: 400ms;
			animation-iteration-count: infinite;
			animation-timing-function: linear;
		}
		@-ms-keyframes spin {
			from { -ms-transform: rotate(0deg); }
			to { -ms-transform: rotate(360deg); }
		}
		@-moz-keyframes spin {
			from { -moz-transform: rotate(0deg); }
			to { -moz-transform: rotate(360deg); }
		}
		@-webkit-keyframes spin {
			from { -webkit-transform: rotate(0deg); }
			to { -webkit-transform: rotate(360deg); }
		}
		@keyframes spin {
			from {
				transform:rotate(0deg);
			}
			to {
				transform:rotate(360deg);
			}
		}
	</style>
	<div class="cs-descricao-quantidade">
	<h4>Materiais de solicitações pendentes a serem etiquetadas</h4>
	<h4 id="cs-totalItens"><!-- js/etiquetagem.js --></h4>
	</div>

	<div id="divPedidoEtiquetagem">
		<table class="table table-hover">
			<thead>
				<tr>
					<th width="150">QRCode</th>
					<th>Nome</th>
					<th width="200">Inserido na solicitação</th>
				</tr>
			</thead>
			<tbody id="listaItens">
				<tr id="comuns">
					<td colspan="8" class="cs-tdTitle">
						<div class="cs-flex">
							<h4>Comuns <i class="fas fa-tag"></i></i></h4>
							<h5 id="cs-qtd-comuns"><!-- js/etiquetagem.js --></h5>
						</div>
					</td>
				</tr>
				<?php
				echo ItensSolicitacaoHelper::listaItensEtiquetagem();

				// teste
				/*
				$i = 0;
				//error_log("- - - > INICIO LISTA");
				foreach(ItensSolicitacoesController::getItensEtiquetagem() as $iso){
					$i ++;
					echo "	<tr>
								<td>" . $iso->iso_pro_qrcode . "</td>
								<td>" . $iso->iso_pro_nome . "</td>
								<td>" . DefaultHelper::converte_data($iso->iso_data) . "</td>
							</tr>";
				}
				if($i == 0){
					echo "	<tr><td colspan='3'>Nenhum item solicitado.</td></tr>";
				}
				//error_log("- - - > FIM LISTA");

				*/
				?>
				
				<!-- cleverson matias -->
				<tr id="consignados">
					<td colspan="8" class="cs-tdTitle">
						<div class="cs-flex">
							<h4>Consignados <i class="fas fa-user-tag"></i></h4>
							<h5 id="cs-qtd-consignados"><!-- js/etiquetagem.js --></h5>
						</div>
					</td>
				</tr>
				<?php 
				echo ItensSolicitacaoHelper::listaItensEtiquetagemConsignados();
				?>
			</tbody>
		</table>
	</div>
	<!-- cleverson matias -> links de navega?o interna -->
	<div id="cs-linksInternos">
		<a id="cs-comuns" href="#comuns" title="Ir at?produtos comuns"><div class="cs-link"><i class="fas fa-tag"></i></div></a>
		<a id="cs-consignados" href="#consignados" title="Ir at?produtos consignados"><div class="cs-link"><i class="fas fa-user-tag"></i></div></a>
	</div>

	<a href="tlaEtiquetarProduto" class="btn btn-primary hide" id="btEtiquetarProduto" data-toggle="modal">Etiquetar produto</a>

	<!-- Tela Reimpressão de Etiqueta -->
	<form id="formReimpressaoEtiqueta">
		<div id="tlaReimpressaoEtiqueta" class="modal hide fade">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">X</a>
				<h3>Reimpressão de Etiqueta</h3>
			</div>
			<div class="modal-body" style="height: 400px;">
				<label>
					QRCode:
					<input type="text" name="qrcodeReimpressao" id="txQrcodeReimpressao" maxlength="50" class="input-xlarge" onkeypress="return noenter()" placeholder="Informe o QRCode!" autofocus autocomplete="off">
				</label>
				<div id="solicitacoesReimpressao" class="hide">
					<table class="table table-hover table-responsive" id="solicitacoesAndamentoCliente">
        				<thead style="position:fixed;">
        					<tr>
        						<th width="97">Lote</th>
        						<th width="50">Reuso</th>
        						<th width="90">Esterilização</th>
        						<th width="90">Validade</th>
        						<th width="30"></th>
        					</tr>
        				</thead>
        				<tbody id="itensSolicitacaoReimpressao" style="position:fixed; margin-top:36px; max-height:332px; overflow-y:scroll; border-bottom:1px solid #d1d1d3;">
        				</tbody>
        			</table>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar </a>
			</div>
		</div>
	</form>

	<!-- Tela Alteração de Metodo de Esterilização -->
	<form id="formAlterarMetodo">
		<div id="tlaAlteracaoMetodo" class="modal hide fade">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">X</a>
				<h3>Alterar Metodo de Esterilização</h3>
			</div>
			<div class="modal-body" style="height: 100px;">
				<label>
					Alterar para:
					<select id="slMetodo">
						<option value=""> Selecione </option>
					<?php
					   foreach ($arrMetodos as $metodo){
					       echo '<option value="'.$metodo->met_id.'"> '.$metodo->met_nome.' </option>';
					   }
					?>
					</select>
				</label>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success"> <i class="icon-check icon-white"></i> Alterar </button>
				<a href="#" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar </a>
			</div>
		</div>
	</form>
	<!--MEXER AQUI-->
	<!-- Tela Etiquetar Produto -->
	<form id="formEtiquetarProduto">
		<div id="tlaEtiquetarProduto" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaEtiquetarProduto" class="close" data-dismiss="modal">X</a>
				<i class="icon-ok pull-right" id="imgOkModal" style="margin: 9px 270px 0 0;"></i>
				<h3>Etiquetar Produto</h3>
			</div>
			<div class="modal-body" id="divEtiqueta" style="width: 2000px; height: 500px;">
				<input type='hidden' id='idItem' name='idItem'>
				<input type='hidden' id='idProduto' name='idProduto'>
				<input type='hidden' id='composto' name='composto'>
				<input type='hidden' id='TotComuns' name='composto'>
				<!--label>Setor: <span id="setor"></span></label>
				<br-->
				<span id="boxQtde"><!--boxQtde-->
				<label id="qtdename">Quantidade atual: <input type="text" readonly="" name="qtde" id="qtdeItem2" style="width:50px !important;">
				<input type="hidden" readonly="" name="qtde" id="qtdeItem3" style="width:50px !important;"></label>
				<br>
				<label id="qtdename2">Quantidade: <input type="number" name="qtde"  oninput="validity.valid||(value=value.replace(/\D+/g, ''))" min="1" max="99999" id="qtdeItem" style="width:50px !important;"></label>
				</span>
				<br>
				<label>Produto: <span id="nomeProduto"></span>, <span id="dadosProduto1"></span>, <span id="dadosProduto2"></span></label>
				<br>
				<label>QRCode: <span id="qrcode"></span></label>
				<br>
				<label>Método de esterilização: <span id="metodo"></span></label>
				<br>
				<label>Data da esterilização: <span id="dataEsterilizacao"></span></label>
				<br>
				<label>Data limite de uso: <span id="spanLimiteUso"></span>&nbsp;&nbsp;&nbsp;<span id="dataLimite"></span></label>
				<br>
				<label id="reuso">Nº de reuso: <span id="qtdProcessada"></span></label>
				<label><span id="labelQtd" >Quantidade Consignado: </span> <span id="qtdPro"></span></label>
			</div>
			<div class="modal-footer" style="height: 60px;">
				<label class="pull-left">Lote: <input type="text" id="txLote" style="width: 120px;" onkeypress="return noenter()"/></label>
				<a href="#" class="btn btn-danger" id="btOcorrencia"><i class="icon-edit icon-white"></i> Lançar ocorrência</a>
				<a href="#" class="btn btn-primary" id="btImprimir"><i class="icon-print icon-white"></i> Imprimir etiqueta</a>
			</div>
		</div>
	</form>

	<!-- Tela Lançar Ocorrência -->
	<form id="formLancarOcorrencia">
		<div id="tlaLancarOcorrencia" class="modal hide fade">
			<div class="modal-header">
				<h3>Lançar Ocorrência</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 500px;">
				<h4 id="produtoAlvo"></h4>
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
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger" id="btCancelarOcorrencia" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
				<a href="#" class="btn btn-success" id="btConfirmarOcorrencia"><i class="icon-ok icon-white"></i> Confirmar</a>
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