<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>etiqueta</title>
<script type="text/javascript">
	img = new Image();
	img = "img/img.png";
</script>
</head>
<body>
	<?php
	/*
	 * Configura??es da impress?o:
	 * > fonte ? configurada via CSS inline;
	 * > margens e formato da p?gina s?o configurados na impressora;
	 * > para impress?o sem prompt e tela cheia: --kiosk --kiosk-printing
	*/
	$item = new ItensSolicitacaoModel();
	//mexer aqui amanha fzr outro metodo que pegue diferente o valor	
	if($_REQUEST['remp'] == 'true'){
		error_log('entrou no true');
		$item = ItensSolicitacoesController::getItem($_REQUEST['item'], true);
	}
	else{
		error_log('falseeeee');
		$item = ItensSolicitacoesController::getItem($_REQUEST['item']);
	}
	
	$arrText = explode(".", $_GET['qrCode']);
	if(count($arrText) >2){
		$qrcode = $arrText[0].'.'.$arrText[1];
	} 
	else{
		$qrcode =  $_GET['qrCode'];
	}
	error_log('qrcode:   '.$qrcode);
	$produto = ProdutosController::getProdutoInSolicitacao($qrcode, $item->iso_idses);
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
	if($_REQUEST['composicao'] >= 0){
		
		if($_REQUEST['composicao'] > 1){
			$composicao = "Composição: " . $_REQUEST['composicao'] . " itens<br>";
		} else {
			$composicao = "Composição: " . $_REQUEST['composicao'] . " item<br>";
		}
		
		$totalFilhos = ProdutosCompostosController::getCountFilhos("pco_idpai = " . $item->iso_idproduto);
		$composicao =  $_REQUEST['composicao'] . " / " . $totalFilhos . "<br>";
	} else {
		$composicao = "<br>";
	}
	// SE FOR REIMPRESS?O - MONTA INFORMA??ES
	if( isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'reimpressao' ){
	    $cliente = ClientesController::getCliente($produto->pro_idcliente);
	    $metodo = MetodosController::getMetodo($item->iso_idmetodo);
	    if($produto->pro_diametrointerno == "" && $produto->pro_curvatura == ""){
	        $_REQUEST['dadosProduto1'] = "";
	    } else {
	        $_REQUEST['dadosProduto1'] = (($produto->pro_diametrointerno == "") ? "-" : $produto->pro_diametrointerno);
	        $_REQUEST['dadosProduto1'] .= ", ";
	        $_REQUEST['dadosProduto1'] .= (($produto->pro_curvatura == "") ? "-" : $produto->pro_curvatura);
	    }
	    if($produto->pro_comprimento == "" && $produto->pro_calibre == ""){
	        $_REQUEST['dadosProduto2'] = "";
	    } else {
	        $_REQUEST['dadosProduto2'] = (($produto->pro_calibre == "") ? "-" : $produto->pro_calibre);
	        $_REQUEST['dadosProduto2'] .= ", ";
	        $_REQUEST['dadosProduto2'] .= (($produto->pro_comprimento == "") ? "-" : $produto->pro_comprimento);
	    }
	    $_REQUEST['nomeCliente'] = $cliente->cli_nome;
	    $_REQUEST['cidade'] = $cliente->cli_cidade;
	    $_REQUEST['nomeProduto'] = $produto->pro_nome;
	    $_REQUEST['metodo'] = $metodo->met_nome;
	    $_REQUEST['lote'] = $item->iso_lote;
	    $_REQUEST['qtdeProc'] = $item->iso_nreuso;
	}else{
	    $metodo = MetodosController::getMetodo($item->iso_idmetodo);
	    $_REQUEST['metodo'] = $metodo->met_nome;		
	}
	
	if($_REQUEST['qtdeConsignado']){
		$consignado_text = 'Consignado: ';
	}else{
		$consignado_text = '';
	}
	
	$respPreparo = ( !empty( $item->iso_conferidopor ) ? $item->iso_conferidopor : $respPreparo )	;   

	$etq .= "<div style='float:left; margin-left:10mm; padding-top:10mm; max-height: 40mm; max-width: 80mm'>";
	$etq .= "<div style='margin-left:15px; margin-top:10px; line-height: 14px; font-size:12px;  !important; font-family: Arial; width:300px !important;' height:180px !important; border:1px solid #000;>";
	$etq .= 		"<div style='margin-bottom:-20px;'>Produto: " . $_REQUEST['nomeProduto'] . "</div><br>";
	$etq .= "<div style='font-size:11px !important; margin-bottom:-10px; margin-top: 9px;'><b>";

	if($_REQUEST['dadosProduto1'] != ""){
		$etq .= 		"" . str_replace(",", " / ", $_REQUEST['dadosProduto1']);
	}
	if($_REQUEST['dadosProduto1'] != "" && $_REQUEST['dadosProduto2'] != ""){
		$etq .= 		" / ";
	}
	if($_REQUEST['dadosProduto2'] != ""){
		$etq .= 		"" . str_replace(",", " / ", $_REQUEST['dadosProduto2']);
	}
	if($_REQUEST['dadosProduto1'] == "" && $_REQUEST['dadosProduto2'] == ""){
		$etq .= 		"<br>";
	}
	$etq .= "</b></div><br>";
	$etq .= 	"<div style='margin-bottom:-10px; width:290px !important;'>Resp. téc.: " . $item->iso_rte_nome . "<br> Coren: " . $item->iso_rte_coren . "</div><br>";
	$etq .= 	"<div style='margin-bottom:-10px; width:290px !important;'>Resp. prep: " . $respPreparo . "</div><br>";
	$etq .= 		"<div style='margin-bottom:-10px;'>Processo:  " . $_REQUEST['metodo'] . "</div><br>";
	$etq .= 		"<div style='margin-bottom:-10px;'>Esterilização: " . $dataEsterilizacao . "</div><br>";
	$etq .= 		"<div style='margin-bottom:-10px;'>Validade: " . $dataLimite . "" . " Lote: " . $_REQUEST['lote'] . "</div><br>";

	
	if(count($arrText) < 2){
	
		if($_REQUEST['qtdeProc']){
			$etq .= 		"<span style='margin-bottom:2px;'>Reuso: " . $_REQUEST['qtdeProc'] . "</span> Qtde Peças: ". $composicao ."<br>";
		}
		if($_REQUEST['qtdeConsignado']){
			$etq .= 	"<br>" . $consignado_text;
			$etq .= "" . $_REQUEST['qtdeConsignado'] . "</div>";		
		}
	}
	$etq .= "</div>";



	$etq .= "<div style=' width:50px; float:right; transform: rotate(-90deg); margin-top:-110px; margin-right:-60px; margin-bottom: 100px;'>";

	$etq .= 	"<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . "CNPJ.png' width='100px' style='margin-left: -0px; margin-top:-6px;'>";

	$etq .= "<div style='font-size:9px; width:100px !important; margin:2px; margin-top:-10px; margin-left: -4px;'> " .$cliente->cli_cpfcnpj. "</div>";

	$etq .= "<img src='img/codbarras/img" . $produto->pro_id . ".png' width='200px' height='60px' style='margin-top:10px; margin-left:-20px; margin-bottom:0;'/>";		
	if($_REQUEST['qtdeProc']){
		$etq .= "<div style='margin-top:-45px;/* margin-left:50px; */width: 40mm;display: flex;justify-content: center;'><p style='margin-left:-50px; font-size:13px !important;/* width: 60mm; */text-align: center;'>" . $_REQUEST['qrCode'] . "</p></div>";
	}else{
		$etq .= "<div style='margin-top:-45px;/* margin-left:50px; */width: 40mm;display: flex;justify-content: center;'> <p style='margin-left:-50px; font-size:13px !important;/* width: 60mm; */text-align: center;'>" . $_REQUEST['qrCode'] . "</p></div>";		
	}
	$etq .= "</div>";
	$etq .= "</div>";
	/*$etq .= "<div style='margin-left: 7px; font-family: Arial;'>";
	$etq .= 	"<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . "CNPJ.png' width='160px'>";
	$etq .= 	"<br>" . $consignado_text;
	$etq .= 	"<div style='font-size: 0.5em; line-height: 120%;'>Resp. t?c.: <br>" . $item->iso_rte_nome . " - " . $item->iso_rte_coren . "</div>";
	$etq .= 	"<div style='font-size: 0.5em; line-height: 120%;'>Resp. preparo: <br>" . $respPreparo . "</div>";
	$etq .= 	"<div style='margin-top: 5px; font-size: 0.55em; line-height: 120%;'>";
	$etq .= 		"Hospital: " . $masterClientNome . "<br>";
	$etq .= 		"Produto: " . $_REQUEST['nomeProduto'] . "<br>";
	$etq .= 		"<div style='margin-left: 37px; font-size: 1.8em; line-height: 95%;'>";
	if($_REQUEST['dadosProduto1'] != ""){
		$etq .= 		"" . $_REQUEST['dadosProduto1'];
	}
	if($_REQUEST['dadosProduto1'] != "" && $_REQUEST['dadosProduto2'] != ""){
		$etq .= 		"<br>";
	}
	if($_REQUEST['dadosProduto2'] != ""){
		$etq .= 		"" . $_REQUEST['dadosProduto2'];
	}
	if($_REQUEST['dadosProduto1'] == "" && $_REQUEST['dadosProduto2'] == ""){
		$etq .= 		"<br>";
	}
	$etq .= 		"</div>";
	$etq .= 		"M?t. esteriliza??o: " . $_REQUEST['metodo'] . "<br>";
	$etq .= 		"Lote: " . $_REQUEST['lote'] . "<br>";
	$etq .= 		"Data de esteriliza??o: " . $dataEsterilizacao . "<br>";
	$etq .= 		"Data limite de uso: " . $dataLimite . "<br>";
	if($_REQUEST['qtdeProc']){
		$etq .= 		"N? de reuso: " . $_REQUEST['qtdeProc'] . "<br>";
	}
	if($_REQUEST['qtdeProc']){
		$etq .= $composicao;
		$etq .= "<div style='text-align: right; margin-right: 10px;'>" . $_REQUEST['qrCode'] . "</div>";
		
	}else{
		$etq .= "<div style='text-align: right; margin-right: 10px;'>" . $_REQUEST['qrCode'] . "</div>";		
	}
	if($_REQUEST['qtdeConsignado']){
		$etq .= "Qtde produto consignado: " . $_REQUEST['qtdeConsignado'] . "</div>";		
	}
	
		$etq .= "</div>";
		$etq .= "</div>";	
*/
	echo stripslashes($etq);
	?>
</body>
<script type="text/javascript">
	window.print();
	setTimeout(function(){ window.close(); }, 1000);
	/*$("body").keypress(function(e){
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla){
			window.close();
		}
	});*/
</script>
</html>