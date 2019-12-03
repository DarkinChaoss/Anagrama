<?php
/*
session_start();
// se for cliente 801 = Centrinho - Redireciona para skm.com.br/centrinho
if( $_SESSION['usu_masterclient'] == '801' AND $_SERVER['SERVER_NAME'] != 'localhost' ){
	echo "	<script>
				location.href = 'http://skm.com.br/centrinho/home';
			</script>";
}
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<meta name="language" content="pt-BR"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo getenv("PAGE_HEADER"); ?></title>
		<link rel="shortcut icon" type="image/png" href="img/tms_icon.png"/>
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/app.css">
		<link rel="stylesheet" href="css/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css">
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="stylesheet" href="view/font-awesome/css/fontawesome-all.min.css">
		<script src="js/jquery-1.7.min.js"></script>
		<script src="js/jquery.printElement.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="js/jquery.maskedinput.min.js"></script>
		<script src="js/jquery.mask.min.js"></script>	
		<script src="js/jquery.maskMoney/jquery.maskMoney.min.js" type="text/javascript"></script>
		<script src="js/default.js?<?php echo time() ?>"></script>
		<?php include_once("analyticstracking.php") ?>
		<script>
		  $(function() {
			$('#txCusto').maskMoney();
		  })
			
		</script>
	</head>
	<body>
	
		<div class="navbar navbar-static-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="./"><img src="img/tms.png"></a>
					<div class="nav-collapse collapse">
						<ul class="nav" id="menu">
							<?php
							echo MenuHelper::montaMenu();
							?>
						</ul>
						<ul class="nav pull-right">
							<li class="dropdown" id="opcoesUsuario">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="loginUsuario"><i class="icon-user"></i> <?php echo $_SESSION['usu_login']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="alterarSenha">Alterar senha</a></li>
									<li id="menuTrocarUsuario"><a href="#" id="trocarUsuario">Logout</a></li>
									<!--li><a href="#" onclick="fecharJanela()">Sair</a></li-->
								</ul>
							</li>
						</ul>
						<form class="navbar-form pull-right hide" id="formLogin" style="margin-top: 9px;">
							<input class="span2" type="text" name="login" id="txLogin" placeholder="Login" autofocus>
							<input class="span2" type="password" name="senha" id="txSenha" placeholder="Senha">
							<input type="button" class="btn" id="btEntrar" value="Entrar">
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		echo DefaultHelper::permissoesMenu($_SESSION['usu_nivel']);
		//SolicitacoesHelper::corrigeStatusFalho();
		?>
		
		<div class="container">
			<div class="hero-unit">