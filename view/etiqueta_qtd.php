<?php 
	if($_POST['action'] == 'store') {

		die(EtiquetaQtdController::insert($_POST['qtd'], $_POST['lote'], $_POST['data'], $_POST['nome_produto']));
		print_r($_POST['data']);
	}
?>

<?php 
	if ($_POST['action'] == 'process') {

		$data = json_decode($_POST['data']);

		$html = EtiquetaQtdHelper_b::parsedata($data);
		echo $html . '***.***' . $_POST['count'];

		die();
	}
?>

<?php if($_REQUEST['print'] == 'true') { ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>etiqueta</title>
<script type="text/javascript">
	img = new Image();
	img = "img/img.png";
</script>
<style type="text/css">

	body, html{
		height: 99%;
	}
	body {
		margin:0;
		padding: 0;
		box-sizing: border-box;
	}
	p {
		margin:0;
		padding: 0;
		font-size: .5em;
	}

	
	.main {
				width: 80mm;
				height: 39mm;
				display: flex;
				justify-content: flex-start;
				align-items: center;
				page-break-after: always;
				overflow: hidden;
			}

			.description {
				width: 51mm;
				height: 90%;
				padding: 2mm;
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: flex-start;
				background: blue;
			}

			.barcode {
				
				width: 21mm;	
				height: 100%;
				display: flex;
			}

			.barcode .logo {
				width: 35mm;
			}

			.barcode .cnpj-text{
				width: 30mm;
				font-size: .4em;
				transform: translate(10px, -6px);
			}

			.barcode .crop  .bars {
				width: 100%;
				height: 20mm;
			}

			.barcode .qrcode {
				width: 35mm;
				text-align: center;
				font-size: .7em;
			}

			.flip {
				transform: rotateZ(-90deg);
				display: flex;
				flex-direction: column;
				align-items: center;
				align-items: center;
			}

			.crop {
				width: 40mm;
				height: 10mm;
			}

			.border {
				border: 1px solid rgba(0,0,0);
				height: 100%;
				padding: 2mm;
				width: 70mm;
				background: transparent;
			}

			.border .text {
				width: 60%;
			}

			p{
				font-size: .7em;
			}
</style>
</head>
<body>
	<?php  
	
	if($_REQUEST['print'] == 'true' && $_REQUEST['especific'] == 'off') {

		$etq = EtiquetaQtdController::getLastEtiquetas();
	}

	if($_REQUEST['print'] == 'true' && $_REQUEST['especific'] == 'on') {
		$etq = EtiquetaQtdController::getEspecificEtiquetas($_REQUEST['id']);
	}


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

<?php } ?>

