<?php
    @session_start();

    require_once("../../env.php");
    
	require_once("Image/Barcode2.php"); // chamada para a biblioteca Image_Barcode
	require_once("../../controller/ProdutosController.php");
	require_once("../../model/Conexao.php");
	require_once("../../model/ProdutosModel.php");

	
	$arrText = explode(".", $_GET['code']);
	if(count($arrText) >2){
		$qrcode = $arrText[0].'.'.$arrText[1];
	} 
	else{
		$qrcode =  $_GET['code'];
	}
	error_log('new qrcode:'. $qrcode);
	print_r($produto = ProdutosController::getProdutos("pro_qrcode='{$qrcode}' "));

	

	$code = $_GET['code'].'_'.$produto[0]->pro_id; // recuperando o código

	$type = 'code128'; // tipo de barra gerada
 	Image_Barcode2::draw($code, $type); // Imprimindo o código de barras na tela