<?php

    ini_set('max_execution_time', 360);
	session_cache_expire(60);
	session_start();


error_reporting(E_ERROR);
ini_set(“display_errors”, 1 );

//	error_reporting(E_ERROR);
	/* setlocale(LC_CTYPE, 'pt_BR');
	mb_internal_encoding("UTF-8");
	mb_http_output("ISO-8859-1");
	header("Content-Type: text/html; charset=UTF-8",true); */
	
	// no-cache
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: ". gmdate('D, d M Y H:i:s') ." GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	header("Expires: 0");
	date_default_timezone_set('America/Sao_Paulo');
	
	// OUTRA OP��O
	//<script src="js/my_script.js?v= < ? =filemtime('js/my_script.js'); ? > "></script>
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Solu��es em T.I. � 2013
*/
?>
