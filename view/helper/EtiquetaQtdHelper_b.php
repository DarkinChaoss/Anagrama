<?php 
	class EtiquetaQtdHelper_b {

		public static function monta_etiqueta($request) {
		
	/*
	 * Configurações da impressão:
	 * > fonte é configurada via CSS inline;
	 * > margens e formato da página são configurados na impressora;
	 * > para impressão sem prompt e tela cheia: --kiosk --kiosk-printing
	*/
	$item = new ItensSolicitacaoModel();
	//mexer aqui amanha fzr outro metodo que pegue diferente o valor	
	$item_info = ItensSolicitacoesController::byItemId($request['item']);
	$item = ItensSolicitacoesController::getItem($item_info->iso_idses);

	//die($request['qrCode'] .' '. $item->iso_idses );
	$produto = ProdutosController::getProdutoInSolicitacao($request['qrCode'], $item->iso_idses);

	$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
	$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
	/////$respTecnico = new ResponsaveisTecnicosModel();
	/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);
	$respPreparo = "";

	switch($_SESSION['usu_nivel']){
		case 2:
			$aux = new ConferentesModel();
			$aux = ConferentesController::getConferente($_SESSION['usu_referencia']);
			$respPreparo = $aux->cnf_nome;
			break;
		case 3:
			$aux = new EtiquetadoresModel();
			$aux = EtiquetadoresController::getEtiquetador($_SESSION['usu_referencia']);
			$respPreparo = $aux->eti_nome;
			break;
		case 4:
			$aux = new ResponsaveisTecnicosModel();
			$aux = ResponsaveisTecnicosController::getRTecnico($_SESSION['usu_referencia']);
			$respPreparo = $aux->rte_nome;
			break;
		case 8:
			$aux = new ProducaosModel();
			$aux = ProducaoController::getProducao($_SESSION['usu_referencia']);
			$respPreparo = $aux->pcao_nome;
			break;
		default:
			break;
	}
	
	$cliente = ClientesController::getCliente($_SESSION['usu_masterclient']);	
	if(isset($_SESSION['usu_cli_nome'])){
		$masterClientNome = $_SESSION['usu_cli_nome'];
	} else {
		$masterClient = ClientesController::getMasterClient($_SESSION['usu_masterclient']);
		$masterClientNome = $masterClient->cli_nome;
	}
	if($request['composicao'] > 0){
		/*
		if($request['composicao'] > 1){
			$composicao = "Composição: " . $request['composicao'] . " itens<br>";
		} else {
			$composicao = "Composição: " . $request['composicao'] . " item<br>";
		}
		*/
		$totalFilhos = ProdutosCompostosController::getCountFilhos("pco_idpai = " . $item->iso_idproduto);
		$composicao = "Itens: " . $request['composicao'] . " / " . $totalFilhos . "<br>";
	} else {
		$composicao = "<br>";
	}
	// SE FOR REIMPRESSÃO - MONTA INFORMAÇÕES
	if( isset($request['acao']) && $request['acao'] == 'reimpressao' ){
	    $cliente = ClientesController::getCliente($produto->pro_idcliente);
	    $metodo = MetodosController::getMetodo($item->iso_idmetodo);
	    if($produto->pro_diametrointerno == "" && $produto->pro_curvatura == ""){
	        $request['dadosProduto1'] = "";
	    } else {
	        $request['dadosProduto1'] = (($produto->pro_diametrointerno == "") ? "-" : $produto->pro_diametrointerno);
	        $request['dadosProduto1'] .= ", ";
	        $request['dadosProduto1'] .= (($produto->pro_curvatura == "") ? "-" : $produto->pro_curvatura);
	    }
	    if($produto->pro_comprimento == "" && $produto->pro_calibre == ""){
	        $request['dadosProduto2'] = "";
	    } else {
	        $request['dadosProduto2'] = (($produto->pro_calibre == "") ? "-" : $produto->pro_calibre);
	        $request['dadosProduto2'] .= ", ";
	        $request['dadosProduto2'] .= (($produto->pro_comprimento == "") ? "-" : $produto->pro_comprimento);
	    }
	    $request['nomeCliente'] = $cliente->cli_nome;
	    $request['cidade'] = $cliente->cli_cidade;
	    $request['nomeProduto'] = $produto->pro_nome;
	    $request['metodo'] = $metodo->met_nome;
	    $request['lote'] = $item->iso_lote;
	    $request['qtdeProc'] = $item->iso_nreuso;
	}else{
	    $metodo = MetodosController::getMetodo($item->iso_idmetodo);
	    $request['metodo'] = $metodo->met_nome;		
	}
	
	if($request['qtdeConsignado']){
		$consignado_text = 'Consignado: ';
	}else{
		$consignado_text = '';
	}

	
	$respPreparo = ( !empty( $item->iso_conferidopor ) ? $item->iso_conferidopor : $respPreparo );

	$etq = <<<EOT
		<div class='main'>
			<div class='description'>
				<div class='border'>
					<div class='text'>
						<p>Produto: {$request['nomeProduto']}</p>
						<p><b><span>{$request['dadosProduto1']}</span><span>{$request['dadosProduto2']}</span></b></p>
						<p>Resp tec: {$item->iso_rte_nome}</p>
						<p>Coren: {$item->iso_rte_coren}</p>
						<p>Resp. Prep: {$respPreparo}</p>
						<p>Processo: {$request['metodo']}</p>
						<p>Esterilizacao: {$dataEsterilizacao}</p>
						<p>Validade: {$dataLimite}</p>
						<p>Lote: {$request['lote']}</p>
						<p>Reuso: {$request['qtdeProc']} Qtde Pecas: {$composicao}</p>
					</div>
				</div>
			</div>
			<div class='barcode'>
				<div class='flip'>
					<img class='logo' src='img/{$_SESSION['usu_cli_logo']}CNPJ.png'>
					<div class='cnpj-text'>CNPJ: {$cliente->cli_cpfcnpj}</div>
					<div class='crop'> <img class='bars' src='img/codbarras/img{$produto->pro_id}.png'/> </div>
					<div class='qrcode'>{$request['qrCode']}</div>
				</div>
			</div>
		</div>
EOT;
		return $etq;
	
	}


		public static function parsedata($data) {
 // qrCode=CLE17&nomeProduto=CLE&dadosProduto1=&dadosProduto2=&nomeSetor=&metodo=	 ** Escolha **OXIDO DE ETILENOVAPOR&qtdeProc=12&qtdeConsignado=&lote=sssss&item=1717&composicao=CE

			$request = array();
			$html = '';
			foreach ($data as $registro) { 
				$cell = explode('&', $registro); // ['qrCode=CLE17', 'nomeProduto=CLE']

				foreach ($cell as $val) { //
					$temp = explode('=', $val);
					$key = $temp[0];
					$value = $temp[1];
					$request[$key] = $value;
				}
				
				$html .= self::monta_etiqueta($request);
			}

			return $html;

		}



	}