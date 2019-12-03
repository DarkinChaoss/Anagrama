<?php
	
	// cleverson matias
	if ($_POST['acao'] == "buscaQtdProntos") {
		die($res = ProdutosController::getCountProntos($_POST['qrcode']));
	}

	if ($_POST['acao'] == 'getcombo'){
		$iso = new ItensSaidaModel();
		$newqte = $iso->getcombo($_POST['qrcode'],  $_POST['reflote']);
		$json = json_encode($newqte);
		die($json);
	}

	if ($_POST['acao'] == 'insereProdQte'){
		$iso = new ItensSaidaModel();

		$_POST['cliente'] = $_SESSION['usu_masterclient']; 

		$newqte = $iso->insertQte($_POST);
		$json = json_encode($newqte);
		die($json);
	}

    if( isset( $_POST['validaitem'] ) ){

		$produto = ProdutosController::getProduto( $_POST['pro_id'] );
		if( !empty( $produto ) ){

			$sql = "isa_idproduto = {$produto->pro_id} AND 
					isa_idsaida={$_POST['idsaida']}";

			$isas = ItensSaidaController::getItensSaida( $sql , true );			
			if( empty( $isas ) OR count( $isas ) == 0 ){
				die("OK"); // MATERIAL NÃO ESTA NA SAIDA ATUAL
			}
			else{
				die("ERRO2"); // MATERIAL JA ESTA NA SAIDA ATUAL 
			}

		}
		else{
			die("ERRO1"); // MATERIAL NAO ENCONTRADO
		}

	}
	// salva saída de materiais
	if (isset($_POST['prontuario']) && !isset($_POST['acao'])) {
		if(empty($_POST['id'])) {
			$res = SaidaMateriaisController::insert($_POST);
			$res = "".$res;
		} elseif(!empty($_POST['id'])) {
			$res = SaidaMateriaisController::update($_POST);
		}
		die($res);
	}

	// monta lista de itens adicionados ao prontuário da saída
	if ($_POST['acao'] == "listaItens") {
	   
		die(SaidaMateriaisHelper::listaItensSaida($_POST['id']));
        
	}

	// cleverson matias
	if ($_POST['acao'] == "listaItensConsignados") {
	   
		die(SaidaMateriaisHelper::listaItensSaidaConsignados($_POST['id']));
        
	}

	// busca produto para inserir no prontuário
	if ($_POST['acao'] == "buscarpn" && isset($_POST['setor']) && (isset($_POST['qrcode'])) && (!empty($_POST['qrcode']) || $_POST['qrcode'] != "" || $_POST['qrcode'] != " ")){
		
		if($_POST['lote'] != ''){
			$iso = new ItensSaidaModel();
			$res = $iso->verificaSaida($_POST['qrcode'], $_POST['lote']);
			if ($res == 0){
			  $ret = json_encode('0');
			  die("0"."*;*");
			} 
		}


		$produto = ProdutosController::selectProdutoParaSaida( $_POST['qrcode'] );
	    	

		if (!empty($produto)){
			// $produto = $pro;
            // $produto = ProdutosController::getProduto( $produto->pro_id );            
            $retorno['produto']=$produto;
            
            if($_POST['lote'] == ''){
				$isas = ItensSaidaController::getItensSaida("isa_idproduto = {$produto->pro_id} AND isa_consignado = 0" , true );	
			}
			else{
				$loteref = $_POST['lote'];
				$isas = ItensSaidaController::getItensSaida("isa_idproduto = {$produto->pro_id} AND isa_consignado = 0 AND isa_loteref = {$loteref}" , true );	
			}		
			
			$infoProduto = $produto->pro_nome
						. (($produto->pro_calibre != "") ? ", " . $produto->pro_calibre : "")
						. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "")
						. (($produto->pro_comprimento != "") ? ", " . $produto->pro_comprimento : "")
						. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");
			
			$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);

			$iso = ItensSolicitacoesController::getUltimoReprocDeItem($produto->pro_id);
			$validade = DefaultHelper::converte_data($iso->iso_datalimite);

			$venceu = (($iso->iso_datalimite < date("Y-m-d") && $iso->iso_datalimite != "") ? "S" : "N");

			/*$retorno['idproduto'] = $produto->pro_id;
			$retorno['qtdSaida'] = count($isas);
			$retorno['infoProduto'] = $infoProduto;
			$retorno['lote'] = $iso->iso_lote;
			$retorno['maxqtdprocessamento'] = $produto->pro_maxqtdprocessamento;
			$retorno['nreuso'] = ( $iso->iso_nreuso == null ) ? 0 : $iso->iso_nreuso;
			$retorno['validade'] = ( $validade == null ) ? '-' : $validade ;
			$retorno['descarte'] = $descarte;
			$retorno['status'] = $produto->pro_status;
			$retorno['venceu'] = $venceu;

			header("Content-Type: application/json", true);
			echo json_encode( $retorno );			
			exit;*/

			
			die("$produto->pro_id"."*;*".					
				"$infoProduto"."*;*".						
				"$iso->iso_lote"."*;*".						
				$produto->pro_maxqtdprocessamento."*;*".	
				"$iso->iso_nreuso"."*;*". //4					
				$validade."*;*".							
				$descarte."*;*".							
				$produto->pro_status."*;*".	//7				
				$venceu."*;*".
				$produto->pro_prontos."*;*". //9
				$produto->pro_qtde."*;*".
				"pn"."*;*".				
				count($isas));			
			

		} else {
			die("ERRO");
		}
	}
	
	// busca produto para inserir no prontuário
	if ($_POST['acao'] == "buscarpc" && isset($_POST['setor']) && (isset($_POST['qrcode'])) && (!empty($_POST['qrcode']) || $_POST['qrcode'] != "" || $_POST['qrcode'] != " ")){

        $produto = ProdutosConsignadoController::selectProdutoConsignadoParaSaida( $_POST['qrcode'] );
        
		if (!empty($produto)){

			// $produto = $pro;
            // $produto = ProdutosController::getProduto( $produto->pro_id );            
            $retorno['produto']=$produto;
            
            
			$isas = ItensSaidaController::getItensSaida("isa_idproduto = {$produto->pro_id} AND isa_consignado > 0" , true );			
			$infoProduto = $produto->pro_nome
						. (($produto->pro_calibre != "") ? ", " . $produto->pro_calibre : "")
						. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "")
						. (($produto->pro_comprimento != "") ? ", " . $produto->pro_comprimento : "")
						. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");
			
			$descarte = OcorrenciasProdutosController::getDescarteByProduto($produto->pro_id);

			$iso = ItensSolicitacoesController::getUltimoReprocDeItem($produto->pro_id);

			$validade = DefaultHelper::converte_data($iso->iso_datalimite);

			$venceu = (($iso->iso_datalimite < date("Y-m-d") && $iso->iso_datalimite != "") ? "S" : "N");

			/*$retorno['idproduto'] = $produto->pro_id;
			$retorno['qtdSaida'] = count($isas);
			$retorno['infoProduto'] = $infoProduto;
			$retorno['lote'] = $iso->iso_lote;
			$retorno['maxqtdprocessamento'] = $produto->pro_maxqtdprocessamento;
			$retorno['nreuso'] = ( $iso->iso_nreuso == null ) ? 0 : $iso->iso_nreuso;
			$retorno['validade'] = ( $validade == null ) ? '-' : $validade ;
			$retorno['descarte'] = $descarte;
			$retorno['status'] = $produto->pro_status;
			$retorno['venceu'] = $venceu;

			header("Content-Type: application/json", true);
			echo json_encode( $retorno );			
			exit;*/

			
			die("$produto->pro_id"."*;*".					
				"$infoProduto"."*;*".						
				"$iso->iso_lote"."*;*".						
				$produto->pro_maxqtdprocessamento."*;*".	
				"$iso->iso_nreuso"."*;*".					
				$validade."*;*".							
				$descarte."*;*".							
				$produto->pro_status."*;*".					
				$venceu."*;*".
				"-"."*;*".
				"-"."*;*".
				"pc"."*;*".
				count($isas));							
			

		} else {
			die("ERRO");
		}
		
	}

	// salva produto na saída
	if ($_POST['acao'] == "salvarpn"){
		$_POST['consignado'] = 0;		
		$qtd = ProdutosController::getquantidade($_POST['idProduto']);
		$_POST['setorOrigem'] = ItensSaidaController::getLastSetorDestino($_POST['idProduto']);
		$res = ItensSaidaController::insert($_POST);

		if($res){
			// marca produto como NÃO PRONTO se não tiver quantidade
			if($qtd > 1){
				$res = ProdutosController::setStatus($_POST['idProduto'], '1');
			}else{
				$res = ProdutosController::setStatus($_POST['idProduto'], '0');
			}
			// se é produto composto, salva todos seus filhos na saída junto
			$pro = ProdutosController::getProduto($_POST['idProduto']);

			if($pro->pro_composto == "1"){
				$aFilhos = ProdutosController::setStatusFilhos($_POST['idProduto'], '0');
               
				foreach($aFilhos as $filho){

					$progress = ItensSolicitacoesController::sonCanProgress($filho);
					
					if(!$progress){
						continue;
					}
					
					$_POST['idProduto'] = $filho;
					$_POST['reuso'] = ItensSolicitacoesController::getReprocessamentoItem($filho);
					$_POST['setorOrigem'] = ItensSaidaController::getLastSetorDestino($filho);
					ItensSaidaController::insert($_POST);
				}
                
			}
			if($res){
				// atualiza último lançamento na saída
				$sai = new SaidaMateriaisModel();
				$sai = SaidaMateriaisController::getSaidaMateriais($_POST['idSaida']);
			
				$sai->sma_ultimolancamento = date("Y-m-d H:i:s");
				$res = $sai->update();
				if($res){
					die("OK");
				} else {
					die("ERRO 1");
				}
			} else {
				die("ERRO 2");
			}
		} else {
			die("ERRO 3");
		}		
	}

	// salva produto na saída
	// cleverson matias
	if ($_POST['acao'] == "salvarpc"){
		$_POST['consignado'] = 1;
		$res = ItensSaidaController::insert($_POST);
		if($res){
			// marca produto como NÃO PRONTO
			$res = ProdutosConsignadoController::setStatus($_POST['idProduto'], '0');
			// se é produto composto, salva todos seus filhos na saída junto
			$pro = ProdutosConsignadoController::getProdutoConsignado($_POST['idProduto']);
			if($pro->pro_composto == "1"){
				$aFilhos = ProdutosController::setStatusFilhos($_POST['idProduto'], '0');
                
				foreach($aFilhos as $filho){
					$_POST['idProduto'] = $filho;
					$_POST['reuso'] = ItensSolicitacoesController::getReprocessamentoItem($filho);
					ItensSaidaController::insert($_POST);
				}
                
			}
			if($res){
				// atualiza último lançamento na saída
				$sai = new SaidaMateriaisModel();
				$sai = SaidaMateriaisController::getSaidaMateriais($_POST['idSaida']);
				$sai->sma_ultimolancamento = date("Y-m-d H:i:s");
				$res = $sai->update();
				if($res){
					die("OK");
				} else {
					die("ERRO 1");
				}
			} else {
				die("ERRO 2");
			}
		} else {
			die("ERRO 3");
		}
		
	}
	
	// salva produto na saída
	// cleverson matias
	if ($_POST['acao'] == "salvapnqtd"){
		
		
		$res = ItensSaidaController::insert($_POST);
		
		
		
		if($res){
			
			if($res){
				//atualiza último lançamento na saída
				$sai = new SaidaMateriaisModel();
				$sai = SaidaMateriaisController::getSaidaMateriais($_POST['idSaida']);
				$sai->sma_ultimolancamento = date("Y-m-d H:i:s");
				$res = $sai->update();
				// tira um de produtos prontos
				$setQTD = ProdutosController::setProntosById($_POST['idProduto'], '-1');
				if($res){
					die("OK");
				} else {
					die("ERRO 1");
				}
			} else {
				die("ERRO 2");
			}
		} else {
			die("ERRO 3");
		}
		
		
	}

	// imprime prontuário
	if($_POST['acao'] == "imprimir") {
		$print = "";
		$sma = new SaidaMateriaisModel();
		$sma = SaidaMateriaisController::getSaidaMateriais($_POST['id']);
		$cli = new ClientesModel();
		$cli = ClientesController::getMasterClient($_SESSION['usu_masterclient']);
		$con = ContatosController::getContatos("con_idcliente = " . $cli->cli_id . " AND con_principal = '1'");
		$print .= "	<link rel='stylesheet' href='css/print.css'>";
		$print .= "	<div class='row-fluid'>
						<img src='img/tms.png' width='100px' style='float: left;'>
						<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' style='float: right;'>
					</div>
					<br><br><br>
					<table class='tableLimpa'>
						<tr>
							<td><h4 style='text-align: center; font-size: 1.3em; margin: 0;'>SAÍDA DE MATERIAIS</h4></td>
						</tr>
						<tr style='text-align: center; font-size: 0.9em;'>
							<td colspan='2'>" . $cli->cli_nome . "</td>
						</tr>
						<!--tr style='font-size: 0.9em;'>
							<td colspan='2'>Endereço: " . $cli->cli_logradouro . ", " . $cli->cli_numero . " - " . $cli->cli_bairro . "</td>
						</tr>
						<tr style='font-size: 0.9em;'>
							<td>Telefone: " . $con[0]->con_telefone . "</td>
							<td>Cidade: " . $cli->cli_cidade . " - " . $cli->cli_estado . "</td>
						</tr-->
						<tr>
							<td colspan='2'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='2'>Prontuário: " . $sma->sma_prontuario . "</td>
						</tr>
						<tr>
							<td colspan='2'>Paciente: " . $sma->sma_paciente . "</td>
						</tr>
						<tr>
							<td colspan='2'>Data de cadastro: " . DefaultHelper::converte_data($sma->sma_data) . "</td>
						</tr>
						<tr>
							<td colspan='2'>Data da impressão: " . date("d/m/Y H:i:s") . "</td>
						</tr>
					</table>
					<br>
					<table class='tableLinhas' cellspacing='0'>
						<tr>
							<th>Data da saída</th>
							<th width='70'>Sala</th>
							<th>Produto</th>
							<th>Lote esterilização</th>
							<th>Val. esterilização</th>
							<th>Reuso</th>
							<th>Tipo</th>
						</tr>";
		$total = 0;
		foreach ($_POST['item'] as $item){
			$isa = ItensSaidaController::getItemSaida($item);
			if($isa->isa_consignado){
				$produto = ProdutosConsignadoController::getProdutoConsignado($isa->isa_idproduto);
				$reuso = '';
			}else{
				$produto = ProdutosController::getProduto($isa->isa_idproduto);
				$reuso = $isa->isa_reuso;
			}
			
			if($isa->isa_consignado == 1){
				$verify = 'Consignado';
			}else{
				$verify = 'Comum';
			}
			
			$infoProduto = $produto->pro_nome
						. (($produto->pro_calibre != "") ? ", " . $produto->pro_calibre : "")
						. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "")
						. (($produto->pro_comprimento != "") ? ", " . $produto->pro_comprimento : "")
						. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "");
			$print .= "	<tr>
							<td>" . DefaultHelper::converte_data($isa->isa_data) . "</td>
							<td>" . $isa->isa_sala . "</td>
							<td>" . $produto->pro_qrcode . "<br>" . stripslashes($infoProduto) . "</td>
							<td>" . $isa->isa_lote . "</td>
							<td>" . DefaultHelper::converte_data($isa->isa_validade) . "</td>
							<td>" . $reuso . "</td>
							<td>" . $verify . "</td>
						</tr>";
			$total ++;
		}
		$print .= "		<tr>
							<td colspan='7' class='right bold dark'>
								Total de itens: " . $total . "
							</td>
						</tr>
					</table>";
		// rodapé
		/*
		$print .= "	<table class='tableLimpa'>
						<tr style='font-size: 0.9em;'>
							<td width='65%' style='height: 50px; vertical-align: bottom; border-bottom: 1px solid black;'>
								Entrega - Nome:
							</td>
							<td style='vertical-align: bottom; border-bottom: 1px solid black;'>
								Data e hora:
							</td>
						</tr>
						<tr style='font-size: 0.9em;'>
							<td colspan='2' style='height: 50px; vertical-align: bottom; border-bottom: 1px solid black;'>
								Recebimento - Nome:
							</td>
						</tr>
					</table>";
		*/
		die(utf8_encode($print));

	}

	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	if ($_SESSION['usu_id'] != 34) { // usuário CC do Centrinho USP
		echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6));
	}

	include "helper/cabecalho.php";
