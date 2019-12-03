<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Composição</title>
<script type="text/javascript">
</script>

		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>

</head>
<body>
<?php
	$produto = produtosController::getProduto($_GET['id']);
	
	if($produto){
		$filhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = '{$_GET['id']}'" );

		$listas = null;

		if( !empty( $filhos ) ){
			foreach ($filhos as $filho) {
				$p = ItensSolicitacoesController::getItens("iso_idproduto = {$filho->pco_idfilho} AND iso_dataconferencia IS NULL");
				if( !empty( $p ) ){
					$listas[] = current($p);
				}
			}
		}		
		
		
?>
	<div style="margin:20px;">
		<h4>Itens conferidos</h4>
		<br><br>
		<h3><?php echo 'Material: ' . $produto->pro_qrcode . ' - ' . $produto->pro_nome .' ('.$_GET['qtdeFilhos'].' Itens)' ?></h3>
		<br><br>
		<table class="table">
			<thead>
				<tr>
					<th>Resumo da conferência</th>
					<th><?php echo $_GET['qdeConferido'];?></th>
				</tr>
			</thead>
			<tbody>
				<?php				
					foreach($listas as $lista){

				?>
					<tr>
						<td><?php echo $lista->iso_pro_nome ; ?></td>
						<td><?php echo $lista->iso_qrcode ; ?></td>
					</td>
				<?php				
					}											
				?>

			</tbody>
		</table>
	</div>
<?php		
	}
?>
</body>
<script type="text/javascript">
	window.print();
	setTimeout(function(){ window.close(); }, 1000);
</script>
</html>