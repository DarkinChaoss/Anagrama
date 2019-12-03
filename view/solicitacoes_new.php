<?php
	session_start();
	if($_POST['acao'] === 'setLoteDetergente'){
		if($_POST['ventilatorio'] == 'ventilatorio'){
			$_SESSION['setLoteDetergente'] = 'ventilatorio';
		}

		if($_POST['instrumental'] == 'instrumental'){
			$_SESSION['setLoteDetergente'] = 'instrumental';
		}
		
	}

	if($_POST['acao'] === 'getLoteDetergente'){
		$lote = $_SESSION['setLoteDetergente'];
		echo $lote;
		exit;
	}
	
	if($_POST['acao'] === 'setLote'){
		$_SESSION['ultimoLote'] = $_POST['lote'];
	}

	if($_POST['acao'] === 'searchLote'){
		//die($_POST['idequip'] . $_POST['tipocampo']);
		$id = $_POST['idequip'];
		
		$control = $_POST['tipocampo'];
		//echo $control;
		
		$eq = EquipamentoController::getEqupipamento_standar($id);		
		
		if($control == 0){
			$lote = $eq->eq_neutro;
		}else{
			$lote = $eq->eq_enzimatico;
		}
		
		die($lote);
	}
	
	if($_POST['acao'] === 'getUltimoEstado'){
		//setor é na saida de materiais e a coferencia é da conferencia pos procedimento
		$itenssaida = ItensSaidaController::selectItemUltimaC($_POST['iditemsol']);

		$idsaida = $itenssaida->isa_idsaida;
		
		$where = "sma_id = ".$idsaida."";
		$saida = SaidaMateriaisController::getSaidasMateriaisLimite($where);

		die($saida[0]->sma_set_nome."*;*".$itenssaida->isa_conferente);
	}
	
	if($_POST['acao'] === 'veridsol'){
		$versol = ItensSolicitacoesController::selectItemidproduct($_POST['id']);
		die($versol->iso_id.'*;*'.$versol->pro_qrcode);
	}
	
	if($_POST['acao'] === 'verautorizacao'){

		$autorizacao = AutorizacaoController::ultimaautorizacao($_POST['id']);
	
		$auto = ItensSolicitacoesController::selectUltimAuto($autorizacao->ac_iditemsol);
		
		if($auto){
			$at = $autorizacao->ac_modo;
		}else{
			$at = '-'; 
		}
		
		die($at);
	}
	if($_POST['acao'] === 'verificafilhos'){
		$verifilho = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = " . $_POST['id']);

		$autorizacaofilhos = AutorizacaoController::ultimaautorizacao($verifilho[0]->pco_idpai);
	
		die($autorizacaofilhos->ac_modo);
	}

	if($_POST['acao'] == 'autorizacao'){
		$repauth = AutenticaController::permission(strtoupper($_POST['responsavel']), strtoupper($_POST['senha']));
		$usureferencia = $repauth->usu_referencia;
		$usureferencia;
		$resp = ResponsaveisTecnicosController::getRTecnicos(" rte_id = '$usureferencia' AND rte_masterclient = " . $_SESSION['usu_masterclient'] . "");
	
		if($resp){
			$autorizacao = AutorizacaoController::insert($_POST);
			
			if($autorizacao == 1){
				die('OK');	
			}else{
				die('ERROR');
			}
		}else{
			die('ERROR');
		}
	}

	if($_GET['iddetail']){
		$iddetail = $_GET['iddetail'];
		$detail = ProdutosController::getProduto($iddetail);
		die(nl2br($detail->pro_detailproduct));
	}
	
	if($_GET['iddetailconsig']){
		$iddetailconsig = $_GET['iddetailconsig'];
		$detailconsig = ProdutosConsignadoController::getProdutoConsignado($iddetailconsig);
		die(nl2br($detailconsig->pro_detailproduct));		
	}

	// var_dump($_SESSION['usu_masterclient']); 865
 	// navegação da paginação

	// total de comuns
	if(isset($_POST['idses']) && ( $_POST['acao'] == 'contaComum')  ){
		$res = ItensSolicitacoesController::countComun($_POST['idses']);
		die($res);
	}

	// total de consignados
	if(isset($_POST['idses']) && ( $_POST['acao'] == 'contaConsignado')  ){
		$res = ItensSolicitacoesController::countConsignados($_POST['idses']);
		die($res);
	}

	if (isset($_POST['pag'])) {
		if($_POST['acao'] == "lista"){
			$res = SolicitacoesHelper::listaSolicitacoes($_POST['buscar'], $_POST['pag']);
		} else {
			$res = SolicitacoesHelper::paginacao($_POST['buscar'], $_POST['pag']);
		}
		die($res);
	}
	
	// verifica se já existe solicitação do setor; se não existir, salva uma nova e retorna o ID
	if (isset($_GET['nova'])) {
		$res = SolicitacoesController::getSolicitacoes("ses_idsetor = " . $_GET['nova']);
		if($res) { // se já existir, apenas retorna resposta
			die("EXISTE***".$_GET['nova']);
		} else {
			$dados['setor'] = $_GET['nova'];
			$res = SolicitacoesController::insert($dados);
			if($res)
				die("".$res);
			else
				die("ERRO");
		}
	}
	
	// salva solicitação
	if (isset($_POST['setor']) && !isset($_POST['acao'])) {
		if(empty($_POST['id'])) {

			$res = SolicitacoesController::insert($_POST);
			$res = "".$res;
			} elseif(!empty($_POST['id'])) {
			$res = SolicitacoesController::update($_POST);
		}
		die($res);
	}
	
	// busca produto para inserir na solicitação
	if ($_POST['acao'] == 'buscar' && isset($_POST["setor"]) && (isset($_POST["qrcode"])) && (!empty($_POST['qrcode']) || $_POST['qrcode'] != '' || $_POST['qrcode'] != ' ')){
		if($_POST['chProduto'] == 'pn'){

			$produto = ProdutosController::getProdutoParaSolicitacao($_POST['qrcode']);
			//print_r($produto);
			if ($produto->pro_id != ""){

				// se produto já estiver incluso na solicitação
				//if (ItensSolicitacoesController::checkProdutoInSolicitacao($produto->pro_id, $_POST['idses']) > 0) {
				$iso = ItensSolicitacoesController::getItemBySolicitacaoEProduto($_POST['idses'], $produto->pro_id, "0");
				//print_r($iso);
				// se produto já estiver incluso na solicitação
				// !empty($_POST['txQtde'])
				//verificar se o produto tem qtde se tiver ele deixa passar, se não ele dá como repetido
				if (!empty($iso) && $produto->pro_qtde <= 0) {
					die("REPETIDO");
				}
				
				$infoProduto = $produto->pro_nome
				. (($produto->pro_diametrointerno != "") ? ", ".$produto->pro_diametrointerno : "")
				. (($produto->pro_curvatura != "") ? ", ". $produto->pro_curvatura : "")
				. (($produto->pro_calibre != "") ? ", ". $produto->pro_calibre : "")
				. (($produto->pro_comprimento != "") ? ", ". $produto->pro_comprimento : "");
				/////$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);
				$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);
				// verifica se produto contém ocorrência que anula reuso
				$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
				//
				$reproc = $produto->pro_reuso + 1 - count($ee);
				if($reproc < 1)
					$reproc = 1;
				/////$ultimoMetodo = ItensSolicitacoesController::getUltimoMetodo();
				/////$ultimoRespTec = ItensSolicitacoesController::getUltimoRespTec();
				$aux = explode("*;*", ItensSolicitacoesController::getLastMetodoERespTec());
				
				if(isset($_SESSION['responsavel'])){
					$ultimoRespTec = $_SESSION['responsavel'];
				}else{
					$ultimoRespTec = $aux[0];
				}
				
				if(isset($_SESSION['equipamento'])){
					$ultimoEquipamento = $_SESSION['equipamento'];
				}else{
					$ultimoEquipamento = $aux[1];
				}
				
				if(isset($_SESSION['ultimoLote'])){
					$loteequip = $_SESSION['ultimoLote'];					
				}else{
					$loteequip = $aux[2];
				}
		
				die("$produto->pro_id".";".stripslashes($infoProduto).";"."$setor->set_nome".";"."-".";".$produto->pro_maxqtdprocessamento.";".$produto->pro_gma_nome.";".$reproc.";".$descarte.";".$ultimoMetodo.";".$ultimoRespTec.";".$produto->pro_composto.
";".$produto->pro_qtde.";".$ultimoEquipamento.";".$loteequip);
			} else {
				die("ERRO");
			}
		}else{
			$produto = ProdutosConsignadoController::getProdutoConsignadoParaSolicitacao($_POST['qrcode']);
			//print_r($produto);
			if ($produto->pro_id != ""){

				// se produto já estiver incluso na solicitação
				//if (ItensSolicitacoesController::checkProdutoInSolicitacao($produto->pro_id, $_POST['idses']) > 0) {
				$iso = ItensSolicitacoesController::getItemBySolicitacaoEProdutoConsignado($_POST['idses'], $produto->pro_id, "0");
				//print_r($iso);g
				// se produto já estiver incluso na solicitação
				// !empty($_POST['txQtde'])
				//verificar se o produto tem qtde se tiver ele deixa passar, se não ele dá como repetido
				if (!empty($iso)) {
					die("REPETIDO");
				}
				
				$infoProduto = $produto->pro_nome
				. (($produto->pro_diametrointerno != "") ? ", ".$produto->pro_diametrointerno : "")
				. (($produto->pro_curvatura != "") ? ", ". $produto->pro_curvatura : "")
				. (($produto->pro_calibre != "") ? ", ". $produto->pro_calibre : "")
				. (($produto->pro_comprimento != "") ? ", ". $produto->pro_comprimento : "");
				/////$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);
				$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);
				// verifica se produto contém ocorrência que anula reuso
				$ee = OcorrenciasProdutosController::getByEfeitoEspecial($produto->pro_id, 'R');
				//
				$reproc = $produto->pro_reuso + 1 - count($ee);
				if($reproc < 1)
					$reproc = 1;
				/////$ultimoMetodo = ItensSolicitacoesController::getUltimoMetodo();
				/////$ultimoRespTec = ItensSolicitacoesController::getUltimoRespTec();
				$aux = explode("*;*", ItensSolicitacoesController::getLastMetodoERespTec());
				$ultimoMetodo = '';
				$ultimoRespTec = $_SESSION['responsavel'];
				$ultimoEquipamento = $_SESSION['equipamento'];
				$loteequip = $_SESSION['loteequip'];

				die("$produto->pro_id".";".stripslashes($infoProduto).";"."$setor->set_nome".";"."-".";".$produto->pro_maxqtdprocessamento.";".$produto->pro_gma_nome.";".$reproc.";".$descarte.";".$ultimoMetodo.";".$ultimoRespTec.";".$produto->pro_composto.";".$produto->pro_qtde.";".$ultimoEquipamento.";".$loteequip);
			} else {
				die("ERRO");
			}
		}
	}

	if ($_POST['acao'] == 'buscarcount' && isset($_POST["idProduto"])&& isset($_POST["qtdeProduct"])){
		$count = ItensSolicitacoesController::getCountItens("iso_idproduto = " . $_POST['idProduto']);
		//print($count);
		if($_POST['qtdeProduct']){
			$qtdeprod = (int) $_POST["qtdeProduct"];
			$sub = $qtdeprod - $count;
			print($sub);
		}else{
			print('');
		}
		die();
	}

	// salva produto na solicitação 
	if (isset($_POST['acao']) && $_POST['acao'] != 'buscar'){
		if ($_POST['acao'] == 'slProd'){
			$count = ItensSolicitacoesController::getCountItens("iso_idproduto = " . $_POST['idProduto']);
			$sub = $_POST['qtdeproduct'] - $count;

			if($_POST['qtde'] > 1){
				//modificar aqui o save com quantidade 
				$qtdep = $_POST['qtde'];

				//numero sequencial, quando o produto for novo ele vai começar de 1 se já estiver com alguns no banco, ele contará apartir do ultimo inserido
				
				$qrcodebase = $_POST['qrcode'];
		
				$getprod = ProdutosController::getProdutoByQrCode($qrcodebase);
				$arraycreatep = $getprod;
				
				$verify = SolicitacoesController::lastproduct($qrcodebase.'.');

			
				$qrcodebase = $qrcodebase . '.';

	
				$auxs = explode('.', $verify->ses_pro_qrcode);
	
		
				$n = ltrim($auxs[1], "0");
				
				
				$n = (int)$n + 1;
				
				$smaTrans = TransferenciaEstoqueController::insert();

					$number = $n ++;
					$cont = strlen($number);
					if($cont == 1){
						$number = str_pad($number , 2 , '0' , STR_PAD_LEFT);

					}else{
						$number = $number;
					}

					$qrcodenew = $qrcodebase . $number;

					$newProd = ProdutosController::replicarProduto($_POST['idProduto'], $_POST['qtde'], $qrcodenew, $_SESSION['usu_id']);
					
					/*
					$novo = $arraycreatep;
					$novo->pro_qrcode = $qrcodenew;
					$novo->pro_qtde = 0;
					$novo->pro_maxqtdprocessamento = 1;
					
					$create = $novo->insert();

					$gp = ProdutosController::getProdutoByQrCode($qrcodenew);
			*/
					$_SESSION['responsavel'] = $_POST['rTecnico'];
					$_SESSION['equipamento'] = $_POST['eqEsterilizacao'];
					$_SESSION['estandar'] = $_POST['estandar'];
					//$_SESSION['ultimoLote'] = $_POST['loteequipamento'];	
					
					$_POST['idProduto'] = $newProd['id'];
					$_POST['qrcode'] = $newProd['pro_qrcode'];
					$_POST['qtdeproduct'] = $newProd['pro_qtde'];
					$res = ItensSolicitacoesController::insert($_POST);


					// Transferência automática dos produtos com quantidade
					////////////////////////////////////////////////////////////////////////////////////////////

					$arrDadosIten = array("smaID"=>$smaTrans,
																"idPro"=>$gp->pro_id,
																"idSetor"=>0,
																"reuso"=>0);
					$salTransIten = ItensTransferenciaController::insert($arrDadosIten);
					////////////////////////////////////////////////////////////////////////////////////////////

				

				


				$pro = ProdutosController::getProduto($_POST['idProduto']);
				if($pro->pro_composto == 1){

					die("OKCOMPOSTO*{$res}");
				}
				else{
					die("OK");
				}

			}else{
		
				$res = ItensSolicitacoesController::insert($_POST);
				
				$_SESSION['responsavel'] = $_POST['rTecnico'];
				$_SESSION['equipamento'] = $_POST['eqEsterilizacao'];
				$_SESSION['estandar'] = $_POST['estandar'];
				$_SESSION['equipamento'] = $_POST['eqEsterilizacao'];
				//$_SESSION['ultimoLote'] = $_POST['loteequipamento'];
				

				if($res){
					// inserir os produtos em transferencia...
					/* $setOrigem = ItensTransferenciaController::getOrigemProduto($_POST['idProduto']);
					$_Dados = array("smaID"=>$_POST['smaid'],"idPro"=>$_POST['idProduto'],"idSetor"=>$setOrigem);
					$iSaida = ItensTransferenciaController::insert($_Dados);
					if ($iSaida){
						$objSes = SolicitacoesController::getSolicitacao($_POST['idSolicitacao']);
						$_Dados_ = array("tesID"=>$_POST['smaid'],"setID"=>$objSes->ses_idsetor);
						ItensTransferenciaController::updateSetDestino($_Dados_);
					}		 */
					$pro = ProdutosController::getProduto($_POST['idProduto']);
					if($pro->pro_composto == 1){

						die("OKCOMPOSTO*{$res}");
					}
					else{
						
						die("OK");
					}

				} else {
					die("ERRO");
				}

			}

		} else {
			die("ERRO");
		} 
		
	}
	
	// apaga solicitação
	if(isset($_GET['delete'])){
		if(SolicitacoesController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	// remove item da solicitação
	if(isset($_GET['remove'])){
		if(ItensSolicitacoesController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 8));
	
	include "helper/cabecalho.php";
?>

	<script src="js/solicitacoes.js"></script>
	
	<h1>
		Solicitações de esterilização
		<small>Novo registro</small>
	</h1>

	<form id="formSolicitacoes">
		<div class="row-fluid">
			<div class="span13">
				<input type="hidden" name="id" id="txId" class="input-small">
				<input type="hidden" name="status" id="txStatus" value="1">
				<input type="hidden" name="qtdecomun" id="qtdeComun" class="input-small">
				<input text="hidden" name="qtdeconsignado" id="qtdeConsignado" class="hidden">
				<label>Setor:</label>
				<?php echo SolicitacoesHelper::populaComboSetor($_REQUEST['id']); ?>
				<br><br>
				<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Concluir</a>
			</div>
		</div>
	</form>
	<!--total de produtos-->
	<div class="txQtdeItens"></div>
	
	<!-- links âncoras -->
	<style type="text/css">
		#ancoras{
			padding: 10px;
			z-index:999;
			position:fixed !important;
			right: 1%;
			bottom: 6%;
			display: flex;
			opacity: .8;
			display: '';

		}
		.linkSeparate{
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
		.linksAncora{
			display: block;
			text-decoration: none !important;
			color: #222;
			font-size: 1.5em;
		}

		.cs-fa{
			font-size:23px;
		}
		.cs-tr td{
   			padding:0px !important;
   			height: 20px !important;
   			padding-left: 10px !important;
   			background-color: #ceeded !important;
		}
		.cs-tr td h4{
			margin: 5px 0 !important;
		}
		
		
		
		.tooltip_info{
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			margin-top:10px;
			background:#000;
			width:170px;
			height:70px;
			color:#fff;
			padding:10px;

		}
		
		.info:hover .tooltip_info{
			display:block;
			background:#000;
		}

		.boxinfo { width:150px;}
		.layer { display:none; }
		.boxinfo:hover .layer {display:block; }


	</style>
	<input type="hidden" id="sector_id" value="<?php echo SetoresController::getCmeId(); ?>">
	<div id="ancoras" class="pull-right">
		<a href="#comum" class="linksAncora comum" title="Ir para Produtos comuns"><div class="linkSeparate"><i class="fas fa-tag"></i></div></a>
		<a href="#consignados" class="linksAncora consignados" title="Ir para Produtos consignados"><div class="linkSeparate"><i class="fas fa-user-tag"></i></div></a>
	</div> <!-- cleverson -->
	<input type="hidden" name="tcomun" id="Tcomun">
	<input type="hidden" name="nSolicitacao" id="<?= $_GET['id'] ?>">
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="200">QRCode</th>
				<th>Produto</th>
				<th>Quantidade</th>
                <th>Inserido na solicita&ccedil;&atilde;o</th>
                <th>Detalhes do produto</th>
				<th width="170"><a href="#tlaProduto" class="btn btn-primary pull-right" style="display: none;" id="btAdProd" data-toggle="modal"><i class="icon-plus icon-white"></i> Adicionar produto</a></th>
			</tr>
		</thead>
		<tr class="cs-tr ">
			<td colspan="5"><h4 id="comum">Produtos Comuns <i class="fas fa-tag cs-fa"></i></h4></td>
			<td>
				<span id="totalComum" class="totalComum" style="float:right !important; margin:5px !important;">
				</span>
			</td>
		</tr>
		<tbody id="lista_itensSE">
			<!-- Conteúdo da lista -->
		</tbody>

		<tr class="cs-tr ">
			<td colspan="5"><h4 id="consignados">Produtos Consignados <i class="fas fa-user-tag cs-fa"></h4></td>
			<td><span id="totalConsignado" style="float:right !important; margin:5px !important;"></span></td>
		</tr>
		<tbody id="lista_consig">
			<!-- Conteúdo da lista auto fill solicitações.js-->
		</tbody>
	</table>
	
	<!-- Tela Adicionar Produto -->
	<form id="formSolicitacoesProduto">
		<div id="tlaProduto" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaAdicionarProduto" class="close" data-dismiss="modal">X</a>
				<h3>Adicionar Produto </h3>
			</div>
	
			 <div class="btn-group" data-toggle="buttons" style="margin: 15px;">
		     
			</div>
			
			<div class="btn-group" data-toggle="buttons-radio" style="margin-top:15px; display: flex; width:270px;">
				
					<label for="pn" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:78% !important; height:30px; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary active hover-button hidden" id="bt-pn">
							<input checked id="pn" type="radio" name="chProduto" value="pn" style="position: absolute; left: 200vw"> Produto  Comun <span class="correct-pn"></span>
						</button>
					</label>
			
					<label for="pc" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:60% !important; height:90%; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary hover-button hidden"  id="bt-pc">
							<input id="pc" type="radio" name="chProduto" value="pc" style="position: absolute; left: 200vw "> Produto Consignado <span class="correct-pc"></span>
						</button>
					</label>
	
			</div>
			
			<div class="modal-body" style="width: 2050px; height: 4050px;">
				<input type='hidden' id='txIdSol' name='idSolicitacao'>
				
				<div style="display: flex;">
					<label>
						QRCode:
						<input type="text" name="qrcode" id="txQrcode" maxlength="50" class="input-large" onkeypress="return noenter()" autofocus>
						<input type='hidden' id='txIdproduto' name='idProduto'>
					</label>
					<div style="position: absolute; transform: translateX(389px); padding:5px; border: 1px solid #efefef">
						<img id="pro_img" style="cursor: pointer;" onerror="imgError(this)" src="img_pro/placeholder_small.png">
					</div>
					<?php require('partials/imagepopup.php'); ?>	
				</div>
	
				<label class="qtdeQrcode">
					Qtde:
					<input type="number" name="qtde" id="txQtdeqt" class="input-mini">
				</label>
				<br>
				<div id="boxNew">
					<label>Nome:</label>
					<input type="text" name="nome" id="txNome" maxlength="70" autocomplete="off" class="input-xlarge" data-provide="typeahead">
					<br>
				</div>
				<div id="boxQtde">
					<span style="float:left;">Qtde:
					<input type="number" name="qtde"  oninput="validity.valid||(value=value.replace(/\D+/g, ''))" id="txQtde"  min="1" max="99999" style="text-transform:;" class="input-mini"/>
					<br><br>
					</span>
					<span class="hide" style="float:left;">
					Disponível:
					<input type="text" name="disponivel" id="disponivel" style="text-transform:;" readonly class="input-mini"/>
					<br><br>
					</span>
					<span style="float:left;" class="hide">
					Qtde produto:
					<input type="text" name="qtdeproduct" id="qtdeProduct" readonly class="input-mini"/>
					</span>
					<br><br>
					<div style="clear:both !important;"></div>
				</div>
				<div id="divRestoItem" class="hide" style="margin-top: 23px">
					<label>Produto: <span id="txProduto"></span></label>
					<br>
					<label>
						Responsável técnico: 
						<?php 
							echo SolicitacoesHelper::populaComboRTecnico();
						?>
					</label>
					<label id = 'equip'>
						Equipamento: 
						<?php 
							echo SolicitacoesHelper::populaComboEEsterilizacao($_SESSION['equipamento']);
						?>
					</label>
					Lote do Equipamento:
					<input type="text" name="loteequipamento" value="<?= $_SESSION['ultimoLote'] ?>" id="loteEquip" style="text-transform:;"/>

					</span>
					<br>
					
					<div id = 'hidde_teste'>
					<input type="radio"   name="estandar" value="0" id = "ventilatorio" class="tipodetergente" <?php echo $_SESSION['estandar'] == 0 ? "checked='checked'" : ''?>> Ventilatório<br><br>
					<input type="radio" name="estandar" value="1" id = "instrumental" class="tipodetergente" <?php echo $_SESSION['estandar'] == 1 ? "checked='checked'" : ''?> > Instrumental<br>
					</div>
					<br>
					<div id = "vent">
					Lote Enzimático	
					<input type='text' name='loteenzimatico' id='vn' class="input-large" readonly value=''>
					</div>
					<div id = "inst">
					Lote Neutro	
					<input type='text' name='loteneutro' id='in' class="input-large" readonly value=''>
					</div>
					<br><br>
					<br>
					<label>Grupo do material: <span id="txGmaterial"></span></label>
					<br>
					<label id="lbReproc">Reprocessamento: <span id="qtdProc">0</span>/<span id="txQtdmaxima"></span></label>
					<br>
				</div>
			</div>
			<div class="modal-footer">
				<input type='hidden' name='acao' id='txAcao' value='slProd'>
				<input type='hidden' name='nReuso' id='txReuso' value=''>
				<a id="lbProdutoinvertido" class="hide" style="color: red; font-weight: bold;">O produto não é <span class="type-product"></span> ou </a>
				<a id="lbProdutoNaoCadastrado" class="hide" style="color: red; font-weight: bold;">o produto não foi cadastrado! </a>
				<a id="lbProdutoDescartado" class="hide" style="color: red; font-weight: bold;">Produto descartado! </a>
                <a href="#" class="btn btn-default hide pull-left" id="btnSubstituir">
					<i class="icon-edit"></i>
					<span>Substituir QRCode</span>
				</a>
				<a href="#" class="btn btn-warning hide" id="btDescartarPro">
					<i class="icon-trash icon-white"></i>
					<span>Descartar produto</span>
				</a>
				<a href="#" class="btn btn-success hide" id="btSalvarPro"><i class="icon-ok icon-white"></i> Adicionar</a>
				<a href="#" class="btn btn-danger hide" id="btOcorrenciaPro"><i class="icon-edit icon-white"></i> Lançar ocorrência</a>
				<a href="#" class="btn btn-danger" id="btCancelarPro" data-dismiss="modal"><i class="icon-remove icon-white"></i> Fechar</a>
			</div>
		</div>
	</form>
	<!-- Tela detail -->
	<script>
function printDiv(close, divName) {
	$(".close").hide();
	$("#btimp").hide();
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
	 
	 location.reload();
}
	</script>
	<div id="telaDetail" class="modal hide fade" style="z-index:9999999;">
		<div id="divimprimi">
			<div class="modal-header">
				<a id="fechaTelaListaProdutos1" id="closes" class="close">X</a>
				<h3>Detalhes<button id="btimp" class="btn btn-success float-right onlyScreen" style="margin-left:100px;" onclick="printDiv('closes', 'divimprimi')">Imprimir</button></h3>
				<input type="hidden" id="txIdPai" value="0">
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<div id="divListaDetail" style="width: 100%; height: 290px; margin-top: 0;">
				</div>
			</div>
		</div>
	</div>

	<!-- Tela detail 2 -->
	<div id="telaDetailcomposto" class="modal hide fade">
		<div id="divimprimi">
			<div class="modal-header">
				<a id="fechaTelaListaProdutos2" id="closes" class="close">X</a>
				<h3>Detalhes <button id="btimp" class="btn btn-success float-right onlyScreen" style="margin-left:100px;" onclick="printDiv('closes', 'divimprimi')">Imprimir</button></h3>
				<input type="hidden" id="txIdPai" value="0">
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<div id="divListaDetail2" style="width: 100%; height: 290px; margin-top: 0;">
				</div>
			</div>
		</div>
	</div>
	
	<!-- Tela Lista de Produtos -->
	<form id="formListaProdutos">
		<div id="telaListaProdutos" class="modal hide fade" data-keyboard="false" data-backdrop="static">
			<div class="modal-header">
				<a id="fechaTelaListaProdutos" class="close">X</a>
				<h3>Lista de Produtos</h3>
		
				<input type="hidden" id="idP">
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<label>Produto composto: <span class="" id="txProdutoPai"></span><input type="hidden" id="txIdPai"></input> <span class="hidden" id="iditemsol"></span></label>

				<a href='#telaDetail' class='btn' title='Detalhe do produto' id="telaDetailclick" data-toggle='modal' style="float:right; z-index:999 !important; bottom:-135px !important;">
					<i class='fas fa-exclamation'></i>
				</a>						
				  <div class="boxinfo">
					<a readonly class="btn btn-info info"><i class="fas fa-info"></i></a>
					<div class="layer">
						<div class="tooltip_info">
							<p>Ultimo setor: <span id="setor"></span></p>
							<p>Conferênte: <span id="conferente"></span></p>
						</div>
					</div>
				  </div>	
			  
				<br>
				<span class="hidden" id="txQrcodePai"></span> Status: <span id="autorizado" style="color:#00FF7F;"></span> <span id="naoautorizado" style="color:red;"></span>
				<h5>Incluir produtos de sua composição na solicitação</h5>
				<input type="hidden" name="idfilho" id="idfilho" value=""/>
				<label>
					QRCode:
					<input type="text" id="txQrcodeFilho" maxlength="15" class="input-large" onkeypress="return noenter()" autofocus>
				</label>
				<div id="divListaFilhos" style="width: 100%; height: 290px; margin-top: 0; background: #dfdfdf; border: 1px solid #cbcbcb; overflow: auto;">
				</div>
				<input type="hidden" name="sobra" id="sobra">
			</div>
			<div class="modal-footer">
				<div id="divFilhoLido" class="hide" style="width: 100%; text-align: right;">
					<input type='text' id='txIdFilhoLido' value='0'>
					<label id="txFilhoLido" class="pull-left" style="font-weight: bold;"></label>
					<label id="lbReprocFilho">Reprocessamento: <span id="qtdProcFilho">0</span>/<span id="txQtdmaximaFilho"></span></label>
				</div>
				<br>
				<div>
					<a id="lbFilhoDescartado" class="hide" style="color: red; font-weight: bold;">Produto descartado! </a>
					<a href="#" class="btn btn-success hide" id="btAdicionarFilho"><i class="icon-ok icon-white"></i> Adicionar</a>
					<a href="#" class="btn btn-warning hide" id="btDescartarFilho"><i class="icon-trash icon-white"></i> Descartar produto</a>
					<a href="#" class="btn btn-danger hide" id="btOcorrenciaFilho"><i class="icon-edit icon-white"></i> Lançar ocorrência</a>
					<a href="#" class="btn btn-danger" id="btFechar"><i class="icon-remove icon-white"></i> Fechar</a>
				</div>
			</div>
		</div>
	</form>

		<!-- Tela Permissao -->
	<form id="formPermissao">
		<div id="telaPermissao" class="modal hide fade" data-keyboard="false" data-backdrop="static">
			<div class="modal-header">

				<h3>Autorização</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<b>Solicite a autorização do responsável técnico do turno atual</b>
				<br><br>
				<label>
					Login:
					<input type="text" id="responsavel" class="input-large" value="" onkeypress="return noenter()" autofocus>
				</label>
				<label>
					Senha:
					<input type="password" id="password" class="input-large" value="" onkeypress="return noenter()" autofocus>
				</label>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" style="float:right;" id="autorizar"><i class="icon-ok icon-white"></i> Autorizar</a>
				<a href="#" class="btn btn-danger" style="float:right; margin-right:20px;" id="naoautorizar">Reter material</a>
				<a href="#" class="btn btn-warning" style="float:right; margin-right:20px;" id="voltarcaixa_filho"> Voltar e inserir</a>
				<a href="#" class="btn" style="float:right; margin-right:20px;" id="voltar">Voltar</a>
			</div>
		</div>
	</form>
		<!-- sbustituir qrcode -->
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
	echo ProdutosHelper::populaNomesProdutos();

	if(isset($_GET['populate'])){
		$arr = SolicitacoesController::getSolicitacoes("ses_idsetor = ".$_GET['id']);
		$solicitacao = $arr[0];
		//print_r($solicitacao);
		echo SolicitacoesHelper::populaCampos($solicitacao);
	}
	include "helper/rodape.php";
?>