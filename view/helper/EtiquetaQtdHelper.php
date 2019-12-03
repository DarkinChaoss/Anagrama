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
	$item = ItensSolicitacoesController::getItem($request['item']);
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
	die($item->iso_rte_nome);
	$respPreparo = ( !empty( $item->iso_conferidopor ) ? $item->iso_conferidopor : $respPreparo );


	$etq = <<<EOT
		<div class='main'>
			<div class='description'>
				<div class='border'>
					<div class='text'>
						<p>Produto: {$request['nomeProduto']}</p>
						<p><b><span>{$request['dadosProduto1']}</span><span>{$request['dadosProduto2']}</span></b></p>
						<p>Resp téc: {$item->iso_rte_nome}</p>
						<p>Coren: {$item->iso_rte_coren}</p>
						<p>Resp. Prep: {$respPreparo}</p>
						<p>Processo: {$request['metodo']}</p>
						<p>Esterilização: {$dataEsterilizacao}</p>
						<p>Validade: {$dataLimite} Lote: {$request['lote']}</p>
						<p>Reuso: {$request['qtdeProc']} Qtde Peças: {$composicao}</p>
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





	// $etq .= "<div style='float:left; max-width: 80mm; max-height: 40mm;'>";
	// $etq .= "<div style='margin-left:7px; margin-top:10px; font-size:11px !important; font-family: Arial; width:300px !important;' height:151px !important; background:red !important; border:1px solid #000;>";
	// $etq .= 		"<div style='margin-bottom:-10px;'>Produto: " . $request['nomeProduto'] . "</div><br>";

	// $etq .= "<div style='font-size:11px !important; margin-bottom:-10px;'><b>";
	// if($request['dadosProduto1'] != ""){
	// 	$etq .= 		"" . str_replace(",", " / ", $request['dadosProduto1']);
	// }
	// if($request['dadosProduto1'] != "" && $request['dadosProduto2'] != ""){
	// 	$etq .= 		" / ";
	// }
	// if($request['dadosProduto2'] != ""){
	// 	$etq .= 		"" . str_replace(",", " / ", $request['dadosProduto2']);
	// }
	// if($request['dadosProduto1'] == "" && $request['dadosProduto2'] == ""){
	// 	$etq .= 		"<br>";
	// }


	// $etq .= "</b></div><br>";
	// $etq .= 	"<div style='margin-bottom:-10px; width:265px !important;'>Resp. téc.: " . $item->iso_rte_nome . "<br> Coren: " . $item->iso_rte_coren . "</div><br>";
	// $etq .= 	"<div style='margin-bottom:-10px; width:265px !important;'>Resp. prep: " . $respPreparo . "</div><br>";
	// $etq .= 		"<div style='margin-bottom:-10px;'>Processo:  " . $request['metodo'] . "</div><br>";
	// $etq .= 		"<div style='margin-bottom:-10px;'>Esterilização: " . $dataEsterilizacao . "</div><br>";
	// $etq .= 		"<div style='margin-bottom:-10px;'>Validade: " . $dataLimite . "" . " Lote: " . $request['lote'] . "</div><br>";
	// if($request['qtdeProc']){
	// 	$etq .= 		"<span style='margin-bottom:2px;'>Reuso: " . $request['qtdeProc'] . "</span> Qtde Peças: ". $composicao ."<br>";
	// }
	// if($request['qtdeConsignado']){
	// 	$etq .= 	"<br>" . $consignado_text;
	// 	$etq .= "" . $request['qtdeConsignado'] . "</div>";		
	// }
	// $etq .= "</div>";

	// $etq .= "<div style=' width:50px; float:right; transform: rotate(-90deg); margin-top:-70px; margin-right:-40px;'>";
	// $etq .= 	"<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . "CNPJ.png' width='120px' style='margin-top:-10px;'>";
	// $etq .= "<div style='font-size:9px; width:100px !important; margin:2px; margin-top:-3px; margin-left:9px;'>CNPJ: " .$cliente->cli_cpfcnpj. "</div>";
	// $etq .= "<img src='img/codbarras/img" . $produto->pro_id . ".png' width='120px' height='60px' style='margin-top:0px; margin-bottom:0;'/>";		
	// if($request['qtdeProc']){
	// 	$etq .= "<div style='margin-top:-45px; margin-left:21px;'><p style='font-size:13px !important;'>" . $request['qrCode'] . "</p></div>";
	// }else{
	// 	$etq .= "<div> <p style='margin-top:-10px; margin-left:21px; font-size:10px !important;'>" . $request['qrCode'] . "</p></div>";		
	// }
	// $etq .= "</div>";
	// $etq .= "</div>";
	//$etq .= "<div style='width:100%; height: 1mm; page-break-after: always;'></div>";
	










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