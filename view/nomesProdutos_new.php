<?php

    // delete all rows with no name
	
	if(isset($_POST['acao']) && $_POST['acao'] == 'cleanDataBase'){
    	NomesProdutosController::delNotUsed();
    	exit;
    }
	
    if(isset($_POST['acao']) && $_POST['acao'] == 'insertEmptyRecord'){
    	echo NomesProdutosController::insertHoldId();
    	exit;
    }

    if(isset($_POST['acao']) && $_POST['acao'] == 'delNotUsed'){
    	echo NomesProdutosController::delNotUsed();
    	exit;
    }


	if (isset($_POST['nome']) && !isset($_POST['acao'])) {

        header ('Content-type: text/html; charset=utf8');
	   
		if(empty($_POST['id']))
			$res = NomesProdutosController::insert($_POST);
		else{

			$res = NomesProdutosController::update($_POST);
                
			// altera os nomes dos produtos dentro da tabela de produtos
			ProdutosController::updateNome( array( 	'nome_velho' 	=> $_POST['nome_antigo'] , 
													'nome_novo'		=> $_POST['nome'] ) );
                                                                                                      
		}

		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(NomesProdutosController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if($_POST['acao'] == "repetido"){

		if($_POST['saving'] != 1){
			die("OK");
		}

		$res = NomesProdutosController::getNomesProdutos("nop_nome = '" . $_POST['nome'] . "'");
		if($res)
			die("REPETIDO");
		else
			die("OK");
	}

	if($_POST['acao'] == "getIdByName"){
		echo NomesProdutosController::getIdByName($_POST['name']);
		exit;
	}

	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/nomesProdutos.js" charset="ISO-8859-1"></script>

	<style type="text/css">
		.pro_img_large{
			position: absolute;
			top: 8em;
			padding: 1em 1em 0 1em;
			background-color: #ffffff;
			box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
			display: none;
		}
		.pro_img_large_btns{
			padding: 1.2em .5em .5em .5em;
			display: flex;
		}

		.pro_img_large_btns p#edit{
			display: block;
			background-color: #006a7b;
			color: white;
			padding: .7em 1.1em .7em 1.1em;
			margin-right: .7em;
			margin-left: .5em;
			cursor: pointer;
		}

		.pro_img_large_btns #cancel{
			display: block;
			text-decoration: none;
			height: 29px;
			background-color: #006a7b;
			color: white;
			padding: .7em 1.1em .7em 1.1em;
			padding-bottom: 0;
			cursor: pointer;
		}
		.pro_img{
			cursor: pointer;
			width: 100px;
			margin-bottom: 1em;
		}

		}
	</style>

	
	<h1>
		Nomes de produtos
		<small>Novo registro</small>
	</h1>
	
	<form id="formNomeProduto" onSubmit="return false;">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>

		<div class="pro_img">
			<?php $img = file_exists('img_pro/pro'.$_GET['id'].'_small.png') ? 'img_pro/pro'.$_GET['id'].'_small.png' : 'img_pro/placeholder_small.png' ?>
			<img id="thumb" src="<?php echo $img; ?>">
			<input type="hidden" name="img_url" value="<?php echo $img ?>">

			<div class="pro_img_large">
				<?php $img = file_exists('img_pro/pro'.$_GET['id'].'.png') ? 'img_pro/pro'.$_GET['id'].'.png' : 'img_pro/placeholder.png' ?>
				<img src="<?php echo $img; ?>">
				<div class="pro_img_large_btns">
					<label for='item-img' id="croppie-link" data-name="pro" data-id="<?php echo $_GET['id']; ?>">
						<p id="edit">EDITAR</p>
					</label>
					<a href="#" id="cancel">FECHAR</a>
				</div>
			</div>
		</div>

		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="70" class="input-xlarge" autofocus>
		<input type="hidden" name="nome_antigo" id="txNomeAntigo" maxlength="70" class="input-xlarge">		
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>

	<?php require_once('croppie.php'); ?>

<?php
	if(isset($_GET['populate'])){
		$nomeProduto = NomesProdutosController::getNomeProduto($_GET['id']);
		echo NomesProdutosHelper::populaCampos($nomeProduto);
	}
	
	include "helper/rodape.php";
?>