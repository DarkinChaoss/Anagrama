<?php

/* JPEGCam Test Script */
/* Receives JPEG webcam submission and saves to local file. */
/* Make sure your directory has permission to write files as your web server user! */

session_start();

$filename = '../../../img/rotulos/' . $_SESSION['lastidrotulo'] . '.jpg'; /*date('YmdHis') .*/
$result = file_put_contents( $filename, file_get_contents('php://input') );
if (!$result) {
	print "Erro: Falha ao gravar dados no arquivo $filename! Verifique as permissões do diretório.\n";
	exit();
}

$filename = '../' . $filename;
$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . $filename;
print $url;

?>