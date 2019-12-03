<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>etiqueta</title>
<script src="js/jquery-1.7.min.js"></script>

</head>
<body>
	<input type="hidden" id="qrcodeconf" value="<?php echo $_REQUEST['qrCode'];?>"

	<?php
	/*
	 * Configurações da impressão:
	 * > fonte é configurada via CSS inline;
	 * > margens e formato da página são configurados na impressora;
	 * > para impressão sem prompt e tela cheia: --kiosk --kiosk-printing
	*/
	//$item = new ItensSolicitacaoModel();
	//$item = ItensSolicitacoesController::getItem($_REQUEST['item']);
	
	if($_RESQUST['qrCode']){
		$qrcode = $_REQUEST['qrCode'];
	}else if($_GET['qrCode']){
		$qrcode = $_GET['qrCode'];
	}
	$produto = ProdutosController::getProdutoInSolicitacao($qrcode);

	if(!file_exists('img/img'.$produto->pro_id.'.png')){
		// Gerar etiqueta
	}

	//$dataEsterilizacao = DefaultHelper::converte_data($item->iso_dataesterilizacao);
	//$dataLimite = DefaultHelper::converte_data($item->iso_datalimite);
	/////$respTecnico = new ResponsaveisTecnicosModel();
	/////$respTecnico = ResponsaveisTecnicosController::getRTecnico($item->iso_idrtecnico);
	/*$respPreparo = "";
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
		default:
			break;
	}*/

	/*if(isset($_SESSION['usu_cli_nome'])){
		$masterClientNome = $_SESSION['usu_cli_nome'];
	} else {
		$masterClient = ClientesController::getMasterClient($_SESSION['usu_masterclient']);
		$masterClientNome = $masterClient->cli_nome;
	}*/

	/*if($_REQUEST['composicao'] > 0){
		
		if($_REQUEST['composicao'] > 1){
			$composicao = "Composição: " . $_REQUEST['composicao'] . " itens<br>";
		} else {
			$composicao = "Composição: " . $_REQUEST['composicao'] . " item<br>";
		}
		
		$totalFilhos = ProdutosCompostosController::getCountFilhos("pco_idpai = " . $item->iso_idproduto);
		$composicao = "Itens: " . $_REQUEST['composicao'] . " / " . $totalFilhos . "<br>";
	} else { */
		$composicao = "<br>";
	//}

	// SE FOR REIMPRESSÃO - MONTA INFORMAÇÕES
	/* if( isset($_REQUEST['acao']) && $_REQUEST['acao'] == 'reimpressao' ){

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
	}*/

	$etq .= "<div style='margin-left: 7px; font-family: Arial;'>";
	//$etq .= 	"<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . "CNPJ.png' width='160px'>";
	//$etq .= 	"<div style='font-size: 0.5em; line-height: 120%;'>Resp. téc.: <br>" . $item->iso_rte_nome . " - " . $item->iso_rte_coren . "</div>";
	//$etq .= 	"<div style='font-size: 0.5em; line-height: 120%;'>Resp. preparo: <br>" . $respPreparo . "</div>";
	//$etq .= 	"<div style='margin-top: 5px; font-size: 0.55em; line-height: 120%;'>";
	//$etq .= 		"Hospital: " . $masterClientNome . "<br>";
	//$etq .= 		"Produto: " . $_REQUEST['nomeProduto'] . "<br>";
	//$etq .= 		"<div style='margin-left: 37px; font-size: 1.8em; line-height: 95%;'>";
	/*if($_REQUEST['dadosProduto1'] != ""){
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
	}*/
	//$etq .= 		"</div>";
	//$etq .= 		"Mét. esterilização: " . $_REQUEST['metodo'] . "<br>";
	//$etq .= 		"Lote: " . $_REQUEST['lote'] . "<br>";
	//$etq .= 		"Data de esterilização: " . $dataEsterilizacao . "<br>";
	//$etq .= 		"Data limite de uso: " . $dataLimite . "<br>";
	//$etq .= 		"Nº de reuso: " . $_REQUEST['qtdeProc'] . "<br>";
	//$etq .= 		$composicao;
	$etq .= 		"<div style='text-align: left; margin-left: 10px;'>" . $_REQUEST['qrCode'] . "</div>";
	//$etq .= 	"</div>";
	$etq .= "</div>";

	$nome = "<div style='margin-left: 7px; font-family: Arial; text-align: left; margin-left: 15px; margin-top:-10px;'>" . $produto->pro_nome . "</div>";
	echo stripslashes($etq);
	?>
	<div style='text-align: left;'>
		<img src='img/codbarras/img<?php echo $produto->pro_id ?>.png' height="20px"/>
	</div>
	<?php
		echo $nome;
	?>
</body>
<script type="text/javascript">
	img = new Image();
	img = "img/img.png";

	console.log($('#qrcodeconf').val())
	$.get("view/Barcode2/gerabarras.php?code=" + $('#qrcodeconf').val(), function(){
	});

	window.print();
	setTimeout(function(){ window.close(); }, 1000);
//	setTimeout(function(){ window.close(); }, 1000);
	/*$("body").keypress(function(e){
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla){
			window.close();
		}
	});*/
</script>
</html>