?>

	<script src="js/saidaMateriais.js?7"></script>

	<h1>
		Saída de Materiais
		<small>Lançar saída</small>
	</h1>

	<form id="formSaida">
		<?php
		if(substr_count($_REQUEST['prontuario'], "SG") > 0) {
			$idSetor = explode("SG", $_REQUEST['prontuario']);
			$idSetor = $idSetor[1];
			$set = SetoresController::getSetor($idSetor);
		?>
			<!-- SAÍDA GERAL -->
			<div class="row-fluid">
				<div class="span6">
					<input type="hidden" name="id" id="txId">
					<input type="hidden" name="paciente" id="txPaciente" value="<?php echo $set->set_nome; ?>">
					<input type="hidden" name="setor" value="<?php echo $idSetor ?>">
					<label>Prontuário:</label>
					<input type="text" name="prontuario" id="txProntuario" value="<?php echo $_REQUEST['prontuario']; ?>" class="input-medium" readonly>
				</div>
				<div class="span6">
					<label>Setor:</label>
					<?php echo SolicitacoesHelper::populaComboSetor($idSetor); ?>
					<br>
					<label>Última alteração:</label>
					<input type="text" name="data" id="txData" class="input-medium" readonly>
					<br><br>
					<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
					<a href="saidaMateriais" class="btn btn-danger" id="btVoltar"><i class="icon-arrow-left icon-white"></i> Voltar</a>
					<a href="saidaMateriais" class="btn btn-success hide" id="btFinalizar"><i class="icon-ok icon-white"></i> Finalizar</a>
				</div>
			</div>
			<script type="text/javascript">
				$("#slSetor").attr("disabled", "true");
			</script>
		<?php } else { ?>
			<!-- SAÍDA POR PRONTUÁRIO -->
			<div class="row-fluid">
				<div class="span6">
					<input type="hidden" name="id" id="txId">
					<label>Prontuário:</label>
					<input type="text" name="prontuario" id="txProntuario" value="<?php echo $_REQUEST['prontuario']; ?>" class="input-medium" readonly>
					<br>
					<label>Paciente:</label>
					<input type="text" name="paciente" id="txPaciente" class="input-large" autofocus>
					<!--br>
					<label>Sala:</label>
					<input type="text" name="sala" id="txSala" class="input-medium"-->
				</div>
				<div class="span6">
					<label>Setor:</label>
					<?php echo SolicitacoesHelper::populaComboSetor(); ?>
					<br>
					<label>Convênio:</label>
					<?php echo SaidaMateriaisHelper::populaComboConvenio(); ?>
					<br>
					<span class="hide">
                        <label>Data:</label>
    					<input type="text" name="data" id="txData" class="input-medium" readonly>                    
                    </span>   
                    
					<br><br>
					<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
					<a href="#" class="btn btn-danger hide" id="btDescartarAlteracao"><i class="icon-remove icon-white"></i> Descartar alteração</a>
					<a href="saidaMateriais" class="btn btn-danger" id="btVoltar"><i class="icon-arrow-left icon-white"></i> Voltar</a>
					<a href="saidaMateriais" class="btn btn-success hide" id="btFinalizar"><i class="icon-ok icon-white"></i> Finalizar</a>
				</div>
			</div>
		<?php } ?>
	</form>
	<a href="#telaAdicionarProduto" class="btn btn-primary pull-right hide" id="btAdProd" data-toggle="modal"><i class="icon-plus icon-white"></i> Adicionar produto</a>
	<br><br>
	<table class="table table-hover">
		<thead>
			<tr style="font-size: 0.8em;">
				<th width="140">Data</th>
				<th width="70">Sala</th>
				<th>Produto</th>
				<th>Quantidade</th>
