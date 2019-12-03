<?php
    @session_start();
	require_once("Image/Barcode2.php"); // chamada para a biblioteca Image_Barcode
	require_once("../../controller/ProdutosController.php");
	require_once("../../model/Conexao.php");
	require_once("../../model/ProdutosModel.php"); 

	$produto = ProdutosController::getProdutos("pro_qrcode='{$_GET['code']}' ");  

	
	$code = $_GET['code'].'_'.$produto[0]->pro_id; // recuperando o código
    $type = 'code128'; // tipo de barra gerada
    Image_Barcode2::draw($code, $type); // Imprimindo o código de barras na tela