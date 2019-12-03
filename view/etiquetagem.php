<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	//error_log("- - - > PERMISSAO");
	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 8));

	//error_log("- - - > CABECALHO");
	include "helper/cabecalho.php";
	//<input type="text" id="txQrcode" class="input-medium pull-right" placeholder="Buscar produto..." onkeypress="return noenter()" autofocus/>
?>
	<script src="js/moment/moment.js"></script>
	<script src="js/placeholder_plugin.js"></script>
	<script src="js/etiquetagem.js?<?php echo time() ?>"></script>

	<div style="display: flex; justify-content:space-between; align-items: center;">
		<h1>
			Etiquetagem 
		</h1>
		<!-- cleverson matias add options for products -->
		<!--
		<div style="margin-right: -2%;">
			<div class="radio" style="margin-left: 25vw; width: 170px;display: flex; justify-content: space-between;">
			  <label class="radio-inline radios" id="cs-co"><input type="radio" name="optradio" <?php if($_SESSION['ctx'] == 'pn') echo 'checked' ?> value="pn">Comuns</label>
			  <label class="radio-inline radios" id="cs-consig"><input type="radio" name="optradio" <?php if($_SESSION['ctx'] == 'pc') echo 'checked' ?> value="pc">Consignados</label>
			</div>
		</div>
		-->
		<div class="btn-group" data-toggle="buttons-radio" style="margin-left: 150px; display: flex; width:270px;">	
			<label for="pn" style="width:70%;">
				<span style="background: rgba(0,0,0,.0); width:48% !important; height:30px; display:block; position: absolute"></span>
				<button type="button" class="btn btn-primary active hide">
					<input checked id="pn" type="radio" name="optradio" value="pn" style="position: fixed; left: 120vw"> Produto Comum <span class="correct-pn"></span>
				</button>
			</label>
	
			<label for="pc" style="width:70%;">
				<span style="background: rgba(0,0,0,.0); width:60% !important; height:90%; display:block; position: absolute"></span>
				<button type="button" class="btn btn-primary hide">
					<input id="pc" type="radio" name="optradio" value="pc" style="position: fixed; left: 120vw "> Produto Consignado <span class="correct-pc"></span>
				</button>
			</label>
		</div>
		<div>
			<a href="#" class="btn pull-right" id="btReimpressao"><i class="icon-print"></i> Reimpressão </a>
			<input type="text" id="txQrcode" autocomplete="off" class="txQrcodecl input-medium pull-right" placeholder="" style="margin-right: 5px" autofocus/>
			<img src="img/loading.gif" width="17px" id="imgLoading" class="pull-right hide" style="margin: 5px 15px 0 0;">
		</div>
	</div>
	<a id="lbProdutoinvertido" class="hide" style="color: red; font-weight: bold; float:right;">O produto não é <span class="type-product"></span> ou <span id="lbProdutoNaoCadastrado" class="hide" style="color: red; font-weight: bold;">o produto não foi inserido na solicitação </span>	</a>
	<br><br>
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
		/* Estilo dos checkboxes */
		.control {
            font-family: arial;
            display: block;
            position: relative;
            padding-left: 27px;
            margin-bottom: 7px;
            padding-top: 2px;
            cursor: pointer;
            font-size: 15px;
        }
            .control input {
                position: absolute;
                z-index: -1;
                opacity: 0;
            }
        .control_indicator {
            position: absolute;
            top: 2px;
            left: 0;
            height: 20px;
            width: 20px;
            background: #b7b7b7;
            border: 0px solid #000000;
        }
        .control-radio .control_indicator {
            border-radius: undefined%;
        }
        
        .control:hover input ~ .control_indicator,
        .control input:focus ~ .control_indicator {
            background: #cccccc;
        }
        
        .control input:checked ~ .control_indicator {
            background: #007acc;
        }
        .control:hover input:not([disabled]):checked ~ .control_indicator,
        .control input:checked:focus ~ .control_indicator {
            background: #0e6647d;
        }
        .control input:disabled ~ .control_indicator {
            background: #e6e6e6;
            opacity: 0.6;
            pointer-events: none;
        }
        .control_indicator:after {
            box-sizing: unset;
            content: '';
            position: absolute;
            display: none;
        }
        .control input:checked ~ .control_indicator:after {
            display: block;
        }
        .control-checkbox .control_indicator:after {
            left: 8px;
            top: 4px;
            width: 3px;
            height: 8px;
            border: solid #ffffff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .control-checkbox input:disabled ~ .control_indicator:after {
            border-color: #7b7b7b;
        }

        /* loader modal etiquetar vários produtos*/
		.loader {
		  height: 4px;
		  width: 100%;
		  position: relative;
		  overflow: hidden;
		  background-color: #ddd;
		}
		.loader:before{
		  display: block;
		  position: absolute;
		  content: "";
		  left: -200px;
		  width: 200px;
		  height: 4px;
		  background-color: #2980b9;
		  animation: loading 2s linear infinite;
		}

		@keyframes loading {
		    from {left: -200px; width: 30%;}
		    50% {width: 30%;}
		    70% {width: 70%;}
		    80% { left: 50%;}
		    95% {left: 120%;}
		    to {left: 100%;}
}
	</style>
	<div class="cs-descricao-quantidade">
	<h4>Materiais de solicitações pendentes a serem etiquetadas</h4>
	<div id="totalitens-container">
		<h4 id="cs-totalItens"><!-- js/etiquetagem.js --></h4>
	</div>
	</div>

	<div id="divPedidoEtiquetagem">
		<table class="table table-hover table-etiqueta">
			<thead>
				<tr>
					<th width="150">QRCode</th>
					<th>Nome</th>
					<th>Quantidade</th>
					<th width="200">Inserido na solicitação</th>
					<th width="200"></th>
				</tr>
			</thead>
			<tbody id="listaItens">
				<tr>
					<td colspan="8">
						<form class="etiquetagem">
							<input type="text" name="search" value="<?php echo $_GET['search'];?>" placeholder="Pesquisar por qrcode" style="width:96%;" id="search"/>
							<button type="submit" class="btn" id="btBuscar" style="margin-top:-12px;"><i class="icon-search"></i></button>
						</form>
					</td>
				</tr>
				<tr id="comuns">
					<td colspan="8" class="cs-tdTitle">
						<div class="cs-flex">
							<h4>Produtos Comuns <i class="fas fa-tag"></i></i></h4>
							<h5 id="cs-qtd-comuns"><!-- js/etiquetagem.js --></h5>
						</div>
					</td>
				</tr>
				<?php
				$search = $_GET['search'];
				echo ItensSolicitacaoHelper::listaItensEtiquetagem($search);
				echo '<input type="hidden" id="qtd_to_print" value="">';
				?>
				<!-- cleverson matias -->
				<tr id="consignados">
					<td colspan="8" class="cs-tdTitle">
						<div class="cs-flex">
							<h4>Produtos Consignados <i class="fas fa-user-tag"></i></h4>
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
				<a id="lbProdutoDescartado" class="hide" style="color: red; font-weight: bold; margin-left: 20px; display: inline;">Produto descartado!</a>
			</div>
			<div class="modal-body" id="divEtiqueta" style="width: 2000px; height: 500px;">
				<input type='hidden' id='idItem' name='idItem'>
				<input type='hidden' id='idProduto' name='idProduto'>
				<input type='hidden' id='composto' name='composto'>
				<input type='hidden' id='TotComuns' name='composto'>
				<!--label>Setor: <span id="setor"></span></label>
				<br-->
				<span id="boxQtde"><!--boxQtde-->
				<input type="hidden" readonly="" name="qtde" id="qtdeItem3" style="width:50px !important;"></label>
				<br>
				<label id="qtdename2">Quantidade: <input type="number" name="qtde"  oninput="validity.valid||(value=value.replace(/\D+/g, ''))" min="1" max="99999" id="qtdeItem" style="width:50px !important;"></label>
				</span>
				<br>
				<label>Produto: <span id="nomeProduto"></span>, <span id="dadosProduto1"></span>, <span id="dadosProduto2"></span></label>
				<br>
				<label>QRCode: <span id="qrcode"></span></label>
				<br>
				<label>Método de esterilização: <span id="spanMetodo"></span><span id="metodo"></span></label>
				<br>
				<label>Equipamento: <span id="spanEquipamento"></span><span id="equipamento_metodo"></span></label>
				<br>
				<label>Data da esterilização: <span id="dataEsterilizacao"></span></label>
				<br>
				<label>Data limite de uso: <span id="spanLimiteUso"></span>&nbsp;&nbsp;&nbsp;<span id="dataLimite"></span></label>
				<br>
				<label id="reuso">Nº de reuso: <span id="qtdProcessada"></span></label>
				<label><span id="labelQtd" >Quantidade Consignado: </span> <span id="qtdPro"></span></label>
				
				<b id="aviso" style="color:red;"></b>
			</div>
			<div class="modal-footer" style="height: 60px;">
				<?php $ultimolote =  isset($_SESSION['ultimoLote']) ? $_SESSION['ultimoLote'] : '' ?>
				<label class="pull-left">Lote: <input type="text" id="txLote" value="<?= $ultimolote ?>" style="width: 120px;" onkeypress="return noenter()"/></label>

				<a href="#" class="btn btn-danger" id="btOcorrencia"><i class="icon-edit icon-white"></i> Lançar ocorrência</a>
				<a href="#" class="btn btn-primary" id="btImprimir"><i class="icon-print icon-white"></i> Imprimir etiqueta</a>
			</div>
		</div>
	</form>

	<!-- Tela de reetiquetagem de Produto com QTE -->
	<form id="formReetiquetagem">
			<div id="tlaReetiquetagem" class="modal hide fade">
				<div class="modal-header">
					<a id="fechaTelaEtiquetarProduto" class="close" data-dismiss="modal">X</a>
					<i class="icon-ok pull-right" id="imgOkModal" style="margin: 9px 270px 0 0;"></i>
					<h3>Reetiquetar Produto</h3>
				</div>
				<div class="modal-body" id="divEtiqueta" style="width: 2000px; height: 300px;">
					<input type='hidden' id='qr' name='qr'>
					<input type='hidden' id='itemRe' name='itemRe'>
					<br>
					<label><b>Produto:</b> <span id="nomeProdutoRe"></span>
					<br>
					<br>
					<label><b>QRCode:</b> <span id="qrcodeRe"></span></label>
					<br>
					<label><b>Método de esterilização:</b> <span id="spanMetodoRe"></span><span id="metodo"></span></label>
					<br>
					<label><b>Data da esterilização:</b> <span id="dataEsterilizacaoRe"></span></label>
					<br>
					<label><b>Data limite de uso:</b> <span id="spanLimiteUsoRe"></span>&nbsp;&nbsp;&nbsp;<span id="dataLimite"></span></label>
				</div>
				<div class="modal-footer" style="height: 60px;">
					<?php $ultimolote =  isset($_SESSION['ultimoLote']) ? $_SESSION['ultimoLote'] : '' ?>
					<label class="pull-left">Lote: <input type="text" id="txLote" value="<?= $ultimolote ?>" style="width: 120px;" onkeypress="return noenter()"/></label>
					<a href="#" class="btn btn-primary" id="btImprimirRE"><i class="icon-print icon-white"></i> Reimprimir etiqueta</a>
				</div>
			</div>
		</form>

	<!-- Tela Etiquetar Produto com quantidade-->
	<form id="formEtiquetarProdutoQuantidade">
		<div id="tlaEtiquetarProdutoQuantidade" class="modal hide fade">
			<div class="modal-header" style="padding: 1em;">
				<a id="fechaTelaEtiquetarProduto" class="close" data-dismiss="modal">X</a>
				<i class="icon-ok pull-right" id="" style="margin: 9px 180px 0 0;"></i>
				<h3>Etiquetar Vários Produtos </h3>
				
			</div>
			<div class="modal-body" id="divEtiqueta" style="width: 2000px;">
				<input type="hidden" name="qrcodeToPrint" id="qrcodesToPrint" value="">
				<input type='hidden' id='idProdutoQtd' name='idProdutoQtd'>
				<input type='hidden' id='qrcodeQtd' name='qrcodeQtd'>
				<!-- Quantidade -->
				<h4 id="qtd_itens" style="color: rgba(0,0,0,0.7); padding-bottom: 1em;"></h4>
				<!-- Nome do produto -->
				<label>Produto: <span id="nProduto"></span>, <span id="dadosProduto1Qtd"></span>, <span id="dadosProduto2Qtd"></span></label>
				<!-- Metodo de esterilização -->
				<label>Método de esterilização: <span id="spanMetodoQtd"></span><span id="metodo"></span></label>
				<!-- equipamento -->
				<label>Equipamento: <span id="spanEquipamentoQtd"></span><span id="equipamento_metodoQtd"></span></label>
				<!-- data da esterilização -->
				<label>Data da esterilização: <span id="dataEsterilizacaoQtd"></span></label>
				<!-- data limite de uso -->
				<label>Data limite de uso: <span id="spanLimiteUsoQtd"></span>&nbsp;&nbsp;&nbsp;<span id="dataLimiteQtd"></span></label>
				<!-- numero de reuso -->
				<label id="reuso">Nº de reuso: <span id="qtdProcessadaQtd"></span></label>
				
			</div>
			<div class="modal-footer" style="height: 60px;">
				<?php $ultimo_loteQtd =  isset($_SESSION['ultimo_loteQtd']) ? $_SESSION['ultimo_loteQtd'] : '' ?>
				<label class="pull-left">Lote: <input type="text" id="txLoteQtd" value="<?= $ultimo_loteQtd ?>" style="width: 120px;" onkeypress="return noenter()"/></label>
				
				<a href="#" class="btn btn-primary" id="btImprimirQtd"><i class="icon-print icon-white"></i> Imprimir etiquetas</a>
				<!-- Loader -->
				<div class="loader" style="display: none;"></div>
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

	<!-- Tela Reimpressão em massa-->
	<form id="formHistoricoDeImpressao">
		<div id="tlaHistoricodeImpressao" class="modal hide fade">
			<div class="modal-header" style="display: flex; justify-content: space-between; padding: 20px 20px 7px 20px;">
				<h3>Histórico de Impressão</h3>
				<a id="#" class="close" data-dismiss="modal">X</a>
			</div>
			<div class="input-group" style="display: flex; margin: .5em  1.2em 0;">
				<span style="padding: .4em .3em 0 .4em;"> buscar por: </span> 
				 <select id="historicoSelect" style="width: 20%; margin: 0 1em;" onchange="setCalendarOnInput()">
					  <option value="0">Nome</option>
					  <option value="1">Data</option>
					  <option value="2">Lote</option>
					  <option value="3">Produto</option>
					  <option value="4">Quantidade</option>
				</select> 

				<input type="text" id="historicoInput" placeholder="Pesquisar" onkeyup="buscaHistoricoImpressao()" class="form-control">
				<input type="text" id="historicoInput_b" placeholder="Pesquisar" oninput="buscaHistoricoImpressao('b')" class="form-control data" style="display: none;">
			</div>
			<div class="modal-body" style="width: 530px; height: 500px;">
				<!-- Data hardcoded para testes apenas, valores reais virão de tmss_etiquetas -->
				<?php
					$data = EtiquetaQtdController::getLastEtiquetas48Hours();
				?>
				
				<table class="table table-bordered" id="historico_impressao_table">
				    <thead>
				      <tr>
				        <th onclick="sortTable(0)" style="cursor: pointer;">Nome
				        	<span class="arr_down" style="display: none;" id="arr_down0"> &#8595;</span>
				        </th>
				        <th onclick="sortTable(1)" style="cursor: pointer;">Data
				        	<span class="arr_down" style="display: none;" id="arr_down1"> &#8595;</span>
				        </th>
				        <th onclick="sortTable(2)" style="cursor: pointer;">Lote
				        	<span class="arr_down" style="display: none;" id="arr_down2"> &#8595;</span>
				        </th>
				        <th onclick="sortTable(3)" style="cursor: pointer;">Prod.
				        	<span class="arr_down" style="display: none;" id="arr_down3"> &#8595;</span>
				        </th>
				        <th onclick="sortTable(4)" style="cursor: pointer;">Qtd.
				        	<span class="arr_down" style="display: none;" id="arr_down4"> &#8595;</span>
				        </th>
				        <th>Ação</th>
				      </tr>
				    </thead>
				    <tbody>
				    	<?php foreach ($data as $registro) { ?>
				    		<?php 
				    		$temp = explode(' ', $registro['eqm_time']);
				    		$data = join('/', array_reverse(explode('-', $temp[0])));
				    		$registro['eqm_time'] = $data . ' ' . $temp[1];
				    		?>
				    		<tr>
						        <td><?= $registro['eqm_user'] ?></td>
						        <td><?= $registro['eqm_time'] ?></td>
						        <td><?= $registro['eqm_lote']  ?></td>
						        <td><?= $registro['eqm_nome_produto'] ?></td>
						        <td><?= $registro['eqm_qtd'] ?></td>
						        <td><p class='btn' title='Etiquetar' onclick='printEspecifc(<?php echo $registro['eqm_id']; ?>)'><i class='fas fa-tag'></i></p></td>
				      		</tr>
				    	<?php } ?>
				    </tbody>
  				</table>

			</div>
		
	</div>

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