<<<<<<< HEAD
				<th width="110">Lote esterilização</th>
				<th width="100">Val. esterilização</th>
				<th width="60">
					<input type="checkbox" class="pull-right" id="ckTodosItensSaida">
					Reuso
				</th>
				<th width="30"><button class="btn" onclick="imprimirProntuario()" title="Imprimir prontuário com os itens selecionados."><i class="icon-print"></i></button></th>
			</tr>
		</thead>
		<style type="text/css">
		  .cs-tr td{
   			padding:0px !important;
   			height: 20px !important;
   			padding-left: 10px !important;
   			background-color: #ceeded !important;
		  }
		  .cs-tr td h4{
			margin: 5px 0 !important;
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
		
	
		
		</style>
		<tr class="cs-tr">
			<td colspan="5"><h4 id="comum">Produtos Comuns <i class="fas fa-tag cs-fa"></i></h4></td>
			<td colspan="2">
				<span id="totalComum" class="totalComum" style="float:right !important; margin:5px !important;">
				</span>
			</td>
		</tr>
		<tbody id="lista_itensSaida">
			<!-- Conteúdo da lista -->
		</tbody>
		<tr class="cs-tr">
			<td colspan="5"><h4 id="consignados">Produtos Consignados <i class="fas fa-user-tag cs-fa"></h4></td>
			<td colspan="3"><span id="totalConsignado" style="float:right !important; margin:5px !important;"></span></td>
		</tr>
		<tbody id="lista_itensSaidaConsignados">
			<!-- Conteúdo da lista -->
		</tbody>
	</table>
	<div style="height: 799px;"></div>

	<!-- cleverson matias -> links de navega?o interna -->
	<div id="cs-linksInternos">
		<a id="cs-comuns" href="#comum" title="Ir até produtos comuns"><div class="cs-link"><i class="fas fa-tag"></i></div></a>
		<a id="cs-consignados" href="#consignados" title="Ir até produtos consignados"><div class="cs-link"><i class="fas fa-user-tag"></i></div></a>
	</div>

	<div class="hide" id="divPrint"></div>

	<!-- Tela Adicionar Produto -->
	<form id="formAdicionarProduto">
		<div id="telaAdicionarProduto" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaAdicionarProduto" class="close" data-dismiss="modal">X</a>
				<h3>Adicionar Produto</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 250px;" id="divSala">
				<label>
					Sala do procedimento:
					<input type="text" name="sala" id="txSalaItem" class="input-medium">
					<a href="#" class="btn btn-primary" id="btConfirmarSala" style="margin-bottom: 11px;"><i class="icon-arrow-right icon-white"></i></a>
				</label>
			</div>
			<div class="modal-body hide" style="width: 530px; height: 350px;" id="divItem">
				<!--
					<div style="margin:30px 0px;">
					   <input type="radio" name="chProduto" value="pn" checked> Produto Normal 
					   <input type="radio" style="margin-left:10px;" name="chProduto" value="pc"> Produto Consignado
					</div>
				-->
				<div class="btn-group" data-toggle="buttons-radio" style="display: flex; width:270px; margin: 0 0 15px 0">	
					<label for="pn" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:48% !important; height:30px; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary active">
							<input checked id="pn" type="radio" name="chProduto" value="pn" style="position: fixed; left: 120vw"> Produto Comun <span class="correct-pn"></span>
						</button>
					</label>
			
					<!-- <label for="pc" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:60% !important; height:90%; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary">
							<input id="pc" type="radio" name="chProduto" value="pc" style="position: fixed; left: 120vw "> Produto Consignado <span class="correct-pc"></span>
						</button>
					</label> -->
				</div>	
				<label>
					QRCode:
					<input type="text" name="qrcode" id="txQrcode" maxlength="20" class="input-large" onkeypress="return noenter()">
					<input type='hidden' id='txIdproduto'>
				</label>
				<br>
				
				<!-- cleverson matias -->
				<div id="boxQtde">
					<span style="float:left;">Qtde:
					<input type="number" name="qtde"  oninput="validity.valid||(value=value.replace(/\D+/g, ''))" id="txQtde"  min="1" max="99999" style="text-transform:;" class="input-mini"/>
					<br><br>
					</span>
					<span style="float:left;">
					Disponível:
					<input type="text" name="disponivel" id="disponivel" style="text-transform:;" readonly class="input-mini"/>
					<br><br>
					</span>
					<br><br>
					<div style="clear:both !important;"></div>
				</div>
				
				
				<div id="divRestoItem" class="hide">
					<label>Produto: <span id="txProduto"></span></label>
					<br>
					<div id="divReuso">
						<label>Lote de esterilização: <span id="txLote"></span></label>
						<br>
						<label>Validade de esterilização: <span id="txValidade"></span></label>
						<br>
						<label id="lbReproc">Reuso: <span id="qtdProc">0</span><span id="txQtdmaxima" class="hide"></span></label>
					</div>
					<div id="divPrimeiroUso" class="hide">
						<label>Este produto ainda não foi utilizado e este será seu <b>primeiro uso</b>.</label>
					</div>
					<div id="comboSetor" class="hide">
						<br>
						<label>Produto possui quantidade selecione o setor:</label>
							<select name='setor' id='slSetorQte' class='input-xlarge'>
								<option value='0'> ** Escolha **</option>
							</select>
						<br>
					</div>
					<br>
					<textarea name="obs" id="txObsIsa" rows="3" placeHolder="Obs." style="width: 97%;"></textarea>
				</div>

			</div>
			<div class="modal-footer">
				<input type='hidden' name='acao' id='txAcao' value='slProd'>
				<a id=lbProdutoNaoCadastrado class="hide" style="color: red; font-weight: bold;">Produto não cadastrado! </a>
				<a id="lbProdutoDescartado" class="hide" style="color: red; font-weight: bold;">Produto descartado! </a>
				<a id="lbProdutoNaoPronto" class="hide" style="color: red; font-weight: bold;">Produto não esterilizado! </a>
				<a id="lbValidadeEsterilizacao" class="hide" style="color: red; font-weight: bold;">A esterilização excedeu o prazo de validade! </a>
				<a href="#" class="btn btn-success hide" id="btSalvarPro"><i class="icon-ok icon-white"></i> Adicionar</a>
				<a href="#" class="btn btn-success hide" id="btSalvarProQte"><i class="icon-ok icon-white"></i> Adicionar</a>
				<a href="#" class="btn btn-danger" id="btCancelarPro" data-dismiss="modal"><i class="icon-remove icon-white"></i> Fechar</a>
			</div>
		</div>
	</form>

<?php
	if(isset($_REQUEST['prontuario'])){
		$saida = SaidaMateriaisController::getSaidaMateriaisByProntuario($_REQUEST['prontuario']);
		if(substr_count($_REQUEST['prontuario'], "SG") > 0)
			echo SaidaMateriaisHelper::populaCamposSaidaGeral($saida);
		else
			echo SaidaMateriaisHelper::populaCamposProntuario($saida);
	}

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